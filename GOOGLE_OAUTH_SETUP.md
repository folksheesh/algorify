# Setup Google OAuth untuk Publik/Production

## Langkah-langkah Setup di Google Cloud Console

### 1. Buat Project di Google Cloud Console
1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Klik **Select a project** → **New Project**
3. Beri nama project (contoh: "Algorify")
4. Klik **Create**

### 2. Aktifkan Google+ API
1. Di menu navigasi, pilih **APIs & Services** → **Library**
2. Cari "Google+ API"
3. Klik **Enable**

### 3. Konfigurasi OAuth Consent Screen
1. Pilih **APIs & Services** → **OAuth consent screen**
2. Pilih **External** (untuk publik)
3. Klik **Create**
4. Isi informasi:
   - **App name**: Algorify
   - **User support email**: Email Anda
   - **Developer contact email**: Email Anda
5. Klik **Save and Continue**
6. Di bagian **Scopes**, klik **Add or Remove Scopes**
   - Pilih: `.../auth/userinfo.email`
   - Pilih: `.../auth/userinfo.profile`
7. Klik **Save and Continue**
8. Di bagian **Test users** (optional untuk development)
9. Klik **Save and Continue**

### 4. Buat OAuth 2.0 Credentials
1. Pilih **APIs & Services** → **Credentials**
2. Klik **Create Credentials** → **OAuth client ID**
3. Pilih **Application type**: Web application
4. Beri nama: "Algorify Web Client"
5. **Authorized JavaScript origins**:
   ```
   https://yourdomain.com
   http://localhost:8000 (untuk testing local)
   ```
6. **Authorized redirect URIs**:
   ```
   https://yourdomain.com/auth/google/callback
   http://localhost:8000/auth/google/callback (untuk testing local)
   ```
7. Klik **Create**
8. Copy **Client ID** dan **Client Secret**

### 5. Update File .env di Server Production
```env
GOOGLE_CLIENT_ID=your-client-id-here.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-client-secret-here
GOOGLE_REDIRECT_URL=https://yourdomain.com/auth/google/callback
```

### 6. Clear Cache (Wajib setelah update .env)
```bash
php artisan config:clear
php artisan cache:clear
```

## Testing

### Testing di Local
1. Pastikan di Google Console sudah ditambahkan:
   - Origin: `http://localhost:8000`
   - Redirect: `http://localhost:8000/auth/google/callback`
2. Di file `.env`:
   ```env
   GOOGLE_REDIRECT_URL=http://localhost:8000/auth/google/callback
   ```
3. Jalankan: `php artisan config:clear`

### Testing di Production
1. Pastikan di Google Console sudah ditambahkan:
   - Origin: `https://yourdomain.com`
   - Redirect: `https://yourdomain.com/auth/google/callback`
2. Di file `.env` server production:
   ```env
   GOOGLE_REDIRECT_URL=https://yourdomain.com/auth/google/callback
   ```
3. Jalankan: `php artisan config:clear`

## Troubleshooting

### Error: "redirect_uri_mismatch"
**Penyebab**: URL redirect tidak cocok dengan yang terdaftar di Google Console

**Solusi**:
1. Periksa URL yang tercantum di error message
2. Tambahkan URL tersebut di Google Console → Credentials → Edit OAuth Client
3. Clear cache: `php artisan config:clear`

### Error: "invalid_client"
**Penyebab**: Client ID atau Secret salah

**Solusi**:
1. Verifikasi Client ID dan Secret di Google Console
2. Copy ulang ke file `.env`
3. Clear cache: `php artisan config:clear`

### Error: "access_denied"
**Penyebab**: User membatalkan login atau app belum di-approve

**Solusi**:
- User harus klik "Allow" di halaman consent Google
- Jika app masih "Testing mode", pastikan email user sudah ditambahkan sebagai Test User

### Login berhasil tapi tidak redirect
**Penyebab**: Session atau redirect route bermasalah

**Solusi**:
1. Clear browser cache dan cookies
2. Pastikan `APP_URL` di `.env` sudah benar
3. Clear application cache: `php artisan config:clear`

## Publikasi App (Opsional)

Untuk menghilangkan warning "This app isn't verified":

1. Di Google Console → **OAuth consent screen**
2. Klik **Publish App**
3. Submit untuk verifikasi (proses bisa 4-6 minggu)

**ATAU**

Tetap dalam mode "Testing" dan tambahkan semua user sebagai Test Users (max 100 user)

## Keamanan

✅ **Sudah Diterapkan:**
- Redirect URL dinamis menggunakan `url()` helper
- Error handling dengan try-catch
- Password random untuk user OAuth
- Auto-assign role 'peserta'

⚠️ **Rekomendasi Tambahan:**
- Jangan commit file `.env` ke Git
- Gunakan HTTPS di production
- Rotasi Client Secret secara berkala
- Monitor logs untuk aktivitas mencurigakan
