@extends('layouts.template')

@section('title', 'Sertifikat - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin/sertifikat-index.css') }}">
@endpush

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')
        <main class="main-content">
            <div style="padding: 0 2rem 2rem;">
                <div class="page-header">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <h1>Kelola Tanda Tangan Sertifikat</h1>
                            <p>Upload tanda tangan yang akan digunakan pada sertifikat pelatihan TIK</p>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="openModalPreviewSertif()" style="margin-top: 0.5rem;">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                            </svg>
                            Preview Sertifikat
                        </button>
                    </div>
                </div>

                <!-- Modal Preview Sertifikat -->
                <div id="modalPreviewSertif" class="modal-overlay" style="display:none;">
                    <div class="modal-content" style="max-width: 900px;">
                        <button class="modal-close" onclick="closeModalPreviewSertif()" aria-label="Close">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.5 4.5L13.5 13.5M4.5 13.5L13.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </button>
                        <div class="certificate-preview-card">
                            <div class="certificate-preview-header">
                                <div>
                                    <div class="preview-title">Preview Sertifikat</div>
                                    <div class="preview-subtitle">Menampilkan tata letak mendekati sertifikat asli</div>
                                </div>
                                <div class="preview-badge">Sampel</div>
                            </div>
                            <div class="certificate-preview-body">
                                <div class="certificate-preview">
                                    <div class="preview-frame">
                                        <div class="preview-logo">
                                            <div class="preview-logo-mark"></div>
                                            <span>Algorify</span>
                                        </div>
                                        <div class="preview-title-script">Sertifikat Penyelesaian</div>
                                        <div class="preview-subtitle-en">Certificate of Completion</div>
                                        <div class="preview-label">Diberikan kepada</div>
                                        <div class="preview-name" id="certificatePreviewName">Peserta</div>
                                        <div class="preview-course-label">Telah berhasil menyelesaikan pelatihan</div>
                                        <div class="preview-course">Analisis Data</div>
                                        <div class="preview-desc">dengan menunjukkan dedikasi, pemahaman mendalam, dan keterampilan praktis dalam bidang analisis data.</div>

                                        <div class="preview-info-row">
                                            <div class="preview-info-box">
                                                <span class="lbl">Tanggal Selesai</span>
                                                <span class="val">09 December 2025</span>
                                            </div>
                                            <div class="preview-info-box">
                                                <span class="lbl">Nilai Akhir</span>
                                                <span class="val score">100/100</span>
                                            </div>
                                        </div>

                                        <div class="preview-footer-row">
                                            <div class="preview-qr">
                                                <div class="qr-box"></div>
                                                <div class="qr-text">Scan untuk verifikasi<br>CERT-2025-AUSZV7MZEM</div>
                                            </div>
                                            <div class="preview-badge-official">
                                                <div class="badge-circle"></div>
                                                <div class="badge-text">Sertifikat Resmi</div>
                                            </div>
                                            <div class="preview-signature-block">
                                                @if($signature)
                                                    <img src="{{ $signature }}" alt="Tanda Tangan" id="certificatePreviewSignature">
                                                @else
                                                    <div class="preview-signature-placeholder" id="certificatePreviewSignature">Belum ada tanda tangan</div>
                                                @endif
                                                <div class="preview-sign-name" id="certificatePreviewNameSignature">{{ $signatureOwner ?? 'Nama Pemilik Tanda Tangan' }}</div>
                                                <div class="preview-sign-role">Direktur Algorify</div>
                                                <div class="preview-sign-loc">Jakarta, Indonesia</div>
                                            </div>
                                        </div>

                                        <div class="preview-verify">Sertifikat ini dapat diverifikasi di <span>algorify.com/verify</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content-card">
                    <h2 class="card-title">Kelola Tanda Tangan Sertifikat</h2>
                    <p class="card-subtitle">Upload tanda tangan yang akan digunakan pada sertifikat pelatihan TIK</p>

                    <!-- Alert Messages -->
                    <div id="alertContainer"></div>


                    <!-- Syarat dan Ketentuan (moved up) -->
                    <div class="upload-guidelines">
                        <div class="guidelines-title">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            Syarat dan Ketentuan:
                        </div>
                        <ul class="guidelines-list" style="padding-left: 0;">
                            <li>Tanda tangan harus dalam format digital dengan kualitas yang baik dan jelas</li>
                            <li>File harus memiliki transparansi background (PNG) untuk hasil terbaik pada sertifikat</li>
                            <li>Ukuran file maksimal 2MB untuk memastikan proses upload yang cepat</li>
                            <li>Tanda tangan yang diupload akan diterapkan pada semua sertifikat yang diterbitkan</li>
                            <li>Pastikan tanda tangan sudah mendapat persetujuan dari pihak yang berwenang</li>
                        </ul>
                    </div>

                    <!-- Signature Section -->
                    <div id="signatureSection" class="signature-section {{ $signature ? 'has-signature' : '' }}">
                        @if($signature)
                        <div id="signaturePreviewContainer">
                            <div class="signature-preview">
                                <img id="signatureImage" src="{{ $signature }}" alt="Tanda Tangan Direktur">
                            </div>
                            <div class="signature-info">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div class="signature-info-text">
                                    <div class="signature-info-label">Tanda Tangan Direktur</div>
                                    <div class="signature-info-value">Direktur Pelatihan TIK • Tanda tangan untuk sertifikat sebagai pengesahan dari direktur</div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div id="uploadPlaceholder" class="upload-placeholder">
                            <div class="upload-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>
                            <div class="upload-text">Drag & drop file tanda tangan di sini</div>
                            <div class="upload-subtext">atau</div>
                        </div>
                        @endif

                        <div class="button-group">
                            <input type="file" id="signatureInput" class="upload-input" accept="image/png,image/jpeg,image/jpg">
                            <button type="button" class="btn btn-primary" onclick="document.getElementById('signatureInput').click()">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                {{ $signature ? 'Ganti Tanda Tangan' : 'Pilih File' }}
                            </button>
                            @if($signature)
                            <button type="button" class="btn btn-danger" onclick="deleteSignature()">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Hapus
                            </button>
                            @endif
                        </div>
                    </div>

                    <div class="signature-owner-form">
                        <label for="signatureOwnerInput" class="owner-label">Nama Pemilik Tanda Tangan</label>
                        <div class="owner-input-group">
                            <input type="text" id="signatureOwnerInput" class="owner-input" placeholder="Contoh: Dr. Andi Pratama" value="{{ $signatureOwner }}">
                            <button type="button" class="btn btn-primary" id="saveOwnerButton">Simpan Nama</button>
                        </div>
                        <p class="owner-hint">Nama ini akan ditampilkan pada sertifikat bersama tanda tangan.</p>
                    </div>

                    <!-- Privacy Note -->
                    <div class="privacy-note">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        <div class="privacy-note-content">
                            <div class="privacy-note-title">Kebijakan Privasi</div>
                            <p class="privacy-note-text">Tanda tangan yang diupload akan disimpan secara aman dan hanya digunakan untuk keperluan sertifikat pelatihan. Pastikan file yang diupload adalah tanda tangan resmi yang telah disetujui.</p>
                        </div>
                    </div>
                </div>

                <!-- Additional Info Section (removed, now shown above) -->
            </div>
        </main>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>
