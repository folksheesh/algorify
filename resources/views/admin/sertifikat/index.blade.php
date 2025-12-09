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
                    <h1>Kelola Tanda Tangan Sertifikat</h1>
                    <p>Upload tanda tangan yang akan digunakan pada sertifikat pelatihan TIK</p>
                </div>

                <div class="content-card">
                    <h2 class="card-title">Kelola Tanda Tangan Sertifikat</h2>
                    <p class="card-subtitle">Upload tanda tangan yang akan digunakan pada sertifikat pelatihan TIK</p>

                    <!-- Alert Messages -->
                    <div id="alertContainer"></div>

                    <!-- Upload Guidelines -->
                    <div class="upload-guidelines">
                        <div class="guidelines-title">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            Panduan Upload Tanda Tangan:
                        </div>
                        <ul class="guidelines-list">
                            <li>Format file yang diterima: PNG, JPG, atau JPEG</li>
                            <li>Ukuran maksimal file: 2MB</li>
                            <li>Disarankan menggunakan tanda tangan dengan latar belakang transparan (PNG)</li>
                            <li>Resolusi yang disarankan: minimal 300 x 150 pixel untuk hasil terbaik</li>
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

                <!-- Additional Info Section -->
                <div class="content-card" style="margin-top: 1.5rem;">
                    <h2 class="card-title">Syarat dan Ketentuan</h2>
                    <ul class="guidelines-list" style="padding-left: 0;">
                        <li>Tanda tangan harus dalam format digital dengan kualitas yang baik dan jelas</li>
                        <li>File harus memiliki transparansi background (PNG) untuk hasil terbaik pada sertifikat</li>
                        <li>Ukuran file maksimal 2MB untuk memastikan proses upload yang cepat</li>
                        <li>Tanda tangan yang diupload akan diterapkan pada semua sertifikat yang diterbitkan</li>
                        <li>Pastikan tanda tangan sudah mendapat persetujuan dari pihak yang berwenang</li>
                    </ul>
                </div>
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
        document.documentElement.setAttribute('data-bs-theme', 'light');

        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

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
        }

        // Remove signature preview
        function removeSignaturePreview() {
            const section = document.getElementById('signatureSection');
            section.classList.remove('has-signature');
            section.style.borderColor = '#CBD5E1';
            section.style.background = '#F8FAFC';
            
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
