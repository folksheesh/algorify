{{--
========================================
HALAMAN DATA ADMIN - ADMIN PANEL
========================================
Halaman untuk mengelola data admin
Features: CRUD, Search, Filter, Export
========================================
--}}

@extends('layouts.template')

@section('title', 'Data Admin - Admin Panel')

{{-- Load CSS dan Fonts --}}
@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin/admin-index.css') }}">

    {{-- Custom CSS untuk halaman admin --}}
    {{-- <style>
        /* ----- Global Font Setting ----- */
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        /* ========================================
       TABLE CONTAINER & LAYOUT
       ======================================== */

        /* Container utama untuk tabel */
        .table-container {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-top: 1.5rem;
        }

        /* Header tabel - berisi search box, filter, dan tombol tambah */
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            gap: 0.75rem;
        }

        /* ========================================
       SEARCH BOX
       ======================================== */

        /* Container untuk search box dengan icon */
        .search-box {
            position: relative;
            width: 458px;
        }

        /* Input field untuk pencarian */
        .search-box input {
            width: 100%;
            padding: 0.625rem 1rem 0.625rem 2.75rem;
            /* Extra padding kiri untuk icon */
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            font-size: 0.875rem;
            transition: all 0.2s;
            height: 40px;
            box-sizing: border-box;
        }

        /* State saat input difokuskan */
        .search-box input:focus {
            outline: none;
            border-color: #5D3FFF;
            /* Purple border saat focus */
            box-shadow: 0 0 0 3px rgba(93, 63, 255, 0.1);
            /* Glow effect */
        }

        /* Styling untuk placeholder text */
        .search-box input::placeholder {
            color: #94A3B8;
        }

        /* Icon search di dalam input */
        .search-box svg {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            pointer-events: none;
            /* Agar tidak menghalangi input */
        }

        /* ========================================
       FILTER & ACTIONS
       ======================================== */

        /* Container untuk filter dan tombol action */
        .filter-actions {
            display: flex;
            gap: 0.75rem;
            margin-left: auto;
        }

        /* Dropdown untuk filter status (Aktif/Nonaktif) */
        .status-dropdown {
            padding: 0 2.5rem 0 1rem;
            /* Extra padding kanan untuk arrow icon */
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 500;
            color: #334155;
            background: white;
            cursor: pointer;
            appearance: none;
            /* Hilangkan default arrow */
            transition: all 0.2s;
            height: 40px;
            min-width: 180px;
            box-sizing: border-box;
            /* Custom arrow icon menggunakan SVG data URI */
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none'%3E%3Cpath d='M7 10L12 15L17 10' stroke='%2364748B' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.875rem center;
            background-size: 18px;
        }

        /* State hover dropdown */
        .status-dropdown:hover {
            border-color: #94A3B8;
            background-color: #F8FAFC;
        }

        /* State focus dropdown */
        .status-dropdown:focus {
            outline: none;
            border-color: #5D3FFF;
            box-shadow: 0 0 0 3px rgba(93, 63, 255, 0.1);
            background-color: white;
        }

        /* Styling untuk option dalam dropdown */
        .status-dropdown option {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            color: #334155;
            background: white;
        }

        /* State hover option */
        .status-dropdown option:hover {
            background: #F8FAFC;
        }

        /* State saat option dipilih */
        .status-dropdown option:checked {
            background: linear-gradient(135deg, #5D3FFF 0%, #7C3FFF 100%);
            color: white;
            font-weight: 600;
        }

        /* Responsive design untuk mobile */
        @media (max-width: 768px) {

            /* Stack header items secara vertikal */
            .table-header {
                flex-direction: column;
                align-items: stretch;
            }

            /* Search box full width */
            .search-box {
                width: 100%;
            }

            /* Filter actions full width */
            .filter-actions {
                width: 100%;
                margin-left: 0;
            }

            /* Dropdown expand untuk mengisi space */
            .status-dropdown {
                flex: 1;
            }
        }

        /* ========================================
       TOMBOL TAMBAH ADMIN
       ======================================== */

        /* Tombol primary untuk tambah data admin */
        .btn-add {
            padding: 0 1.5rem;
            background: linear-gradient(135deg, #5D3FFF 0%, #7C3FFF 100%);
            /* Purple gradient */
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
            height: 40px;
            box-shadow: 0 2px 8px rgba(93, 63, 255, 0.2);
            white-space: nowrap;
            /* Prevent text wrapping */
        }

        /* Hover effect - lift button */
        .btn-add:hover {
            background: linear-gradient(135deg, #4D2FEF 0%, #6C2FEF 100%);
            /* Darker gradient */
            transform: translateY(-2px);
            /* Lift effect */
            box-shadow: 0 4px 12px rgba(93, 63, 255, 0.3);
            /* Enhanced shadow */
        }

        /* Active/click effect */
        .btn-add:active {
            transform: translateY(0);
            /* Remove lift */
        }

        /* Icon dalam tombol */
        .btn-add svg {
            width: 18px;
            height: 18px;
        }

        /* ========================================
       DATA TABLE
       ======================================== */

        /* Tabel utama untuk menampilkan data admin */
        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        /* Header tabel dengan background purple */
        .data-table thead {
            background: #5D3FFF;
            color: white;
        }

        /* Cell header tabel */
        .data-table thead th {
            padding: 1rem;
            text-align: center;
            font-weight: 600;
            font-size: 0.875rem;
            white-space: nowrap;
            /* Prevent text wrapping */
        }

        /* Rounded corner kiri atas */
        .data-table thead th:first-child {
            border-radius: 8px 0 0 0;
        }

        /* Rounded corner kanan atas */
        .data-table thead th:last-child {
            border-radius: 0 8px 0 0;
        }

        /* Baris data dalam tabel */
        .data-table tbody tr {
            border-bottom: 1px solid #F1F5F9;
            cursor: pointer;
            /* Kursor pointer karena bisa diklik untuk detail */
            transition: all 0.2s;
        }

        /* Hover effect pada baris - scale sedikit dan beri shadow */
        .data-table tbody tr:hover {
            background: #F8FAFC;
            transform: scale(1.01);
            /* Zoom sedikit */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        /* Cell dalam baris data */
        .data-table tbody td {
            padding: 1rem;
            font-size: 0.875rem;
            color: #334155;
            text-align: center;
        }

        /* ========================================
       STATUS BADGE & ACTION BUTTONS
       ======================================== */

        /* Badge untuk menampilkan status (Aktif/Nonaktif) */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Badge status aktif - hijau */
        .status-badge.active {
            background: #D1FAE5;
            /* Light green background */
            color: #059669;
            /* Green text */
        }

        /* Badge status nonaktif - merah */
        .status-badge.inactive {
            background: #FEE2E2;
            /* Light red background */
            color: #DC2626;
            /* Red text */
        }

        /* Container untuk tombol aksi (Edit, Delete) */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        /* Base styling untuk semua tombol aksi */
        .btn-action {
            padding: 0.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            background: transparent;
        }

        /* Hover effect - perbesar icon */
        .btn-action:hover {
            transform: scale(1.1);
        }

        /* Tombol view - biru */
        .btn-view {
            color: #3B82F6;
        }

        /* Tombol edit - kuning/orange */
        .btn-edit {
            color: #F59E0B;
        }

        /* Tombol delete - merah */
        .btn-delete {
            color: #EF4444;
        }

        /* ========================================
       PAGE HEADER
       ======================================== */

        /* Header halaman dengan judul */
        .page-header {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Judul halaman */
        .page-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1E293B;
            margin: 0;
        }

        /* ========================================
       MODAL SYSTEM
       ======================================== */

        /* Overlay gelap di belakang modal */
        .modal-overlay {
            display: none;
            /* Hidden by default */
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.65);
            /* Dark semi-transparent */
            z-index: 9999;
            /* Di atas semua element */
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        /* Show modal saat diberi class active */
        .modal-overlay.active {
            display: flex;
        }

        /* Container konten modal */
        .modal-content {
            background: white;
            border-radius: 16px;
            padding: 2rem 2.5rem;
            max-width: 560px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            /* Scroll jika konten terlalu panjang */
            position: relative;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }

        /* Header dalam modal (judul dan deskripsi) */
        .modal-header {
            margin-bottom: 1.5rem;
            padding-right: 2.5rem;
            /* Space untuk tombol close */
        }

        /* Judul modal */
        .modal-header h2 {
            font-size: 1.375rem;
            font-weight: 700;
            color: #0F172A;
            margin: 0 0 0.5rem 0;
            letter-spacing: -0.02em;
        }

        /* Deskripsi/subtitle modal */
        .modal-header p {
            color: #64748B;
            font-size: 0.8125rem;
            margin: 0;
            line-height: 1.5;
            font-weight: 400;
        }

        /* Tombol close (X) di pojok kanan atas */
        .modal-close {
            position: absolute;
            top: 1.75rem;
            right: 2rem;
            background: transparent;
            border: none;
            color: #94A3B8;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            transition: color 0.2s;
        }

        /* Hover effect tombol close */
        .modal-close:hover {
            color: #64748B;
        }

        /* ========================================
       FORM ELEMENTS
       ======================================== */

        /* Group untuk setiap field form (label + input) */
        .form-group {
            margin-bottom: 0.875rem;
        }

        /* Label untuk form input */
        .form-label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 500;
            color: #0F172A;
            margin-bottom: 0.375rem;
            letter-spacing: -0.01em;
        }

        /* Base styling untuk semua input, textarea, dan select */
        .form-input {
            width: 100%;
            padding: 0.75rem 0.875rem;
            border: 1px solid #E2E8F0;
            border-radius: 6px;
            font-size: 0.8125rem;
            background: white;
            color: #475569;
            font-weight: 400;
            transition: all 0.2s;
            box-sizing: border-box;
            resize: vertical;
            /* Allow vertical resize untuk textarea */
        }

        /* State saat input difokuskan */
        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            /* Focus ring */
        }

        /* State untuk readonly input (seperti di modal detail) */
        .form-input:read-only {
            background: #F1F5F9;
            /* Gray background */
            cursor: default;
            /* Cursor default, bukan text cursor */
        }

        /* Styling untuk placeholder text */
        .form-input::placeholder {
            color: #94A3B8;
        }

        /* Ensure textarea menggunakan font yang sama */
        textarea.form-input {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.5;
        }

        /* ----- Dropdown/Select Styling ----- */

        /* Custom styling untuk select dropdown dalam modal */
        .modal-content select.form-input {
            appearance: none;
            /* Hilangkan default arrow */
            /* Custom arrow icon menggunakan SVG */
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none'%3E%3Cpath d='M7 10L12 15L17 10' stroke='%2364748B' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px;
            padding-right: 2.5rem;
            /* Space untuk arrow icon */
            cursor: pointer;
        }

        /* Hover state untuk select */
        .modal-content select.form-input:hover {
            border-color: #94A3B8;
            background-color: #F8FAFC;
        }

        /* Focus state untuk select */
        .modal-content select.form-input:focus {
            background-color: white;
        }

        /* ========================================
       FILE UPLOAD AREA
       ======================================== */

        /* Area drag & drop untuk upload file */
        .upload-area {
            border: 2px dashed #CBD5E1;
            /* Dashed border */
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            background: #F8FAFC;
            transition: all 0.3s;
            cursor: pointer;
            position: relative;
        }

        /* Hover effect pada upload area */
        .upload-area:hover {
            border-color: #5D3FFF;
            /* Purple border */
            background: #F0EDFF;
            /* Light purple background */
        }

        /* State saat file di-drag over area ini */
        .upload-area.dragover {
            border-color: #5D3FFF;
            background: #E8E3FF;
            /* Darker purple background */
            transform: scale(1.02);
            /* Zoom sedikit */
        }

        /* Icon upload (cloud icon) */
        .upload-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 0.75rem;
            color: #5D3FFF;
        }

        /* Text "Klik atau drag & drop file" */
        .upload-text {
            color: #334155;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        /* Hint text "PDF, JPG, PNG (Max: 2MB)" */
        .upload-hint {
            color: #64748B;
            font-size: 0.75rem;
        }

        /* ----- File Preview ----- */

        /* Preview file yang sudah dipilih (nama file + ukuran + tombol remove) */
        .file-preview {
            display: none;
            /* Hidden by default */
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem;
            background: white;
            border: 1px solid #E2E8F0;
            border-radius: 6px;
            margin-top: 0.75rem;
        }

        /* Show preview saat file dipilih */
        .file-preview.active {
            display: flex;
        }

        /* Icon file (document icon) */
        .file-icon {
            width: 36px;
            height: 36px;
            color: #5D3FFF;
            flex-shrink: 0;
            /* Prevent icon dari shrink */
        }

        /* Container untuk nama file dan ukuran */
        .file-info {
            flex: 1;
            /* Ambil sisa space */
        }

        /* Nama file */
        .file-name {
            font-size: 0.8125rem;
            font-weight: 500;
            color: #334155;
            margin-bottom: 0.125rem;
        }

        /* Ukuran file */
        .file-size {
            font-size: 0.75rem;
            color: #64748B;
        }

        /* Tombol remove file (X button) */
        .file-remove {
            padding: 0.375rem;
            background: transparent;
            border: none;
            color: #EF4444;
            /* Red color */
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.2s;
        }

        /* Hover effect - background merah muda */
        .file-remove:hover {
            background: #FEE2E2;
        }

        /* ========================================
       PASSWORD FIELD WITH TOGGLE
       ======================================== */

        /* Wrapper untuk input password + tombol show/hide */
        .password-wrapper {
            position: relative;
        }

        /* Tombol toggle show/hide password (eye icon) */
        .password-toggle {
            position: absolute;
            right: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #94A3B8;
            cursor: pointer;
            padding: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        /* Hover effect - warna purple */
        .password-toggle:hover {
            color: #5D3FFF;
        }

        /* Icon dalam toggle button */
        .password-toggle svg {
            width: 18px;
            height: 18px;
        }

        /* ========================================
       SUCCESS MODAL
       ======================================== */

        /* Modal khusus untuk menampilkan pesan sukses */
        .success-modal {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            max-width: 400px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }

        /* Icon checkmark dalam lingkaran hijau */
        .success-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1rem;
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            /* Green gradient */
            border-radius: 50%;
            /* Circle shape */
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        /* Checkmark icon */
        .success-icon svg {
            width: 36px;
            height: 36px;
        }

        /* Judul success modal "Berhasil!" */
        .success-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 0.5rem;
        }

        /* Pesan sukses */
        .success-message {
            font-size: 0.875rem;
            color: #64748B;
            margin-bottom: 1.5rem;
        }

        /* ========================================
       FORM LAYOUT & BUTTONS
       ======================================== */

        /* Layout 2 kolom untuk form fields (misal: Email dan No. Telepon) */
        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            /* 2 kolom sama besar */
            gap: 0.75rem;
            margin-bottom: 0.875rem;
        }

        /* Container untuk tombol aksi di modal (Batal, Simpan, dll) */
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            /* Align ke kanan */
            gap: 0.625rem;
            margin-top: 1.25rem;
            padding-top: 0;
        }

        /* ----- Button Styles ----- */

        /* Base styling untuk semua tombol */
        .btn {
            padding: 0.625rem 1.75rem;
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            letter-spacing: -0.01em;
        }

        /* Tombol Cancel/Batal - putih dengan border */
        .btn-cancel {
            background: white;
            color: #64748B;
            border: 1px solid #CBD5E1;
        }

        /* Hover state tombol cancel */
        .btn-cancel:hover {
            background: #F8FAFC;
            border-color: #94A3B8;
            color: #475569;
        }

        /* Tombol Submit/Simpan - purple gradient */
        .btn-submit {
            background: linear-gradient(135deg, #5D3FFF 0%, #7C3FFF 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(93, 63, 255, 0.2);
        }

        /* Hover state tombol submit */
        .btn-submit:hover {
            background: linear-gradient(135deg, #4D2FEF 0%, #6C2FEF 100%);
            box-shadow: 0 4px 12px rgba(93, 63, 255, 0.3);
        }

        /* Tombol Danger/Delete - merah */
        .btn-danger {
            background: #EF4444;
            color: white;
        }

        /* Hover state tombol danger */
        .btn-danger:hover {
            background: #DC2626;
            /* Darker red */
        }

        /* ========================================
       ERROR & NOTIFICATION MESSAGES
       ======================================== */

        /* ----- Error Message ----- */

        /* Pesan error yang muncul di bawah field form */
        .error-message {
            display: none;
            /* Hidden by default */
            margin-top: 0.375rem;
            padding: 0.5rem 0.75rem;
            background: #FEE2E2;
            /* Light red background */
            border: 1px solid #FECACA;
            /* Red border */
            border-radius: 6px;
            color: #DC2626;
            /* Red text */
            font-size: 0.75rem;
            font-weight: 500;
            animation: slideDown 0.3s ease;
            /* Slide down animation */
        }

        /* Show error saat diberi class active */
        .error-message.active {
            display: block;
        }

        /* Animation untuk error message */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
                /* Start dari atas */
            }

            to {
                opacity: 1;
                transform: translateY(0);
                /* End di posisi normal */
            }
        }

        /* ----- Toast Notification ----- */

        /* Toast notification di pojok kanan atas */
        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            z-index: 10000;
            /* Di atas semua element */
            display: none;
            /* Hidden by default */
            align-items: center;
            gap: 0.75rem;
            min-width: 300px;
            animation: slideInRight 0.3s ease;
            /* Slide in from right */
        }

        /* Show toast saat diberi class active */
        .toast-notification.active {
            display: flex;
        }

        /* Toast dengan tipe error - border kiri merah */
        .toast-notification.error {
            border-left: 4px solid #EF4444;
        }

        /* Toast dengan tipe warning - border kiri kuning */
        .toast-notification.warning {
            border-left: 4px solid #F59E0B;
        }

        /* Icon dalam toast */
        .toast-icon {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
        }

        /* Container untuk title dan message toast */
        .toast-content {
            flex: 1;
        }

        /* Title toast (misal: "Error", "Berhasil") */
        .toast-title {
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        /* Message toast */
        .toast-message {
            font-size: 0.8125rem;
            color: #64748B;
        }

        /* Animation slide in dari kanan */
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
                /* Start dari kanan */
            }

            to {
                opacity: 1;
                transform: translateX(0);
                /* End di posisi normal */
            }
        }
        /* ========================================
           PAGINATION STYLES
           ======================================== */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #E2E8F0;
        }

        .pagination-info {
            font-size: 0.875rem;
            color: #64748B;
        }

        .pagination-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .pagination-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 0.75rem;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            background: white;
            color: #334155;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .pagination-btn:hover:not(.disabled):not(.active) {
            border-color: #5D3FFF;
            color: #5D3FFF;
            background: rgba(93, 63, 255, 0.05);
        }

        .pagination-btn.active {
            background: #5D3FFF;
            border-color: #5D3FFF;
            color: white;
        }

        .pagination-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .pagination-ellipsis {
            padding: 0 0.5rem;
            color: #94A3B8;
        }
    </style> --}}
