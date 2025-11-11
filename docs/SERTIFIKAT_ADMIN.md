# Halaman Sertifikat Admin - Algorify

## 📋 Deskripsi
Halaman Sertifikat Admin adalah fitur untuk mengelola tanda tangan direktur yang akan digunakan pada sertifikat pelatihan TIK. Admin dapat mengupload, melihat preview, mengganti, dan menghapus tanda tangan direktur.

## ✨ Fitur Utama

### 1. Upload Tanda Tangan
- **Format yang didukung**: PNG, JPG, JPEG
- **Ukuran maksimal**: 2MB
- **Rekomendasi**: Gunakan PNG dengan background transparan untuk hasil terbaik
- **Resolusi**: Minimal 300 x 150 pixel

### 2. Preview Tanda Tangan
- Menampilkan preview tanda tangan yang sudah diupload
- Informasi detail: "Direktur Pelatihan TIK • Tanda tangan untuk sertifikat sebagai pengesahan dari direktur"
- Status upload yang jelas dengan icon centang hijau

### 3. Ganti Tanda Tangan
- Memungkinkan admin untuk mengganti tanda tangan yang sudah ada
- Proses penggantian otomatis menghapus tanda tangan lama

### 4. Hapus Tanda Tangan
- Fitur untuk menghapus tanda tangan yang sudah diupload
- Konfirmasi sebelum menghapus untuk mencegah kesalahan

### 5. Drag & Drop Upload
- Mendukung upload dengan cara drag & drop file
- UI yang intuitif dan user-friendly
- Visual feedback saat drag over area upload

## 🎨 Desain UI/UX

### Design System
- **Font**: Inter (Google Fonts)
- **Primary Color**: #667eea (Purple)
- **Success Color**: #10B981 (Green)
- **Error Color**: #EF4444 (Red)
- **Warning Color**: #F59E0B (Amber)

### Komponen UI
1. **Page Header**: Judul dan deskripsi halaman
2. **Upload Guidelines**: Panduan upload dengan icon informasi
3. **Signature Section**: Area upload/preview dengan border dashed
4. **Button Group**: Tombol upload dan hapus dengan icon
5. **Privacy Note**: Kebijakan privasi dengan highlight kuning
6. **Alert Messages**: Notifikasi sukses/error
7. **Loading Overlay**: Loading spinner saat upload/delete

## 🔧 Implementasi Teknis

### Backend (Laravel)

#### Controller: `App\Http\Controllers\Admin\SertifikatController.php`

**Methods:**
1. `index()` - Menampilkan halaman sertifikat dengan data signature yang ada
2. `uploadSignature(Request $request)` - Handle upload tanda tangan
3. `deleteSignature()` - Handle hapus tanda tangan

**Validasi Upload:**
```php
$request->validate([
    'signature' => 'required|image|mimes:png,jpg,jpeg|max:2048'
]);
```

**Storage:**
- Lokasi: `storage/app/public/signatures/`
- Nama file: `director_signature.{ext}`
- Format: PNG, JPG, atau JPEG

#### Routes: `routes/web.php`

```php
Route::middleware('role:admin|super admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/sertifikat', [SertifikatController::class, 'index'])->name('sertifikat.index');
    Route::post('/sertifikat/upload-signature', [SertifikatController::class, 'uploadSignature'])->name('sertifikat.upload-signature');
    Route::delete('/sertifikat/delete-signature', [SertifikatController::class, 'deleteSignature'])->name('sertifikat.delete-signature');
});
```

### Frontend (Blade + JavaScript)

#### View: `resources/views/admin/sertifikat/index.blade.php`

**Fitur JavaScript:**
1. File input change handler
2. Drag & drop handlers (dragover, dragleave, drop)
3. Upload function dengan FormData
4. Delete function dengan konfirmasi
5. Update/remove signature preview
6. Alert notification system
7. Loading overlay management

**AJAX Upload:**
```javascript
fetch('{{ route("admin.sertifikat.upload-signature") }}', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': csrfToken
    },
    body: formData
})
```

## 📱 Responsive Design
- Mobile-friendly design
- Adaptive layout untuk semua ukuran layar
- Touch-friendly buttons dan interactive elements

## 🔒 Keamanan

### Validasi
1. **File Type**: Hanya menerima image (PNG, JPG, JPEG)
2. **File Size**: Maksimal 2MB
3. **CSRF Protection**: Menggunakan Laravel CSRF token
4. **Role-based Access**: Hanya admin dan super admin yang dapat akses

### Storage
- File disimpan di `storage/app/public/signatures/`
- Public access melalui symbolic link
- Auto-delete file lama saat upload baru

## 📁 Struktur File

```
algorify/
├── app/
│   └── Http/
│       └── Controllers/
│           └── Admin/
│               └── SertifikatController.php
├── resources/
│   └── views/
│       └── admin/
│           └── sertifikat/
│               └── index.blade.php
├── routes/
│   └── web.php
├── storage/
│   └── app/
│       └── public/
│           └── signatures/
│               └── director_signature.{ext}
└── public/
    └── storage/ (symbolic link)
```

## 🚀 Cara Penggunaan

### Untuk Admin:

1. **Login** sebagai admin atau super admin
2. **Navigasi** ke menu "Sertifikat" di sidebar
3. **Upload tanda tangan** dengan cara:
   - Klik tombol "Pilih File", atau
   - Drag & drop file ke area upload
4. **Preview** tanda tangan yang sudah diupload
5. **Ganti** tanda tangan jika diperlukan
6. **Hapus** tanda tangan dengan tombol "Hapus"

### Notifikasi:
- ✅ **Sukses**: "Tanda tangan berhasil diupload"
- ✅ **Sukses**: "Tanda tangan berhasil dihapus"
- ❌ **Error**: Validasi file gagal
- ❌ **Error**: Upload/delete gagal

## 🎯 Future Improvements

1. **Multiple Signatures**: Support untuk beberapa tanda tangan (direktur, ketua, dll)
2. **Signature History**: Riwayat perubahan tanda tangan
3. **Image Editor**: Crop, resize, dan edit tanda tangan sebelum upload
4. **Preview Certificate**: Preview sertifikat dengan tanda tangan
5. **Batch Processing**: Generate sertifikat untuk banyak peserta sekaligus
6. **Digital Signature**: Integrasi dengan digital signature provider
7. **Audit Log**: Log aktivitas upload/delete tanda tangan

## 🐛 Troubleshooting

### Error: "The [public/storage] link already exists"
**Solusi**: Symbolic link sudah dibuat, tidak perlu action

### Error: "Failed to upload signature"
**Solusi**: 
- Pastikan folder `storage/app/public/signatures/` ada
- Pastikan permissions folder correct (775)
- Cek file size tidak melebihi 2MB
- Cek format file (PNG, JPG, JPEG)

### Image tidak muncul setelah upload
**Solusi**:
- Run: `php artisan storage:link`
- Clear cache: `php artisan cache:clear`
- Cek browser console untuk error

## 📞 Support
Untuk pertanyaan atau issue, silakan hubungi tim development.

---

**Version**: 1.0.0  
**Last Updated**: November 11, 2025  
**Author**: Development Team Algorify
