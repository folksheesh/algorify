# ALGORIFY

Platform E-Learning & Sertifikasi Digital

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php&logoColor=white)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## 1.1 Judul Proyek

**Algorify**

---

## 1.2 Deskripsi Singkat

Algorify adalah platform e-learning berbasis web yang memungkinkan pengelolaan pelatihan online, ujian, dan penerbitan sertifikat digital. Platform ini dirancang untuk memfasilitasi pembelajaran jarak jauh dengan fitur manajemen kursus, pelacakan progres peserta, pembayaran terintegrasi, dan sistem ujian yang aman.

**Tujuan Proyek:**
- Menyediakan akses pelatihan berkualitas secara online
- Memfasilitasi proses pembelajaran dengan pelacakan progres yang akurat
- Menerbitkan sertifikat digital terverifikasi untuk peserta yang lulus
- Mempermudah admin dan pengajar dalam mengelola konten dan memantau aktivitas peserta

---

## 1.3 Fitur Utama

### Alur Aplikasi

```
┌─────────────────┐
│  Landing Page   │
│   (Login/Reg)   │
└────────┬────────┘
         │
         ▼
    ┌────────┐
    │  Login │◄─────── Google OAuth
    └───┬────┘
        │
        ▼
┌───────────────────────────────────────┐
│          Dashboard (Role-based)        │
├───────────────┬───────────┬───────────┤
│    Peserta    │  Pengajar │   Admin   │
└───────┬───────┴─────┬─────┴─────┬─────┘
        │             │           │
        ▼             ▼           ▼
┌──────────────┐ ┌──────────┐ ┌──────────────┐
│ Pilih Kursus │ │ Kelola   │ │ Kelola Data  │
│ Bayar (DOKU) │ │ Kursus   │ │ Master       │
└──────┬───────┘ └─────┬────┘ └──────┬───────┘
       │               │              │
       ▼               ▼              ▼
┌──────────────┐ ┌──────────┐ ┌──────────────┐
│ Ikuti        │ │ Upload   │ │ Monitoring   │
│ Pelatihan    │ │ Materi   │ │ & Laporan    │
│ (Video/Modul)│ │ & Soal   │ │              │
└──────┬───────┘ └──────────┘ └──────────────┘
       │
       ▼
┌──────────────┐
│ Ujian        │
│ (Pengacakan) │
└──────┬───────┘
       │
       ▼
┌──────────────┐
│ Sertifikat   │
│ Digital (PDF)│
└──────────────┘
```

### Fitur Berdasarkan Role

#### Peserta
- Mendaftar akun baru dan login (email/password atau Google OAuth)
- Mengelola data pribadi (profil)
- Melihat katalog dan memilih kursus
- Melakukan pembayaran melalui DOKU Payment Gateway
- Mengikuti pelatihan (video, modul, materi)
- Pelacakan progres otomatis
- Mengikuti ujian dengan soal teracak
- Mengunduh sertifikat digital setelah lulus

#### Pengajar
- Login dan mengelola profil
- Membuat dan mengelola kursus
- Mengunggah materi (video, modul, PDF)
- Membuat dan mengelola bank soal ujian (impor/ekspor Excel)
- Memantau progres peserta di kursus yang diajar
- Melihat statistik kursus

#### Admin & Super Admin
- Mengelola seluruh data master (peserta, pengajar, kursus, kategori)
- Monitoring aktivitas pelatihan real-time
- Mengelola transaksi pembayaran
- Verifikasi dan penerbitan sertifikat
- Ekspor laporan dan data
- Dashboard analitik dengan grafik pertumbuhan
- Super Admin: mengelola akun admin lainnya

### Fitur Teknis
- Autentikasi multi-provider (Breeze + Google OAuth)
- Manajemen role & permission (Spatie Permission)
- Pembayaran terintegrasi (DOKU Wallet)
- Impor/Ekspor soal ujian (Excel/CSV)
- PDF generator untuk sertifikat (DomPDF)
- Pelacakan progres berbasis event
- Scheduler untuk tugas terjadwal
- Responsive UI (Tailwind CSS + Alpine.js)

---

## 1.4 Tech Stack

### Backend
- **Framework:** Laravel 11.x
- **Language:** PHP 8.2+
- **Authentication:** Laravel Breeze + Socialite (Google OAuth)
- **Authorization:** Spatie Laravel Permission
- **Payment Gateway:** DOKU Wallet API
- **PDF Generator:** barryvdh/laravel-dompdf
- **Excel Import/Export:** maatwebsite/excel

