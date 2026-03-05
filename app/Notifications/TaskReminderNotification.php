<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Task $task
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $due = $this->task->due_date->format('M j, Y');
        $url = url('/tasks/' . $this->task->id);

        return (new MailMessage)
            ->subject('Reminder: Task due — ' . $this->task->title)
            ->greeting('Hello ' . ($notifiable->name ?? $notifiable->email) . ',')
            ->line('This is a reminder for the following task.')
            ->line('Task: ' . $this->task->title)
            ->line('Due date: ' . $due)
            ->action('View task', $url)
            ->line('Thank you for using our application.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'due_date' => $this->task->due_date->toDateString(),
        ];
    }
}