@endpush

@section('content')
    <div class="dashboard-container">
        {{-- Sidebar Navigation --}}
        @include('components.sidebar')

        {{-- Main Content Area --}}
        <main class="main-content">
            <div style="padding: 0 2rem 2rem;">
                {{-- Page Header --}}
                <div class="page-header">
                    <h1>Halaman Data Admin</h1>
                </div>

                {{-- Table Container dengan Search & Filter --}}
                <div class="table-container">
                    {{-- Header: Search Box, Filter Status, dan Tombol Tambah --}}
                    <div class="table-header">
                        {{-- Search Box --}}
                        <div class="search-box">
                            {{-- Icon Search --}}
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="9" cy="9" r="6" stroke="currentColor" stroke-width="1.5" fill="none" />
                                <path d="M13 13L17 17" stroke="currentColor" stroke-width="1.5" />
                            </svg>
                            {{-- Input pencarian (nama, email) --}}
                            <input type="text" id="searchInput" placeholder="Cari nama atau email.....">
                        </div>

                        {{-- Filter Actions: Status & Tombol Tambah --}}
                        <div class="filter-actions">
                            {{-- Dropdown Filter Status --}}
                            <select id="statusFilter" class="status-dropdown">
                                <option value="">Semua Status</option>
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
                            </select>

                            {{-- Tombol Tambah Admin Baru --}}
                            <button class="btn-add" onclick="openAddModal()">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Tambah Admin
                            </button>
                        </div>
                    </div>

                    {{-- Tabel Data Admin --}}
                    <div style="overflow-x: auto;">
                        <table class="data-table">
                            {{-- Header Tabel --}}
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Admin</th>
                                    <th>Email</th>
                                    <th>No Telepon</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            {{-- Body tabel - akan diisi via JavaScript --}}
                            <tbody id="adminTableBody">
                                <!-- Data akan dimuat via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="pagination-container" id="paginationContainer">
                        <div class="pagination-info">
                            Menampilkan <span id="showingStart">0</span> - <span id="showingEnd">0</span> dari <span id="totalData">0</span> data
                        </div>
                        <div class="pagination-buttons" id="paginationButtons">
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- ========================================
    MODAL: DETAIL ADMIN (READ-ONLY)
    ======================================== --}}
    <div id="detailModal" class="modal-overlay">
        <div class="modal-content">
            {{-- Tombol Close Modal --}}
            <button class="modal-close" onclick="closeModal('detailModal')" aria-label="Close">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.5 4.5L13.5 13.5M4.5 13.5L13.5 4.5" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" />
                </svg>
            </button>

            {{-- Header Modal --}}
            <div class="modal-header">
                <h2>Detail Data Admin</h2>
                <p>Informasi lengkap mengenai admin</p>
            </div>

            {{-- Form Detail (Read-Only) --}}
            <form id="detailForm">
                {{-- Nama Lengkap --}}
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-input" id="detailName" readonly>
                </div>

                {{-- Email dan No. Telepon (2 kolom) --}}
                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-input" id="detailEmail" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" class="form-input" id="detailPhone" readonly>
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="form-group">
                    <label class="form-label">Alamat</label>
                    <input type="text" class="form-input" id="detailAddress" readonly>
                </div>

                {{-- Tanggal Lahir dan Jenis Kelamin (2 kolom) --}}
                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-input" id="detailTanggalLahir" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin</label>
                        <input type="text" class="form-input" id="detailJenisKelamin" readonly>
                    </div>
                </div>

                {{-- Status dan Tanggal Bergabung (2 kolom) --}}
                <div class="form-row-2">
                    <div>
                        <label class="form-label">Status</label>
                        <input type="text" class="form-input" id="detailStatus" readonly>
                    </div>
                    <div>
                        <label class="form-label">Tanggal Bergabung</label>
                        <input type="date" class="form-input" id="detailDate" readonly>
                    </div>
                </div>

                {{-- Action Button --}}
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeModal('detailModal')">Tutup</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL ADD/EDIT ADMIN - Form untuk menambah atau mengedit data admin --}}
    <div id="formModal" class="modal-overlay">
        <div class="modal-content">
            {{-- Tombol Close --}}
            <button class="modal-close" onclick="closeModal('formModal')" aria-label="Close">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.5 4.5L13.5 13.5M4.5 13.5L13.5 4.5" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" />
                </svg>
            </button>

            {{-- Header Modal (title berubah sesuai mode add/edit) --}}
            <div class="modal-header">
                <h2 id="formModalTitle">Tambah Admin Baru</h2>
                <p id="formModalDesc">Lengkapi form di bawah untuk menambah admin</p>
            </div>

            {{-- Form Input Data Admin --}}
            <form id="adminForm" enctype="multipart/form-data">
                <input type="hidden" id="adminId">

                {{-- Nama Lengkap (Required) --}}
                <div class="form-group">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" class="form-input" id="formName" required placeholder="Masukkan nama lengkap">
                    <div class="error-message" id="nameError"></div>
                </div>

                {{-- Email (Required) --}}
                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" class="form-input" id="formEmail" required placeholder="contoh@email.com">
                    <div class="error-message" id="emailError"></div>
                </div>

                {{-- No Telepon dan Tanggal Lahir (2 kolom) --}}
                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" class="form-input" id="formPhone" placeholder="08xxxxxxxxxx">
                        <div class="error-message" id="phoneError"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-input" id="formTanggalLahir">
                    </div>
                </div>

                {{-- Password dengan Toggle Show/Hide (Required saat Add, Optional saat Edit) --}}
                <div class="form-group" id="passwordGroup">
                    <label class="form-label" id="passwordLabel">Password *</label>
                    <div class="password-wrapper">
                        <input type="password" class="form-input" id="formPassword" placeholder="Minimal 8 karakter"
                            style="padding-right: 2.5rem;">
                        <button type="button" class="password-toggle" onclick="togglePassword('formPassword', this)">
                            <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="eye-closed" style="display: none;" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    <small style="color: #64748B; font-size: 0.75rem; display: none;" id="passwordHint">Kosongkan jika tidak
                        ingin mengubah password</small>
                    <div class="error-message" id="passwordError"></div>
                </div>

                {{-- Konfirmasi Password dengan Toggle Show/Hide (Required saat Add, Optional saat Edit) --}}
                <div class="form-group" id="confirmPasswordGroup">
                    <label class="form-label" id="confirmPasswordLabel">Konfirmasi Password *</label>
                    <div class="password-wrapper">
                        <input type="password" class="form-input" id="formPasswordConfirm" placeholder="Ulangi password"
                            style="padding-right: 2.5rem;">
                        <button type="button" class="password-toggle" onclick="togglePassword('formPasswordConfirm', this)">
                            <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="eye-closed" style="display: none;" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Alamat (Textarea) --}}
                <div class="form-group">
                    <label class="form-label">Alamat</label>
                    <textarea class="form-input" id="formAddress" rows="2" placeholder="Masukkan alamat lengkap"></textarea>
                </div>

                {{-- Jenis Kelamin dan Status (2 kolom) --}}
                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin</label>
                        <select class="form-input" id="formJenisKelamin">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select class="form-input" id="formStatus">
                            <option value="active">Aktif</option>
                            <option value="inactive">Nonaktif</option>
                            <option value="suspended">Ditangguhkan</option>
                        </select>
                    </div>
                </div>

                {{-- Action Buttons - Batal dan Simpan --}}
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeModal('formModal')">Batal</button>
                    <button type="submit" class="btn btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL KONFIRMASI HAPUS - Popup konfirmasi sebelum menghapus admin --}}
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 400px;">
            {{-- Tombol Close --}}
            <button class="modal-close" onclick="closeModal('deleteModal')" aria-label="Close">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.5 4.5L13.5 13.5M4.5 13.5L13.5 4.5" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" />
                </svg>
            </button>

            {{-- Header Modal Konfirmasi --}}
            <div class="modal-header">
                <h2>Konfirmasi Hapus</h2>
                <p>Apakah Anda yakin ingin menghapus admin ini?</p>
            </div>

            {{-- Action Buttons - Batal dan Hapus --}}
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('deleteModal')">Batal</button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">Ya, Hapus</button>
            </div>
        </div>
    </div>

    {{-- MODAL SUCCESS - Popup success setelah aksi berhasil dilakukan --}}
    <div id="successModal" class="modal-overlay">
        <div class="success-modal">
            {{-- Icon Success (checkmark) --}}
            <div class="success-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            {{-- Title dan Message (akan diupdate via JS) --}}
            <h3 class="success-title" id="successTitle">Berhasil!</h3>
            <p class="success-message" id="successMessage">Data admin berhasil ditambahkan</p>
            <button class="btn btn-submit" onclick="closeModal('successModal')" style="width: 100%;">OK</button>
        </div>
    </div>

    {{-- TOAST NOTIFICATION - Notifikasi error/warning di pojok kanan atas --}}
    <div id="toastNotification" class="toast-notification">
        <svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="toast-content">
            <div class="toast-title" id="toastTitle">Error</div>
            <div class="toast-message" id="toastMessage">Terjadi kesalahan</div>
        </div>
    </div>

