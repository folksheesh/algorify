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

Aplikasi ini dirancang sebagai wadah pembelajaran mandiri, repositori materi, dan media promosi produk pelatihan TIK.

**Tujuan Proyek:**
Membangun platform layanan pelatihan TIK berbasis LMS yang berfungsi sebagai wadah pembelajaran mandiri, repository materi, serta media promosi produk pelatihan TIK. Platform ini ditujukan untuk masyarakat umum dan dikelola oleh layanan bisnis Jurusan TIK PNJ dengan tujuan utama menghasilkan pendapatan.

Aplikasi yang akan dibuat diharapkan dapat memberikan manfaat pada aspek berikut:

- **Model Pembelajaran Fleksibel:** Mendukung pelatihan full online (video, quiz, ujian), hybrid (materi online + pertemuan via Zoom/Google Meet), dan tatap muka (online maupun offline), serta berfungsi sebagai repository materi pembelajaran.
- **Dashboard Analitik:** Menyediakan analisis data terkait jumlah peserta, jenis pelatihan, transaksi, keuntungan, segmentasi pengguna (profesi, umur, lokasi), serta performa produk pelatihan (mana yang laku dan tidak). Dashboard ini menjadi dasar pengambilan keputusan strategis.
- **Kemudahan Akses:** Platform bersifat user-friendly, responsif, dan dapat diakses dari berbagai perangkat, termasuk dengan layar kecil.

---

## 1.3 Fitur Utama

### Alur Aplikasi

#### Admin
- Mengelola data pengguna (peserta & pengajar)
- Mengunggah dan mengelola kursus
- Mengunggah dan mengelola materi pelatihan
- Memantau transaksi
- Melihat laporan melalui dashboard & analitik

#### Pengajar
- Login ke sistem sebagai pengajar
- Mengelola kursus
- Mengunggah dan mengelola materi pelatihan
- Memantau progres peserta

#### Peserta
- Mengakses dan login ke sistem Algorify
- Melihat dan memilih pelatihan yang tersedia
- Melakukan pendaftaran dan pembayaran
- Mengikuti pelatihan (mengakses materi video/modul, mengerjakan kuis dan ujian)
- Menyelesaikan pelatihan dan mendapatkan sertifikat

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
- **Framework:** Laravel 11
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
- **Primary:** PostgreSQL
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
- PostgreSQL/MySQL
- Web Server (Apache/Nginx) atau Laravel Sail (Docker)

### Langkah Instalasi

1. **Clone repo**
   ```bash
   git clone https://github.com/folksheesh/algorify.git
   cd algorify
   ```

2. **Install dependency backend (PHP/Laravel)**
   ```bash
   composer install
   ```

3. **Install dependency frontend (Vite/Tailwind)**
   ```bash
   npm install
   ```

4. **Buat file `.env` dari template**
   - Linux/Mac:
     ```bash
     cp .env.example .env
     ```
   - Windows PowerShell:
     ```powershell
     Copy-Item .env.example .env
     ```

5. **Generate APP_KEY**
   ```bash
   php artisan key:generate
   ```

6. **Siapkan database**
   - **PostgreSQL (default):** pastikan PostgreSQL berjalan dan buat database `algorify`.
     - Via `psql`:
       ```bash
       psql -U postgres -c "CREATE DATABASE algorify;"
       ```
     - Atau buat dari pgAdmin.
   - **MySQL (opsional):** pastikan MySQL berjalan dan buat database `algorify`.

7. **Atur koneksi database di `.env`**
   **PostgreSQL (default):**
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=algorify
   DB_USERNAME=postgres
   DB_PASSWORD=
   ```

   **MySQL (opsional):**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=algorify
   DB_USERNAME=root
   DB_PASSWORD=
   ```

8. **Migrasi + seed**
   ```bash
   php artisan migrate --seed
   ```

9. **Link storage (wajib untuk file upload/akses storage)**
   ```bash
   php artisan storage:link
   ```

10. **Konfigurasi Google OAuth (opsional)**
   Ikuti panduan di `docs/GOOGLE_OAUTH_SETUP.md`, lalu isi di `.env`:
   ```env
   GOOGLE_CLIENT_ID=your_client_id
   GOOGLE_CLIENT_SECRET=your_client_secret
   GOOGLE_REDIRECT_URL=http://localhost:8000/auth/google/callback
   ```

11. **Konfigurasi DOKU Payment (opsional)**
   Isi di `.env` (sesuai `config/doku.php`):
   ```env
   DOKU_CLIENT_ID=your_doku_client_id
   DOKU_SECRET_KEY=your_doku_secret_key
   DOKU_IS_PRODUCTION=false
   DOKU_DISABLE_SSL_VERIFY=true
   ```