### Frontend
- **CSS Framework:** Tailwind CSS 3.x
- **JavaScript:** Alpine.js 3.x
- **Build Tool:** Vite
- **Icons & UI:** Blade Components + Tailwind Forms

### Database
- **Primary:** MySQL / MariaDB
- **ORM:** Eloquent

### Testing
- **Unit/Feature Testing:** PHPUnit 10.5
- **Mocking:** Mockery

### Development Tools
- **Package Manager:** Composer (PHP), npm (JavaScript)
- **Linting:** Laravel Pint
- **Debugging:** Laravel Ignition, Tinker

---

## 1.5 Instalasi

### Prasyarat
- PHP >= 8.2
- Composer
- Node.js & npm
- MySQL/MariaDB
- Web Server (Apache/Nginx) atau Laravel Sail (Docker)

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd algorify-main-new
   ```

2. **Install Dependencies PHP**
   ```bash
   composer install
   ```

3. **Install Dependencies JavaScript**
   ```bash
   npm install
   ```

4. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Konfigurasi Database**
   
   Edit file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=algorify
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Konfigurasi Google OAuth** (Opsional)
   
   Ikuti panduan di `GOOGLE_OAUTH_SETUP.md`, lalu tambahkan ke `.env`:
   ```env
   GOOGLE_CLIENT_ID=your_client_id
   GOOGLE_CLIENT_SECRET=your_client_secret
   GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
   ```

7. **Konfigurasi DOKU Payment** (Opsional)
   
   Edit `.env`:
   ```env
   DOKU_CLIENT_ID=your_doku_client_id
   DOKU_SECRET_KEY=your_doku_secret_key
   DOKU_SHARED_KEY=your_doku_shared_key
   DOKU_ENVIRONMENT=sandbox
   ```

8. **Migrasi Database & Seeder**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

9. **Link Storage**
   ```bash
   php artisan storage:link
   ```

10. **Build Assets**
    ```bash
    npm run build
    ```

---

## 1.6 Cara Menjalankan

### Development Mode

#### Opsi 1: Server Bawaan PHP

Terminal 1 - Backend:
```bash
php artisan serve
```

Terminal 2 - Frontend (Vite):
```bash
npm run dev
```

Akses aplikasi di: `http://localhost:8000`

#### Opsi 2: Laravel Sail (Docker)

```bash
./vendor/bin/sail up
./vendor/bin/sail npm run dev
```

Akses aplikasi di: `http://localhost`

### Production Mode

1. Build assets untuk produksi:
   ```bash
   npm run build
   ```

2. Optimize Laravel:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. Setup Queue Worker (untuk background jobs):
   ```bash
   php artisan queue:work
   ```

4. Setup Scheduler (untuk tugas terjadwal):
   
   Pada Windows:
   ```powershell
   .\setup_scheduler.ps1
   ```
   
   Atau jalankan manual:
   ```bash
   .\start_scheduler.bat
   ```
   
   Pada Linux/Mac, tambahkan ke crontab:
   ```bash
   * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
   ```

### Testing

Jalankan seluruh tes:
```bash
php artisan test
```

Jalankan tes dengan coverage:
```bash
php artisan test --coverage
```

Jalankan PHPUnit secara langsung:
```bash
./vendor/bin/phpunit
```

---

## 1.7 Struktur Folder