{{-- SCRIPTS SECTION - Load JavaScript untuk halaman ini --}}
@push('scripts')
    <script>
        {{-- Set tema aplikasi --}}
        document.documentElement.setAttribute('data-bs-theme', 'light');

        {{-- CONFIG - CSRF Token dan API Routes untuk JavaScript --}}
        const csrfToken = '{{ csrf_token() }}';
        const apiRoutes = {
            getData: '{{ route("admin.admin.data") }}',
            store: '{{ route("admin.admin.store") }}'
        };

        /* ========================================
       ADMIN MANAGEMENT JAVASCRIPT
       Script untuk halaman Data Admin
       ======================================== */

        // ========================================
        // GLOBAL VARIABLES
        // ========================================

        let adminData = [];  // Array untuk menyimpan semua data admin
        let filteredData = [];  // Array untuk data yang sudah difilter
        let deleteId = null;    // ID admin yang akan dihapus
        let currentPage = 1;
        const itemsPerPage = 10;

        // ========================================
        // NOTIFICATION FUNCTIONS
        // ========================================

        /**
         * Menampilkan toast notification di pojok kanan atas
         * @param {string} title - Judul notifikasi
         * @param {string} message - Pesan notifikasi
         * @param {string} type - Tipe notifikasi ('error' atau 'warning')
         */
        function showToast(title, message, type = 'error') {
            const toast = document.getElementById('toastNotification');
            const toastTitle = document.getElementById('toastTitle');
            const toastMessage = document.getElementById('toastMessage');

            toast.className = 'toast-notification ' + type;
            toastTitle.textContent = title;
            toastMessage.textContent = message;
            toast.classList.add('active');

            // Auto hide setelah 4 detik
            setTimeout(() => {
                toast.classList.remove('active');
            }, 4000);
        }

        /**
         * Menampilkan error message di bawah field form
         * @param {string} fieldId - ID element error message
         * @param {string} message - Pesan error
         * @param {string} inputFieldId - ID field input yang bermasalah (untuk scroll dan focus)
         */
        function showFieldError(fieldId, message, inputFieldId = null) {
            const errorElement = document.getElementById(fieldId);
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.classList.add('active');

                // Scroll ke field yang bermasalah
                if (inputFieldId) {
                    const inputField = document.getElementById(inputFieldId);
                    if (inputField) {
                        inputField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        inputField.focus();

                        // Highlight field dengan border merah
                        inputField.style.borderColor = '#DC2626';
                        setTimeout(() => {
                            inputField.style.borderColor = '';
                        }, 3000);
                    }
                }

                // Error TIDAK auto hide - harus di-clear manual atau saat submit ulang
            }
        }

        /**
         * Clear semua error message yang aktif
         */
        function clearAllErrors() {
            document.querySelectorAll('.error-message').forEach(el => {
                el.classList.remove('active');
                el.textContent = '';
            });

            // Reset border color semua input
            document.querySelectorAll('.form-input').forEach(input => {
                input.style.borderColor = '';
            });
        }

        /**
         * Clear error untuk field tertentu saat user mulai mengetik
         * @param {string} inputId - ID input field
         * @param {string} errorId - ID error message element
         */
        function clearFieldError(inputId, errorId) {
            const input = document.getElementById(inputId);
            const error = document.getElementById(errorId);

            if (input && error) {
                input.addEventListener('input', function () {
                    error.classList.remove('active');
                    error.textContent = '';
                    input.style.borderColor = '';
                });
            }
        }

        // ========================================
        // DATA LOADING & RENDERING
        // ========================================

        /**
         * Load data admin dari server saat halaman dimuat
         */
        document.addEventListener('DOMContentLoaded', function () {
            loadAdminData();

            // Setup auto-clear error saat user mulai mengetik
            clearFieldError('formName', 'nameError');
            clearFieldError('formEmail', 'emailError');
            clearFieldError('formPhone', 'phoneError');
            clearFieldError('formPassword', 'passwordError');
            clearFieldError('formPasswordConfirm', 'passwordError');
        });

        /**
         * Fetch data admin dari API
         */
        function loadAdminData() {
            fetch(apiRoutes.getData)
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    // Urutkan data berdasarkan ID dari kecil ke besar
                    adminData = data.sort((a, b) => a.id - b.id);
                    filteredData = adminData;
                    currentPage = 1;
                    renderTable();
                    renderPagination();
                })
                .catch(error => {
                    showToast('Gagal Memuat Data', 'Tidak dapat memuat data admin. Silakan refresh halaman.', 'error');
                });
        }

        /**
         * Render tabel dengan data admin (dengan pagination)
         */
        function renderTable() {
            const tbody = document.getElementById('adminTableBody');
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const paginatedData = filteredData.slice(startIndex, endIndex);

            // Jika tidak ada data
            if (filteredData.length === 0) {
                tbody.innerHTML = `
                <tr>
                    <td colspan="6" style="text-align: center; padding: 2rem; color: #94A3B8;">
                        Tidak ada data admin
                    </td>
                </tr>
            `;
                updatePaginationInfo(0, 0, 0);
                return;
            }

            // Render setiap baris data
            tbody.innerHTML = paginatedData.map((item, index) => {
                const status = item.status || 'active';
                const statusDisplay = status === 'active' ? 'Aktif' : 'Nonaktif';
                const phone = item.phone || '-';

                return `
            <tr onclick="showDetail('${item.id}')">
                <td>${startIndex + index + 1}</td>
                <td>${item.name}</td>
                <td>${item.email}</td>
                <td>${phone}</td>
                <td>
                    <span class="status-badge ${status}">${statusDisplay}</span>
                </td>
                <td onclick="event.stopPropagation()">
                    <div class="action-buttons">
                        <button class="btn-action btn-edit" onclick="openEditModal('${item.id}')" title="Edit">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                            </svg>
                        </button>
                        <button class="btn-action btn-delete" onclick="openDeleteModal('${item.id}')" title="Hapus">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `;
            }).join('');
            
            updatePaginationInfo(startIndex + 1, Math.min(endIndex, filteredData.length), filteredData.length);
        }

        // Update info pagination
        function updatePaginationInfo(start, end, total) {
            document.getElementById('showingStart').textContent = total > 0 ? start : 0;
            document.getElementById('showingEnd').textContent = end;
            document.getElementById('totalData').textContent = total;
        }

        // Render pagination buttons
        function renderPagination() {
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);
            const container = document.getElementById('paginationButtons');
            
            if (totalPages <= 1) {
                container.innerHTML = '';
                return;
            }
            
            let html = '';
            
            // Previous button
            html += `<button class="pagination-btn ${currentPage === 1 ? 'disabled' : ''}" 
                            onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </button>`;
            
            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                    html += `<button class="pagination-btn ${i === currentPage ? 'active' : ''}" 
                                    onclick="goToPage(${i})">${i}</button>`;
                } else if (i === currentPage - 2 || i === currentPage + 2) {
                    html += `<span class="pagination-ellipsis">...</span>`;
                }
            }
            
            // Next button
            html += `<button class="pagination-btn ${currentPage === totalPages ? 'disabled' : ''}" 
                            onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </button>`;
            
            container.innerHTML = html;
        }

        // Go to specific page
        function goToPage(page) {
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            renderTable();
            renderPagination();
        }

        /**
         * Format tanggal ke format Indonesia
         * @param {string} dateString - String tanggal ISO
         * @returns {string} Tanggal terformat (contoh: 15 Jan 2024)
         */
        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
        }

        // ========================================
        // SEARCH & FILTER FUNCTIONS
        // ========================================

        /**
         * Event listener untuk search input
         */
        document.getElementById('searchInput').addEventListener('input', function (e) {
            filterData();
        });

        /**
         * Event listener untuk status filter dropdown
         */
        document.getElementById('statusFilter').addEventListener('change', function (e) {
            filterData();
        });

        /**
         * Filter data berdasarkan search term dan status
         */
        function filterData() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;

            const filtered = adminData.filter(item => {
                // Search di nama dan email
                const nameMatch = item.name.toLowerCase().includes(searchTerm);
                const emailMatch = item.email.toLowerCase().includes(searchTerm);

                const matchSearch = nameMatch || emailMatch;
                const matchStatus = !statusFilter || (item.status || 'active') === statusFilter;
                return matchSearch && matchStatus;
            });

            // Urutkan hasil filter berdasarkan ID dari kecil ke besar
            filteredData = filtered.sort((a, b) => a.id - b.id);
            
            // Reset ke halaman 1 saat filter
            currentPage = 1;

            renderTable();
            renderPagination();
        }

        // ========================================
        // MODAL FUNCTIONS - DETAIL
        // ========================================

        /**
         * Menampilkan modal detail admin (readonly)
         * @param {string} id - ID admin yang akan ditampilkan
         */
        function showDetail(id) {
            const admin = adminData.find(p => String(p.id) === String(id));
            if (!admin) return;

            // Populate form fields
            document.getElementById('detailName').value = admin.name;
            document.getElementById('detailEmail').value = admin.email;
            document.getElementById('detailPhone').value = admin.phone || '-';
            document.getElementById('detailAddress').value = admin.address || '-';
            document.getElementById('detailTanggalLahir').value = admin.tanggal_lahir || '';
            document.getElementById('detailJenisKelamin').value = admin.jenis_kelamin === 'L' ? 'Laki-laki' : admin.jenis_kelamin === 'P' ? 'Perempuan' : '-';

            const statusDisplay = (admin.status || 'active') === 'active' ? 'Aktif' : (admin.status === 'inactive' ? 'Nonaktif' : 'Ditangguhkan');
            document.getElementById('detailStatus').value = statusDisplay;
            document.getElementById('detailDate').value = admin.created_at ? admin.created_at.split('T')[0] : '';

            // Show modal
            document.getElementById('detailModal').classList.add('active');
        }

        // ========================================
        // MODAL FUNCTIONS - ADD
        // ========================================

        /**
         * Membuka modal untuk tambah admin baru
         */
        function openAddModal() {
            // Set judul modal
            document.getElementById('formModalTitle').textContent = 'Tambah Admin Baru';
            document.getElementById('formModalDesc').textContent = 'Lengkapi form di bawah untuk menambah admin';

            // Reset form
            document.getElementById('adminForm').reset();
            document.getElementById('adminId').value = '';

            // Password required untuk tambah data
            document.getElementById('formPassword').required = true;
            document.getElementById('formPasswordConfirm').required = true;
            document.getElementById('passwordLabel').innerHTML = 'Password *';
            document.getElementById('confirmPasswordLabel').innerHTML = 'Konfirmasi Password *';
            document.getElementById('passwordHint').style.display = 'none';

            // Show password fields
            document.getElementById('passwordGroup').style.display = 'block';
            document.getElementById('confirmPasswordGroup').style.display = 'block';

            // Clear all errors
            clearAllErrors();

            // Show modal
            document.getElementById('formModal').classList.add('active');
        }

        // ========================================
        // MODAL FUNCTIONS - EDIT
        // ========================================

        /**
         * Membuka modal untuk edit data admin
         * @param {string} id - ID admin yang akan diedit
         */
        function openEditModal(id) {
            const admin = adminData.find(p => String(p.id) === String(id));
            if (!admin) return;

            // Set judul modal
            document.getElementById('formModalTitle').textContent = 'Edit Data Admin';
            document.getElementById('formModalDesc').textContent = 'Perbarui informasi admin di bawah ini';

            // Populate form dengan data yang ada
            document.getElementById('adminId').value = admin.id;
            document.getElementById('formName').value = admin.name;
            document.getElementById('formEmail').value = admin.email;
            document.getElementById('formPhone').value = admin.phone || '';
            document.getElementById('formAddress').value = admin.address || '';
            document.getElementById('formTanggalLahir').value = admin.tanggal_lahir || '';
            document.getElementById('formJenisKelamin').value = admin.jenis_kelamin || '';
            document.getElementById('formStatus').value = admin.status || 'active';
            document.getElementById('formPassword').value = '';
            document.getElementById('formPasswordConfirm').value = '';

            // Password optional untuk edit
            document.getElementById('formPassword').required = false;
            document.getElementById('formPasswordConfirm').required = false;

            // Sembunyikan field password saat edit
            document.getElementById('passwordGroup').style.display = 'none';
            document.getElementById('confirmPasswordGroup').style.display = 'none';

            // Show modal
            document.getElementById('formModal').classList.add('active');
        }

        // ========================================
        // FORM SUBMISSION
        // ========================================

        /**
         * Handle form submit untuk tambah/edit admin
         */
        document.getElementById('adminForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Clear all previous errors
            clearAllErrors();

            const id = document.getElementById('adminId').value;
            const password = document.getElementById('formPassword').value;
            const passwordConfirm = document.getElementById('formPasswordConfirm').value;

            // Validasi password match saat tambah atau saat password diisi saat edit
            if (!id || password) {
                if (password !== passwordConfirm) {
                    showFieldError('passwordError', 'Password dan konfirmasi password tidak cocok!', 'formPassword');
                    return;
                }

                // Validasi panjang password minimal 8 karakter
                if (password.length < 8) {
                    showFieldError('passwordError', 'Password minimal 8 karakter!', 'formPassword');
                    return;
                }
            }

            // Prepare form data
            const formData = new FormData();
            formData.append('name', document.getElementById('formName').value);
            formData.append('email', document.getElementById('formEmail').value);
            formData.append('phone', document.getElementById('formPhone').value);
            formData.append('address', document.getElementById('formAddress').value);
            formData.append('tanggal_lahir', document.getElementById('formTanggalLahir').value);
            formData.append('jenis_kelamin', document.getElementById('formJenisKelamin').value);
            formData.append('status', document.getElementById('formStatus').value);

            if (password) {
                formData.append('password', password);
            }

            formData.append('_token', csrfToken);
            if (id) {
                formData.append('_method', 'PUT');
            }

            // Tentukan URL berdasarkan mode (create/update)
            const url = id ? `/admin/admin/${id}` : apiRoutes.store;

            // Submit data
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeModal('formModal');

                        // Show success modal
                        document.getElementById('successTitle').textContent = id ? 'Berhasil Diupdate!' : 'Berhasil Ditambahkan!';
                        document.getElementById('successMessage').textContent = data.message;
                        document.getElementById('successModal').classList.add('active');

                        // Reload data
                        loadAdminData();
                    } else {
                        // Handle validation errors dari server
                        if (data.errors) {
                            // Mapping field errors
                            const errorMapping = {
                                'name': { errorId: 'nameError', inputId: 'formName' },
                                'email': { errorId: 'emailError', inputId: 'formEmail' },
                                'password': { errorId: 'passwordError', inputId: 'formPassword' },
                                'phone': { errorId: 'phoneError', inputId: 'formPhone' }
                            };

                            // Tampilkan error pertama dan scroll ke field tersebut
                            let firstError = true;
                            for (const [field, messages] of Object.entries(data.errors)) {
                                const mapping = errorMapping[field];
                                if (mapping) {
                                    const errorMsg = Array.isArray(messages) ? messages[0] : messages;
                                    showFieldError(mapping.errorId, errorMsg, firstError ? mapping.inputId : null);
                                    firstError = false;
                                }
                            }

                            // Tampilkan toast untuk error umum
                            if (firstError) {
                                showToast('Validasi Gagal', data.message || 'Periksa kembali data yang Anda masukkan', 'error');
                            }
                        } else {
                            showToast('Gagal Menyimpan', data.message || 'Terjadi kesalahan saat menyimpan data', 'error');
                        }
                    }
                })
                .catch(error => {
                    showToast('Gagal Menyimpan', 'Tidak dapat menyimpan data. Silakan coba lagi.', 'error');
                });
        });

        // ========================================
        // MODAL FUNCTIONS - DELETE
        // ========================================

        /**
         * Membuka modal konfirmasi hapus
         * @param {number} id - ID admin yang akan dihapus
         */
        function openDeleteModal(id) {
            deleteId = id;
            document.getElementById('deleteModal').classList.add('active');
        }

        /**
         * Konfirmasi dan proses penghapusan data
         */
        function confirmDelete() {
            if (!deleteId) return;

            fetch(`/admin/admin/${deleteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeModal('deleteModal');

                        // Show success modal
                        document.getElementById('successTitle').textContent = 'Berhasil Dihapus!';
                        document.getElementById('successMessage').textContent = data.message;
                        document.getElementById('successModal').classList.add('active');

                        // Reload data
                        loadAdminData();
                        deleteId = null;
                    } else {
                        closeModal('deleteModal');
                        showToast('Gagal Menghapus', data.message || 'Tidak dapat menghapus data admin', 'warning');
                    }
                })
                .catch(error => {
                    closeModal('deleteModal');
                    showToast('Gagal Menghapus', 'Terjadi kesalahan saat menghapus data', 'error');
                });
        }

        // ========================================
        // MODAL UTILITIES
        // ========================================

        /**
         * Menutup modal berdasarkan ID
         * @param {string} modalId - ID element modal
         */
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        /**
         * Event listener untuk close modal saat klik overlay
         */
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.addEventListener('click', function (e) {
                if (e.target === this) {
                    closeModal(this.id);
                }
            });
        });

        // ========================================
        // PASSWORD TOGGLE
        // ========================================

        /**
         * Toggle visibility password (show/hide)
         * @param {string} inputId - ID input password
         * @param {HTMLElement} button - Element tombol toggle
         */
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const eyeOpen = button.querySelector('.eye-open');
            const eyeClosed = button.querySelector('.eye-closed');

            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.style.display = 'none';
                eyeClosed.style.display = 'block';
            } else {
                input.type = 'password';
                eyeOpen.style.display = 'block';
                eyeClosed.style.display = 'none';
            }
        }

        // ========================================
        // FILE UPLOAD HANDLERS
        // ========================================

        const fileInput = document.getElementById('formSertifikasi');
        const uploadArea = document.getElementById('uploadArea');
        const filePreview = document.getElementById('filePreview');

        /**
         * Handle file input change
         */
        fileInput.addEventListener('change', function (e) {
            handleFile(this.files[0]);
        });

        /**
         * Handle drag over event
         */
        uploadArea.addEventListener('dragover', function (e) {
            e.preventDefault();
            this.classList.add('dragover');
        });

        /**
         * Handle drag leave event
         */
        uploadArea.addEventListener('dragleave', function (e) {
            e.preventDefault();
            this.classList.remove('dragover');
        });

        /**
         * Handle drop event
         */
        uploadArea.addEventListener('drop', function (e) {
            e.preventDefault();
            this.classList.remove('dragover');
            const file = e.dataTransfer.files[0];
            fileInput.files = e.dataTransfer.files;
            handleFile(file);
        });

        /**
         * Validasi dan preview file yang diupload
         * @param {File} file - File object yang diupload
         */
        function handleFile(file) {
            if (!file) return;

            // Clear previous error
            const fileErrorElement = document.getElementById('fileError');
            fileErrorElement.classList.remove('active');
            fileErrorElement.textContent = '';

            // Validate file type
            const validTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                showFieldError('fileError', 'Format file tidak valid! Gunakan PDF, JPG, atau PNG', 'formSertifikasi');
                fileInput.value = '';
                return;
            }

            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                showFieldError('fileError', 'Ukuran file terlalu besar! Maksimal 2MB', 'formSertifikasi');
                fileInput.value = '';
                return;
            }

            // Show preview
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileSize').textContent = formatFileSize(file.size);
            filePreview.classList.add('active');
            uploadArea.style.display = 'none';
        }

        /**
         * Remove selected file
         * @param {Event} e - Event object
         */
        function removeFile(e) {
            e.stopPropagation();
            fileInput.value = '';
            filePreview.classList.remove('active');
            uploadArea.style.display = 'block';
        }

        /**
         * Format ukuran file ke format yang readable
         * @param {number} bytes - Ukuran file dalam bytes
         * @returns {string} Ukuran terformat (contoh: 1.5 MB)
         */
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}    
    </script>
    @endpush

@endsection