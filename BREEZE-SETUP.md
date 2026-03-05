# Laravel Breeze — Authentication Setup

**Stack:** Laravel 10 + Breeze (Blade + Vanilla JS)

---

## 1. Install Steps

1. **Create Laravel project** (if not done):
   ```bash
   composer create-project laravel/laravel . --prefer-dist
   ```

2. **Install Breeze:**
   ```bash
   composer require laravel/breeze --dev
   php artisan breeze:install
   ```
   When prompted, choose **Blade** (no React/Vue/API-only). Use **No** for dark mode if you prefer Bootstrap default.

3. **Install front-end dependencies:**
   ```bash
   npm install && npm run build
   ```

4. **Configure environment and DB:**
   - Copy `.env.example` to `.env`, set `APP_KEY` and MySQL in `.env`:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=your_database
     DB_USERNAME=your_user
     DB_PASSWORD=your_password
     ```
   - Run:
     ```bash
     php artisan key:generate
     php artisan migrate
     ```

---

## 2. Config

| What | Where / How |
|------|------------------|
| **Auth config** | `config/auth.php` — guards (web), providers (users). Defaults are fine. |
| **Redirect after login** | `app/Http/Middleware/Authenticate.php` — `redirectTo()` → e.g. `route('dashboard')`. |
| **Redirect after logout** | `app/Http/Controllers/Auth/AuthenticatedSessionController.php` — `destroy()` redirect. |
| **Registration** | To disable: comment out rego routes in `routes/auth.php` and hide nav links. |
| **Password rules** | `app/Http/Requests/Auth/LoginRequest.php` and `PasswordValidationRules` (e.g. in `RegisteredUserController`) for strength. |

**Breeze + Bootstrap:** Breeze ships with Tailwind. To use Bootstrap 5, replace Breeze’s Blade layout and auth view classes with Bootstrap equivalents (or add a second CSS build and override).

---

## 3. Required Commands (summary)

```bash
# One-time setup
composer create-project laravel/laravel . --prefer-dist
composer require laravel/breeze --dev
php artisan breeze:install    # choose Blade
npm install && npm run build

# Env & DB
cp .env.example .env
php artisan key:generate
php artisan migrate

# Dev
php artisan serve
npm run dev
```

---

## 4. Route Protection

**Middleware:** `auth` (session-based). Guest routes use `guest` middleware.

**In routes (`routes/web.php`):**

```php
// Public
Route::get('/', function () { return view('welcome'); });

// Auth routes (login, register, etc.) — added by Breeze in routes/auth.php
require __DIR__.'/auth.php';

// Protected — only logged-in users
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('tasks', TaskController::class);
});
```

**In controller:**  
Use same middleware so all methods are protected:

```php
class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    // ...
}
```

Or per-route only (prefer grouping in `web.php` as above).

**Redirect unauthenticated:**  
If not logged in and hitting a route that uses `auth`, Laravel sends user to `route('login')` (set in `App\Http\Middleware\Authenticate::redirectTo()`).

**Check in Blade:**  
```blade
@auth
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <form method="POST" action="{{ route('logout') }}">@csrf @method('DELETE')<button>Logout</button></form>
@endauth
@guest
    <a href="{{ route('login') }}">Login</a>
@endguest
```

---

## 5. Breeze Files (reference)

| Purpose | Location |
|--------|----------|
| Auth routes | `routes/auth.php` |
| Login/register views | `resources/views/auth/` |
| Layout | `resources/views/layouts/` (e.g. `app.blade.php`) |
| Controllers | `app/Http/Controllers/Auth/` |
| Middleware | `app/Http/Middleware/` (`Authenticate`, `RedirectIfAuthenticated`) |
| Config | `config/auth.php` |

Keep auth routes in `auth.php` and protect app routes with `Route::middleware('auth')->group(...)` in `web.php`.