12. **Jalankan aplikasi (2 terminal)**
   - Terminal 1 (backend):
     ```bash
     php artisan serve
     ```
   - Terminal 2 (frontend):
     ```bash
     npm run dev
     ```

   Buka: `http://localhost:8000`

> **Jika error koneksi PostgreSQL di Windows:** pastikan ekstensi PHP `pdo_pgsql` aktif (di `php.ini`), lalu restart terminal/server.

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
│   ├── helpers.php             # Helper global (fungsi bantu)
│   ├── Console/
│   │   └── Commands/           # Artisan custom commands
│   ├── Exports/                # Excel export
│   │   ├── SoalExport.php
│   │   └── SoalTemplateExport.php
│   ├── Imports/                # Excel import
│   │   └── SoalImport.php
│   ├── Http/
│   │   ├── Controllers/        # Controller (logika halaman/fitur)
│   │   │   ├── Admin/          # Halaman & fitur khusus Admin
│   │   │   ├── Api/            # Endpoint API
│   │   │   ├── Auth/           # Login/daftar/OAuth
│   │   │   ├── User/           # Halaman & fitur peserta/user
│   │   │   └── ...             # Controller lain (file .php langsung)
│   │   ├── Middleware/         # Middleware (filter auth/role/dll.)
│   │   └── Requests/           # Validasi request (aturan input form)
│   ├── Models/                 # Model Eloquent (mapping tabel DB)
│   ├── Providers/              # Service providers
│   ├── Repositories/           # Pola repository (akses data)
│   ├── Services/               # Service / logika bisnis
│   │   └── DokuSignatureService.php
│   └── View/
│       └── Components/         # Blade components
│
├── bootstrap/
│   ├── app.php                 # Bootstrap aplikasi
│   ├── providers.php           # Konfigurasi providers
│   └── cache/                  # Cache bootstrap
│
├── config/                     # Configuration files
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── doku.php                # DOKU payment config
│   ├── permission.php          # Role/permission (Spatie)
│   ├── services.php            # OAuth & external services
│   └── ...
│
├── database/
│   ├── factories/              # Data dummy (untuk testing/seeding)
│   ├── migrations/             # Perubahan struktur tabel DB
│   └── seeders/                # Data awal (akun default, master data)
│
├── docs/                       # Documentation
│   ├── GOOGLE_OAUTH_SETUP.md
│   ├── IMPORT_EXPORT_SOAL.md
│   ├── PROGRESS_TRACKING.md
│   ├── SERTIFIKAT_ADMIN.md
│   └── template-setup.md
│
├── public/                     # Web root (assets publik)
│   ├── index.php               # Entry point
│   ├── .htaccess               # Konfigurasi Apache (jika dipakai)
│   ├── css/                    # CSS yang dipakai browser
│   │   ├── admin/              # CSS halaman Admin
│   │   ├── kursus/             # CSS halaman Kursus
│   │   ├── pengajar/           # CSS halaman Pengajar
│   │   ├── peserta/            # CSS halaman Peserta
│   │   └── profile/            # CSS halaman Profil
│   ├── images/                 # Gambar statis
│   │   └── Group 1000015019.png
│   ├── js/                     # JavaScript statis
│   │   └── indonesia-cities.js
│   ├── template/               # Template assets
│   ├── storage                 # Symlink ke storage/app/public
│   ├── favicon.ico             # Icon tab browser
│   ├── favicon.png             # Icon alternatif
│   └── robots.txt              # Aturan indexing crawler
│
├── resources/
│   ├── css/                    # Sumber CSS (sebelum dibuild oleh Vite)
│   │   ├── admin/              # CSS khusus admin (source)
│   │   └── app.css             # Entry CSS utama
│   ├── js/                     # Sumber JavaScript (sebelum dibuild)
│   │   ├── components/         # Komponen UI (Vue)
│   │   ├── layouts/            # Layout tampilan (Vue)
│   │   ├── router/             # Routing frontend (Vue)
│   │   ├── stores/             # State management (Vue)
│   │   ├── views/              # Halaman frontend (Vue)
│   │   ├── app.js              # Entry JS utama
│   │   ├── App.vue             # Root component Vue
│   │   ├── axios.js            # Konfigurasi request HTTP
│   │   └── bootstrap.js        # Inisialisasi frontend
│   └── views/                  # Blade templates (halaman Laravel)
│       ├── layouts/            # Kerangka halaman (header/footer)
│       ├── components/         # Komponen Blade reusable
│       ├── admin/              # Halaman Admin
│       ├── pengajar/           # Halaman Pengajar
│       ├── user/               # Halaman Peserta/User
│       ├── auth/               # Halaman login/register
│       ├── kursus/             # Halaman kursus
│       ├── profile/            # Halaman profil
│       ├── verify/             # Halaman verifikasi (contoh: sertifikat)
│       ├── vendor/             # Override view package pihak ketiga
│       └── ...
│
├── routes/
│   ├── web.php                 # Web routes
│   ├── api.php                 # API routes
│   ├── auth.php                # Authentication routes
│   └── console.php             # Console routes
│
├── storage/
│   ├── app/                    # File aplikasi (upload/export)
│   │   └── public/             # File yang boleh diakses publik (via public/storage)
│   ├── framework/              # File runtime Laravel
│   │   ├── cache/              # Cache aplikasi
│   │   ├── sessions/           # Session login
│   │   ├── views/              # Cache hasil render Blade
│   │   └── testing/            # Kebutuhan testing
│   └── logs/                   # Log aplikasi
│
├── tests/
│   ├── Feature/                # Tes alur fitur (end-to-end level aplikasi)
│   ├── Unit/                   # Tes fungsi kecil (unit)
│   └── TestCase.php
│
├── vendor/                     # Composer dependencies
├── .editorconfig
├── .env                        # Konfigurasi lokal (jangan di-commit)
├── .env.example                # Environment template
├── .gitattributes
├── .gitignore
├── artisan                     # Artisan CLI
├── composer.json               # PHP dependencies
├── composer.lock               # Lock dependencies PHP
├── fix_metode_pembayaran.sql
├── nixpacks.toml
├── package-lock.json
├── package.json                # JavaScript dependencies
├── phpunit.xml                 # PHPUnit config
├── postcss.config.js
├── Procfile
├── setup_scheduler.ps1
├── start_scheduler.bat
├── tailwind.config.js          # Tailwind CSS config
├── vercel.json
├── verify_seeder.php
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

