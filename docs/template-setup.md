# Template integration guide

This project is wired to use a plain-HTML template for Login, Register, and Dashboard pages.

## 1) Copy your built template assets

Copy everything from your template `dist/` into `public/template/`.

Recommended layout under `public/template/`:
- `css/`  -> CSS files (e.g. style.css, vendor.css)
- `js/`   -> JS files (e.g. app.js, vendor.js)
- `img/`  -> images/icons
- `fonts/` -> webfonts (create if needed)
- `vendor/` -> any third-party assets (optional)

Example (PowerShell):

```powershell
# Adjust the source path to your actual template folder
Copy-Item -Path "C:\Users\Muhammad Zein\Documents\KuliyeahProject\dist\*" -Destination "public\template" -Recurse -Force
```

## 2) Point the Blade layout to your files

Open `resources/views/layouts/template.blade.php` and update the CSS/JS links to match your files, for example:

```html
<link rel="stylesheet" href="{{ asset('template/css/style.css') }}">
<script src="{{ asset('template/js/main.js') }}" defer></script>
```

Add more `<link>`/`<script>` tags if your template has multiple files. You can also place vendor files under `public/template/vendor` and link them via `asset('template/vendor/...')`.

## 3) Paste your header/sidebar/footer HTML

In `resources/views/layouts/template.blade.php`, fill these sections with your template markup:
- `@section('header')` → header/navbar HTML
- `@section('sidebar')` → sidebar HTML (optional)
- `@section('footer')` → footer HTML

You can do this by editing the layout, or from child views using:

```blade
@section('header')
    <!-- your header HTML here -->
@endsection
```

## 4) Pages already wired to the template

The following pages already extend the template layout and are ready to use:
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`
- `resources/views/dashboard.blade.php`

Replace their placeholder content with your template page sections if you have specific page markup (e.g., from `login.html` in your template). The forms are already connected to Laravel routes and CSRF.

## 5) Using assets in Blade

Always use the `asset()` helper so paths work in all environments:

```blade
<img src="{{ asset('template/img/logo.png') }}" alt="Logo">
<link rel="stylesheet" href="{{ asset('template/css/style.css') }}">
<script src="{{ asset('template/js/main.js') }}" defer></script>
```

## 6) Routes and auth

- Home `/` renders the login page (HTTP 200 to keep default tests passing)
- `/login`, `/register` are provided by Breeze
- `/dashboard` is protected by `auth`

You can change the home page later by editing `routes/web.php`.

## 7) Sessions and database

- `.env` uses `SESSION_DRIVER=database`
- The sessions migration is present and migrations are applied

If you change DB credentials, run:

```powershell
php artisan migrate
```

## 8) Run the app

```powershell
php artisan serve
```

Open http://127.0.0.1:8000 to access the Login page.

## 9) Troubleshooting

- 404 for CSS/JS: ensure files exist under `public/template` and `<link>/<script>` paths match
- Mixed content in template HTML: rewrite paths from `assets/...` to `{{ asset('template/...') }}` inside Blade files
- Email verification: currently not required for Dashboard; add `verified` middleware back if needed