```
algorify-main-new/
├── app/
│   ├── Console/
│   │   └── Commands/           # Artisan custom commands
│   ├── Exports/                # Excel export classes
│   │   ├── SoalExport.php
│   │   └── SoalTemplateExport.php
│   ├── Http/
│   │   ├── Controllers/        # Route controllers
│   │   │   ├── Admin/          # Admin controllers
│   │   │   ├── User/           # User-facing controllers
│   │   │   └── Auth/           # Authentication controllers
│   │   ├── Middleware/         # Custom middleware
│   │   └── Requests/           # Form requests & validation
│   ├── Imports/                # Excel import classes
│   │   └── SoalImport.php
│   ├── Models/                 # Eloquent models
│   │   ├── User.php
│   │   ├── Kursus.php
│   │   ├── Materi.php
│   │   ├── Ujian.php
│   │   ├── Sertifikat.php
│   │   └── ...
│   ├── Providers/              # Service providers
│   ├── Repositories/           # Repository pattern classes
│   ├── Services/               # Business logic services
│   │   └── DokuSignatureService.php
│   ├── View/Components/        # Blade components
│   └── helpers.php             # Global helper functions
│
├── bootstrap/
│   ├── app.php                 # Application bootstrap
│   ├── providers.php           # Service providers config
│   └── cache/                  # Bootstrap cache
│
├── config/                     # Configuration files
│   ├── app.php
│   ├── database.php
│   ├── doku.php                # DOKU payment config
│   ├── services.php            # OAuth & external services
│   └── ...
│
├── database/
│   ├── factories/              # Model factories
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
│
├── docs/                       # Documentation
│   ├── GOOGLE_OAUTH_SETUP.md
│   ├── IMPORT_EXPORT_SOAL.md
│   ├── PROGRESS_TRACKING.md
│   ├── SERTIFIKAT_ADMIN.md
│   ├── TEST_PLAN.md
│   └── template-setup.md
│
├── public/                     # Web root (assets publik)
│   ├── index.php               # Entry point
│   ├── css/
│   ├── js/
│   └── template/               # Template assets
│
├── resources/
│   ├── css/                    # Source CSS files
│   ├── js/                     # Source JS files
│   └── views/                  # Blade templates
│       ├── admin/              # Admin views
│       ├── pengajar/           # Instructor views
│       ├── auth/               # Auth views
│       ├── components/         # Reusable components
│       └── ...
│
├── routes/
│   ├── web.php                 # Web routes
│   ├── api.php                 # API routes
│   ├── auth.php                # Authentication routes
│   └── console.php             # Console routes
│
├── storage/
│   ├── app/                    # Application files
│   ├── framework/              # Framework cache & sessions
│   └── logs/                   # Application logs
│
├── tests/
│   ├── Feature/                # Feature tests
│   ├── Unit/                   # Unit tests
│   └── TestCase.php
│
├── vendor/                     # Composer dependencies
├── .env.example                # Environment template
├── artisan                     # Artisan CLI
├── composer.json               # PHP dependencies
├── package.json                # JavaScript dependencies
├── phpunit.xml                 # PHPUnit config
├── tailwind.config.js          # Tailwind CSS config
├── vite.config.js              # Vite build config
└── README.md                   # This file
```

---

## 1.8 Informasi Tambahan

### Akun Default (Setelah Seeder)

Gunakan kredensial berikut untuk login setelah menjalankan seeder:

- **Super Admin:**
  - Email: `admin@algorify.com`
  - Password: `password`

- **Pengajar:**
  - Email: `pengajar@algorify.com`
  - Password: `password`

- **Peserta:**
  - Email: `peserta@algorify.com`
  - Password: `password`

### Fitur Penting yang Perlu Dikonfigurasi

1. **Email Configuration** (untuk verifikasi & notifikasi)
   
   Edit `.env`:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=mailpit
   MAIL_PORT=1025
   MAIL_USERNAME=null
   MAIL_PASSWORD=null
   MAIL_ENCRYPTION=null
   ```

2. **Queue Configuration** (untuk background jobs)
   
   Recommended: `database` atau `redis`
   ```env
   QUEUE_CONNECTION=database
   ```

3. **Session & Cache**
   ```env
   SESSION_DRIVER=database
   CACHE_DRIVER=file
   ```

### Troubleshooting

#### Error: "Class not found"
```bash
composer dump-autoload
php artisan optimize:clear
```

#### Error: Storage symlink
```bash
php artisan storage:link
```

#### Error: Permission denied (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### Migrasi ulang database
```bash
php artisan migrate:fresh --seed
```

### Dokumentasi Tambahan

- [Setup Google OAuth](GOOGLE_OAUTH_SETUP.md)
- [Impor/Ekspor Soal](docs/IMPORT_EXPORT_SOAL.md)
- [Pelacakan Progres](docs/PROGRESS_TRACKING.md)
- [Sertifikat Admin](docs/SERTIFIKAT_ADMIN.md)

### Kontribusi

Untuk berkontribusi pada proyek ini:
1. Fork repository
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

### Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

### Kontak & Support

- Email: support@algorify.com
- Dokumentasi: `/docs`
- Issue Tracker: GitHub Issues

---

**Dibuat dengan menggunakan Laravel**
