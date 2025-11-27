# Dokumentasi Sistem Pembayaran Lokal

## Overview
Sistem pembayaran lokal ini adalah simulasi pembayaran untuk development. User dapat memilih metode pembayaran dan memiliki waktu 10 menit untuk menyelesaikan transaksi.

## Flow Pembayaran

### 1. Pilih Metode Pembayaran
- User memilih salah satu dari 3 metode pembayaran:
  - **Transfer Bank** (BCA, BNI, Mandiri, BRI)
  - **E-Wallet** (GoPay, OVO, Dana, ShopeePay)
  - **Kartu Kredit** (Visa, Mastercard, JCB)
- Klik box untuk memilih, klik lagi untuk batal pilih
- Box yang dipilih akan highlight dengan border ungu

### 2. Proses Pembayaran
- Setelah memilih metode, klik tombol **"Proses Pembayaran"**
- Sistem akan:
  - Create transaksi baru dengan status `pending` di database
  - Generate kode transaksi unik (format: `TRX-XXXXXXXXXX`)
  - Menampilkan modal "Menunggu Pembayaran"

### 3. Status Pending (Modal Menunggu Pembayaran)
Modal ini menampilkan:
- **Timer Countdown**: Menghitung mundur dari 10:00 menit
- **Kode Transaksi**: Kode unik untuk tracking
- **Detail Kursus**: Nama kursus yang dibeli
- **Metode Pembayaran**: Yang dipilih user
- **Total Pembayaran**: Harga kursus
- **Tombol "Selesaikan Pembayaran"**: Untuk simulasi pembayaran berhasil

### 4. Selesaikan Pembayaran
Ketika user klik **"Selesaikan Pembayaran"**:
- Sistem mengubah status transaksi dari `pending` ke `success`
- Create enrollment dengan status `active`
- Timer berhenti
- Modal pending ditutup
- Modal invoice muncul

### 5. Invoice (Pembayaran Berhasil)
Modal invoice menampilkan:
- Icon success (✅)
- Kode transaksi
- Tanggal pembayaran
- Detail kursus
- Total yang dibayar
- Tombol **"Lihat Pelatihan Saya"**: Redirect ke halaman pelatihan

## Status Transaksi

### Status yang Tersedia
1. **pending**: Menunggu pembayaran (max 10 menit)
2. **success**: Pembayaran berhasil
3. **expired**: Waktu pembayaran habis
4. **failed**: Pembayaran gagal

### Auto-Expire Transaksi
- Sistem otomatis mengubah status `pending` menjadi `expired` setelah 10 menit
- Dilakukan oleh scheduled command `transactions:expire` yang berjalan setiap 1 menit
- Timer di frontend akan alert user dan reload page saat waktu habis

## Jika Ada Transaksi Pending Sebelumnya
Ketika user kembali ke halaman pembayaran dan masih ada transaksi pending:
- Modal "Menunggu Pembayaran" akan otomatis muncul
- Timer melanjutkan countdown dari sisa waktu
- User bisa langsung klik "Selesaikan Pembayaran"

## Technical Implementation

### Routes
```php
// Create pending transaction
POST /user/kursus/{id}/enroll

// Complete payment (pending -> success)
POST /user/pembayaran/{kode_transaksi}/complete

// Check payment status
GET /user/pembayaran/{kode_transaksi}/status
```

### Controller Methods
- `enroll()`: Create pending transaction
- `completePayment()`: Ubah status jadi success, create enrollment
- `checkPaymentStatus()`: Check apakah transaksi expired atau masih pending

### Database
Table: `transaksis`
- `kode_transaksi`: Kode unik transaksi
- `status`: pending/success/expired/failed
- `metode_pembayaran`: bank_transfer/e_wallet/credit_card
- `updated_at`: Digunakan untuk hitung expired time (10 menit)

### Scheduled Command
Command: `php artisan transactions:expire`
- Berjalan setiap 1 menit (via Laravel Scheduler)
- Mengubah transaksi pending yang >10 menit jadi expired
- Untuk menjalankan scheduler: `php artisan schedule:work`

### Frontend (JavaScript)
Functions:
- `selectPayment()`: Handle pemilihan metode pembayaran
- `processPayment()`: Create pending transaction via AJAX
- `showPendingModal()`: Tampilkan modal pending
- `startCountdown()`: Timer countdown 10 menit
- `completePayment()`: Selesaikan pembayaran via AJAX
- `showInvoiceModal()`: Tampilkan invoice

## Development Mode
Untuk development, jalankan scheduler dengan:
```bash
php artisan schedule:work
```

Atau manual expire transaksi:
```bash
php artisan transactions:expire
```

## Production Setup
Di production, setup cron job:
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## Testing Flow
1. Buka halaman pembayaran kursus
2. Pilih metode pembayaran (misal: E-Wallet)
3. Klik "Proses Pembayaran"
4. Modal pending muncul dengan timer 10:00
5. Klik "Selesaikan Pembayaran"
6. Modal invoice muncul
7. Klik "Lihat Pelatihan Saya"
8. User diarahkan ke halaman pelatihan dengan enrollment active

## Notes
- Ini adalah **simulasi** pembayaran untuk development
- Tidak ada integrasi payment gateway yang real
- DOKU integration code sudah disiapkan tapi di-comment
- Timer berjalan di frontend, tapi validasi sebenarnya di backend
- Expired check dilakukan saat:
  - User klik "Selesaikan Pembayaran" (backend check)
  - Scheduled command berjalan (backend check)
  - Timer habis di frontend (client-side alert)
