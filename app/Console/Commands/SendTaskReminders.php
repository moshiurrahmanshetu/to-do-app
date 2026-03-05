<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskReminderNotification;
use Illuminate\Console\Command;

class SendTaskReminders extends Command
{
    protected $signature = 'tasks:send-reminders';

    protected $description = 'Send email reminders for tasks due today or tomorrow (prevents duplicate via notification_sent)';

    public function handle(): int
    {
        $today = now()->startOfDay();
        $tomorrow = now()->addDay()->startOfDay();

        $tasks = Task::query()
            ->whereNotNull('due_date')
            ->where('status', 'pending')
            ->where('notification_sent', false)
            ->where(function ($q) use ($today, $tomorrow) {
                $q->whereDate('due_date', $today)
                    ->orWhereDate('due_date', $tomorrow);
            })
            ->with('user')
            ->get();

        $sent = 0;
        foreach ($tasks as $task) {
            if (!$task->user) {
                continue;
            }
            $task->user->notify(new TaskReminderNotification($task));
            $task->update(['notification_sent' => true]);
            $sent++;
        }

        $this->info("Sent {$sent} reminder(s).");
        return self::SUCCESS;
    }
}
