# Panduan Import/Export Soal

Fitur ini memungkinkan admin dan pengajar untuk mengimport dan mengexport soal ujian/kuis dalam format Excel (.xlsx).

## Fitur yang Tersedia

### 1. Download Template Excel
- Tombol hijau **"Template"** untuk download template Excel
- Template sudah berisi contoh format dan data sampel
- Format kolom: Pertanyaan | Pilihan A | Pilihan B | Pilihan C | Pilihan D | Kunci Jawaban (A/B/C/D) | Kategori

### 2. Import Soal dari Excel
- Tombol orange **"Import"** untuk upload file Excel
- File maksimal 2MB
- Format yang diterima: .xlsx, .xls
- Soal akan otomatis ditambahkan ke ujian/kuis yang dipilih

### 3. Export Soal ke Excel
- Tombol biru **"Export"** untuk download semua soal dari ujian/kuis
- File akan berisi semua soal yang sudah ada
- Bisa diedit dan diimport kembali

## Cara Menggunakan

### Import Soal

1. **Download Template**
   - Klik tombol **"Template"** di halaman detail ujian
   - File `template_soal.xlsx` akan terdownload
   - Buka file dengan Excel atau Google Sheets

2. **Isi Data Soal**
   - Kolom **Pertanyaan**: Isi dengan pertanyaan soal (wajib)
   - Kolom **Pilihan A-D**: Isi dengan pilihan jawaban (wajib)
   - Kolom **Kunci Jawaban**: Isi dengan huruf A, B, C, atau D (wajib)
   - Kolom **Kategori**: Isi kategori soal (opsional)

   Contoh:
   ```
   Pertanyaan                  | Pilihan A    | Pilihan B     | Pilihan C      | Pilihan D    | Kunci Jawaban | Kategori
   Apa itu algoritma?          | Bahasa       | Urutan        | Aplikasi       | Website      | B             | Dasar
   ```

3. **Upload File**
   - Klik tombol **"Import"** di halaman detail ujian
   - Pilih file Excel yang sudah diisi
   - Klik **"Import Soal"**
   - Tunggu hingga muncul notifikasi "Soal berhasil diimport"
   - Halaman akan reload otomatis dan soal baru akan muncul

### Export Soal

1. Buka halaman detail ujian yang ingin diekspor soalnya
2. Klik tombol **"Export"**
3. File Excel akan terdownload dengan nama `soal_[nama-ujian].xlsx`
4. File berisi semua soal yang sudah ada di ujian tersebut

## Format Excel yang Benar

### Header (Baris 1)
```
Pertanyaan * | Pilihan A * | Pilihan B * | Pilihan C * | Pilihan D * | Kunci Jawaban * (A/B/C/D) | Kategori (Opsional)
```

### Data (Baris 2 dst)
- **Pertanyaan**: Text bebas
- **Pilihan A-D**: Text bebas untuk setiap pilihan jawaban
- **Kunci Jawaban**: HANYA huruf A, B, C, atau D (case-insensitive)
- **Kategori**: Text bebas (bisa dikosongkan)

### Contoh Data Valid

| Pertanyaan | Pilihan A | Pilihan B | Pilihan C | Pilihan D | Kunci Jawaban | Kategori |
|------------|-----------|-----------|-----------|-----------|---------------|----------|
| Apa kepanjangan HTML? | Hyper Text Markup Language | High Tech Modern Language | Home Tool Markup Language | Hyperlink Text | A | Web Development |
| Berapa hasil 2+2? | 3 | 4 | 5 | 6 | B | Matematika |

## Tips dan Catatan

✅ **DO's:**
- Gunakan template yang disediakan
- Pastikan semua kolom wajib (*) terisi
- Kunci jawaban harus A, B, C, atau D (huruf besar atau kecil)
- Save file dalam format .xlsx atau .xls
- Maksimal ukuran file 2MB

❌ **DON'Ts:**
- Jangan ubah nama header di baris pertama
- Jangan hapus baris header
- Jangan isi kunci jawaban dengan angka (0, 1, 2, 3)
- Jangan isi kunci jawaban dengan text selain A-D
- Jangan upload file dengan format selain .xlsx/.xls

## Troubleshooting

### Error: "Gagal mengimport soal"
- Cek format file (harus .xlsx atau .xls)
- Cek ukuran file (maksimal 2MB)
- Pastikan header Excel sesuai dengan template
- Pastikan semua kolom wajib terisi

### Soal tidak muncul setelah import
- Cek apakah kunci jawaban valid (A/B/C/D)
- Cek apakah ada error di console browser (F12)
- Refresh halaman

### Kunci jawaban tidak benar
- Pastikan kolom "Kunci Jawaban" diisi dengan huruf A, B, C, atau D saja
- Jangan ada spasi sebelum/sesudah huruf

## Struktur Database

Import soal akan membuat data di 2 tabel:
1. **soal**: Menyimpan pertanyaan dan kunci jawaban
2. **pilihan_jawaban**: Menyimpan pilihan A, B, C, D untuk setiap soal

## Developer Notes

### Files yang Terlibat

**Controllers:**
- `app/Http/Controllers/Admin/SoalController.php`
  - `downloadTemplate()`: Download template Excel
  - `import()`: Process upload dan import
  - `export()`: Export soal ke Excel

**Exports:**
- `app/Exports/SoalTemplateExport.php`: Template Excel dengan sample data
- `app/Exports/SoalExport.php`: Export soal existing ke Excel

**Imports:**
- `app/Imports/SoalImport.php`: Import soal dari Excel ke database

**Views:**
- `resources/views/admin/pelatihan/ujian-detail.blade.php`: UI tombol dan modal

**Routes:**
```php
Route::get('/soal/template', [SoalController::class, 'downloadTemplate'])->name('admin.soal.template');
Route::post('/soal/import', [SoalController::class, 'import'])->name('admin.soal.import');
Route::get('/soal/export/{ujianId}', [SoalController::class, 'export'])->name('admin.soal.export');
```

### Dependencies
- **maatwebsite/excel**: ^3.1
- **phpoffice/phpspreadsheet**: Dependency dari maatwebsite/excel