#### Dokumen di Repository

- [Setup Google OAuth](docs/GOOGLE_OAUTH_SETUP.md)
- [Impor/Ekspor Soal](docs/IMPORT_EXPORT_SOAL.md)
- [Pelacakan Progres](docs/PROGRESS_TRACKING.md)
- [Sertifikat Admin](docs/SERTIFIKAT_ADMIN.md)

#### Referensi & Dokumen Eksternal

- **Manajemen Proyek**
   - 🔗 [Google Drive](https://drive.google.com/drive/u/1/folders/1oAxSLa0hDV8qDVVc9A-17Wq_4-t6zhrQ)
   - 🔗 [Notion Layanan Pelatihan TIK - Algorify](https://www.notion.so/2627ae519f5b8055b911f65df68dec5c?pvs=21)
   - 🔗 [WBS dan Timeline SDLC](https://docs.google.com/spreadsheets/d/16pAwnZB_--z471tSl9-F57v7FtNQ-dkXEr89QE5qilg/edit?gid=0#gid=0)
   - 🔗 [Presentasi Akhir - Algorify](https://www.canva.com/design/DAG7MAqpZGk/d4F5x-WkE3Sst1W2misoDQ/edit?utm_content=DAG7MAqpZGk&utm_campaign=designshare&utm_medium=link2&utm_source=sharebutton)

- **Requirements**
   - 🔗 [Business & Functional Requirements](https://docs.google.com/spreadsheets/d/1Ez24vwxtnEtPiwsVFA6v6P5nFrwpMKLjwRihAjbAltA/edit?gid=0#gid=0)
   - 🔗 [History Requirement](https://docs.google.com/spreadsheets/d/1vy-JE1JgS3fEFe2Z8iMITcGi8TLbymJG05GCRFDo2X4/edit?gid=303200458#gid=303200458)

- **Desain & Proses**
   - 🔗 [Desain Figma](https://www.figma.com/design/G2zEPAx4DJAYr2WLbQgQhk/Latihan-Pelayanan-TIK---HighFive--Copy-?node-id=0-1&p=f&t=Fh1lNCEYyQlRB2oT-0)
   - 🔗 [Diagram BPMN](https://modeler.camunda.io/diagrams/a5596897-ad7f-4c34-8004-b10a794010c8--high-five?v=1056,345,1)

- **Testing**
   - 🔗 [Form Usability Testing - Algorify](https://forms.gle/wJSaeqKy4oMsfhbm9)

### Kontribusi

Untuk berkontribusi pada proyek ini:
1. Fork repository
2. Buat branch fitur (contoh: `git checkout -b feature/login-google`)
3. Lakukan perubahan kode seperlunya
4. Jalankan tes (contoh: `php artisan test`)
5. Commit perubahan (contoh: `git commit -m "feat: tambah login Google"`)
6. Push ke branch (contoh: `git push origin feature/login-google`)
7. Buat Pull Request