@endsection

@push('scripts')
    <script>
        function openModalPreviewSertif() {
            // Open modal in new popup window
            const width = 1000;
            const height = 800;
            const left = (screen.width - width) / 2;
            const top = (screen.height - height) / 2;
            
            const modal = document.getElementById('modalPreviewSertif');
            const modalContent = modal.querySelector('.certificate-preview-card').outerHTML;
            
            const popup = window.open('', 'PreviewSertifikat', 
                `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes`);
            
            if (popup) {
                popup.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset="UTF-8">
                        <title>Preview Sertifikat - Algorify</title>
                        <link rel="stylesheet" href="{{ asset('css/admin/sertifikat-index.css') }}">
                        <link rel="preconnect" href="https://fonts.googleapis.com">
                        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
                        <style>
                            * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
                            body { margin: 0; padding: 2rem; background: linear-gradient(135deg, #f6f4ff 0%, #eeedff 100%); }
                            .certificate-preview-card { margin: 0; box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
                        </style>
                    </head>
                    <body>
                        ${modalContent}
                    </body>
                    </html>
                `);
                popup.document.close();
            } else {
                alert('Popup diblokir oleh browser. Mohon izinkan popup untuk melihat preview.');
            }
        }
        function closeModalPreviewSertif() {
            document.getElementById('modalPreviewSertif').style.display = 'none';
        }
        document.documentElement.setAttribute('data-bs-theme', 'light');

        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const ownerInput = document.getElementById('signatureOwnerInput');
        const saveOwnerButton = document.getElementById('saveOwnerButton');
        const previewName = document.getElementById('certificatePreviewName');
        const previewNameSignature = document.getElementById('certificatePreviewNameSignature');
        let previewSignature = document.getElementById('certificatePreviewSignature');
        let currentSignatureUrl = `{{ $signature }}` || '';

        // File input change handler
        document.getElementById('signatureInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                uploadSignature(file);
            }
        });

        // Drag and drop handlers
        const signatureSection = document.getElementById('signatureSection');
        
        signatureSection.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.style.borderColor = '#667eea';
            this.style.background = '#F0F3FF';
        });

        signatureSection.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (!this.classList.contains('has-signature')) {
                this.style.borderColor = '#CBD5E1';
                this.style.background = '#F8FAFC';
            }
        });

        signatureSection.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const file = e.dataTransfer.files[0];
            if (file && file.type.match('image.*')) {
                uploadSignature(file);
            } else {
                showAlert('Format file tidak valid. Gunakan PNG, JPG, atau JPEG.', 'error');
            }
        });

        // Owner name live update
        ownerInput?.addEventListener('input', () => {
            const val = ownerInput.value.trim() || 'Nama Pemilik Tanda Tangan';
            previewName.textContent = val;
            if (previewNameSignature) previewNameSignature.textContent = val;
        });

        saveOwnerButton?.addEventListener('click', () => {
            const name = ownerInput.value.trim();
            if (!name) {
                showAlert('Nama pemilik wajib diisi', 'error');
                return;
            }

            showLoading();

            fetch('{{ route("admin.sertifikat.save-signature-info") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ owner_name: name })
            })
            .then(res => res.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showAlert(data.message, 'success');
                    previewName.textContent = data.owner_name;
                    if (previewNameSignature) previewNameSignature.textContent = data.owner_name;
                } else {
                    showAlert(data.message || 'Gagal menyimpan nama', 'error');
                }
            })
            .catch(() => {
                hideLoading();
                showAlert('Terjadi kesalahan saat menyimpan nama', 'error');
            });
        });

        // Upload signature function
        function uploadSignature(file) {
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                showAlert('Ukuran file terlalu besar. Maksimal 2MB.', 'error');
                return;
            }

            // Validate file type
            if (!['image/png', 'image/jpeg', 'image/jpg'].includes(file.type)) {
                showAlert('Format file tidak valid. Gunakan PNG, JPG, atau JPEG.', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('signature', file);
            formData.append('_token', csrfToken);

            showLoading();

            fetch('{{ route("admin.sertifikat.upload-signature") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showAlert(data.message, 'success');
                    updateSignaturePreview(data.url);
                } else {
                    showAlert(data.message || 'Gagal mengupload tanda tangan', 'error');
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat mengupload tanda tangan', 'error');
            });
        }

        // Delete signature function
        function deleteSignature() {
            if (!confirm('Apakah Anda yakin ingin menghapus tanda tangan ini?')) {
                return;
            }

            showLoading();

            fetch('{{ route("admin.sertifikat.delete-signature") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showAlert(data.message, 'success');
                    removeSignaturePreview();
                } else {
                    showAlert(data.message || 'Gagal menghapus tanda tangan', 'error');
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat menghapus tanda tangan', 'error');
            });
        }

        // Update signature preview
        function updateSignaturePreview(url) {
            const section = document.getElementById('signatureSection');
            section.classList.add('has-signature');
            currentSignatureUrl = url;
            
            section.innerHTML = `
                <div id="signaturePreviewContainer">
                    <div class="signature-preview">
                        <img id="signatureImage" src="${url}" alt="Tanda Tangan Direktur">
                    </div>
                    <div class="signature-info">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div class="signature-info-text">
                            <div class="signature-info-label">Tanda Tangan Direktur</div>
                            <div class="signature-info-value">Direktur Pelatihan TIK • Tanda tangan untuk sertifikat sebagai pengesahan dari direktur</div>
                        </div>
                    </div>
                </div>
                <div class="button-group">
                    <input type="file" id="signatureInput" class="upload-input" accept="image/png,image/jpeg,image/jpg">
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('signatureInput').click()">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Ganti Tanda Tangan
                    </button>
                    <button type="button" class="btn btn-danger" onclick="deleteSignature()">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Hapus
                    </button>
                </div>
            `;
            
            // Re-attach event listener
            document.getElementById('signatureInput').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    uploadSignature(file);
                }
            });

            // Update certificate preview image
            if (previewSignature.tagName.toLowerCase() === 'img') {
                previewSignature.src = url;
            } else {
                const img = document.createElement('img');
                img.id = 'certificatePreviewSignature';
                img.src = url;
                previewSignature.replaceWith(img);
                previewSignature = img;
            }
        }

        // Remove signature preview
        function removeSignaturePreview() {
            const section = document.getElementById('signatureSection');
            section.classList.remove('has-signature');
            section.style.borderColor = '#CBD5E1';
            section.style.background = '#F8FAFC';
            currentSignatureUrl = '';
            
            section.innerHTML = `
                <div id="uploadPlaceholder" class="upload-placeholder">
                    <div class="upload-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>
                    <div class="upload-text">Drag & drop file tanda tangan di sini</div>
                    <div class="upload-subtext">atau</div>
                </div>
                <div class="button-group">
                    <input type="file" id="signatureInput" class="upload-input" accept="image/png,image/jpeg,image/jpg">
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('signatureInput').click()">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Pilih File
                    </button>
                </div>
            `;
            
            // Re-attach event listener
            document.getElementById('signatureInput').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    uploadSignature(file);
                }
            });

            // Reset certificate preview image
            const placeholder = document.createElement('div');
            placeholder.className = 'preview-signature-placeholder';
            placeholder.id = 'certificatePreviewSignature';
            placeholder.textContent = 'Belum ada tanda tangan';
            previewSignature.replaceWith(placeholder);
            previewSignature = placeholder;
        }

        // Show alert
        function showAlert(message, type) {
            const container = document.getElementById('alertContainer');
            const iconPath = type === 'success' 
                ? 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
                : 'M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z';
            
            container.innerHTML = `
                <div class="alert alert-${type}">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="${iconPath}" clip-rule="evenodd"/>
                    </svg>
                    <div>${message}</div>
                </div>
            `;
            
            setTimeout(() => {
                container.innerHTML = '';
            }, 5000);
        }

        // Show/hide loading
        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('active');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('active');
        }
    </script>
@endpush
