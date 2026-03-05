# To-Do Web App — Clean Architecture Plan

**Stack:** Laravel 10 · MySQL · Bootstrap 5 · Vanilla JS

---

## 1. Folder Structure

```
to-do/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── TaskController.php      # CRUD for tasks
│   │   │   └── HomeController.php      # Dashboard / landing
│   │   └── Middleware/
│   ├── Models/
│   │   └── Task.php
│   └── Providers/
├── config/
├── database/
│   ├── migrations/
│   │   └── xxxx_create_tasks_table.php
│   └── seeders/
│       └── TaskSeeder.php
├── public/
│   ├── css/
│   │   └── app.css                    # Bootstrap + overrides
│   └── js/
│       └── app.js                     # Vanilla JS (AJAX, UI)
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php          # Bootstrap layout
│   │   ├── tasks/
│   │   │   ├── index.blade.php        # List + filters
│   │   │   ├── create.blade.php
│   │   │   ├── edit.blade.php
│   │   │   └── show.blade.php
│   │   └── home.blade.php
│   └── lang/
├── routes/
│   └── web.php
├── storage/
└── .env
```

---

## 2. Routes Plan

| Method | URI | Action | Purpose |
|--------|-----|--------|---------|
| GET | `/` | HomeController@index | Dashboard / redirect to tasks |
| GET | `/tasks` | TaskController@index | List tasks (with filters) |
| GET | `/tasks/create` | TaskController@create | Show create form |
| POST | `/tasks` | TaskController@store | Save new task |
| GET | `/tasks/{task}` | TaskController@show | Show single task |
| GET | `/tasks/{task}/edit` | TaskController@edit | Show edit form |
| PUT/PATCH | `/tasks/{task}` | TaskController@update | Update task |
| DELETE | `/tasks/{task}` | TaskController@destroy | Delete task |
| PATCH | `/tasks/{task}/toggle` | TaskController@toggle | Toggle completed (optional, for AJAX) |

**Optional API-style (for Vanilla JS):**  
`POST /tasks/{task}/toggle` or `PATCH /tasks/{task}` with `completed` in body.

---

## 3. MVC Flow

```
Request → routes/web.php → Middleware → Controller
                                    ↓
                              Model (Task)
                                    ↓
                              DB (MySQL)
                                    ↓
                              View (Blade + Bootstrap)
                                    ↓
Response (HTML) ← optional: JS fetches JSON for toggle/sort
```

- **Model:** `Task` — fields: `id`, `title`, `description`, `due_date`, `priority`, `completed`, `timestamps`.
- **Controller:** Validate input, call `Task::` methods, return `view()` or `redirect()`; one optional JSON response for toggle.
- **View:** Blade layouts; Bootstrap 5 for grid, cards, forms, buttons; Vanilla JS for form checks, optional AJAX toggle/delete.

---

## 4. Feature List Summary

| Feature | Description |
|---------|-------------|
| **Task CRUD** | Create, read, update, delete tasks |
| **List & filter** | List all; filter by status (all / pending / completed), priority, due date |
| **Task fields** | Title, description (optional), due date, priority (low/medium/high), completed flag |
| **Validation** | Server-side (Laravel Form Request or `$request->validate`) |
| **UI** | Bootstrap 5: responsive layout, forms, buttons, badges for priority/status |
| **Interactivity** | Vanilla JS: optional quick toggle complete, delete confirm, client-side validation |
| **Auth (optional)** | Later: `php artisan make:auth` or Laravel Breeze — scope tasks by `user_id` |

---

## 5. Database (MySQL)

**Table: `tasks`**

| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | Auto |
| title | string | Required |
| description | text | Nullable |
| due_date | date | Nullable |
| priority | enum('low','medium','high') | Default medium |
| completed | boolean | Default false |
| created_at, updated_at | timestamps | |

---

Keep controllers thin, validation in Form Requests or inline, and all front-end logic in one `app.js` for maintainability.
