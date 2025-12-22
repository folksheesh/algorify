@extends('layouts.template')

@section('title', 'Data Kursus Saya - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/pelatihan-index.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@endpush

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')
        <main class="main-content">
            <div class="page-container">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="page-header-top">
                        <h1 class="page-title">Data Kursus</h1>
                    </div>
                    
                    <div class="search-box">
                        <svg class="search-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                        <input 
                            type="text" 
                            class="search-input" 
                            id="searchKursus"
                            placeholder="Cari kursus berdasarkan judul..."
                            autocomplete="off"
                        >
                    </div>
                </div>

                @if($kursus->count() > 0)
                    <!-- No-match message (hidden by default) -->
                    <div id="noMatchMessage" style="display:none;background:#fff;padding:1rem;border-radius:8px;margin-bottom:1rem;border:1px dashed #e5e7eb;color:#374151;"></div>
                    
                    <!-- Courses Grid -->
                    <div class="courses-grid">
                        @foreach($kursus as $course)
                        <div class="course-card" onclick="window.location='{{ route('admin.pelatihan.show', $course->slug) }}'" style="cursor: pointer;">
                            <div class="course-thumbnail-container">
                                @php
                                    $courseThumbnailUrl = $course->thumbnail ? resolve_thumbnail_url($course->thumbnail) : null;
                                @endphp
                                @if($courseThumbnailUrl)
                                    <img src="{{ $courseThumbnailUrl }}" 
                                         alt="{{ $course->judul }}" 
                                         class="course-thumbnail"
                                         onerror="this.style.display='none'; this.parentElement.style.background='linear-gradient(135deg, #667eea 0%, #764ba2 100%)';">
                                @else
                                    <div class="course-thumbnail" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                                @endif
                                <span class="course-badge">{{ strtoupper(str_replace('_', ' ', $course->kategori ?? 'OTHER')) }}</span>
                                
                                <!-- Hover Overlay with Admin Style Actions -->
                                <div class="card-overlay">
                                    <button class="overlay-btn edit" title="Edit" onclick="event.stopPropagation(); editKursus({{ $course->id }})">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                    </button>
                                    <button class="overlay-btn delete" title="Hapus" onclick="event.stopPropagation(); deleteKursus({{ $course->id }})">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="course-content">
                                <h3 class="course-title">{{ $course->judul }}</h3>
                                <p class="course-type">{{ ucfirst($course->status ?? 'Published') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $kursus->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path d="M4 6C4 4.89543 4.89543 4 6 4H18C19.1046 4 20 4.89543 20 6V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V6Z"/>
                            </svg>
                        </div>
                        <h3>Belum Ada Kursus</h3>
                        <p>Belum ada kursus yang di-assign ke Anda</p>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Modal Edit Kursus (Adapted from Admin) -->
    <div class="modal-overlay" id="modalKursus">
        <div class="modal-container">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title" id="modalTitle">Edit Kursus</h2>
                    <p class="modal-subtitle">Perbarui informasi kursus pembelajaran</p>
                </div>
                <button class="modal-close" onclick="closeModal()">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            
            <form id="formKursus" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="PUT">
                <input type="hidden" name="kursus_id" id="kursusId">
                <!-- Pengajar ID auto-set to current user -->
                <input type="hidden" name="pengajar_id" id="pengajar_id" value="{{ auth()->id() }}">
                
                <div class="modal-body">
                    <!-- Upload Gambar Section -->
                    <div class="form-group" style="margin-bottom: 2rem;">
                        <label class="form-label">Upload Gambar Kursus</label>
                        <input type="file" name="thumbnail" id="thumbnail" accept="image/png,image/jpeg,image/jpg" style="display: none;" onchange="previewThumbnail(event)">
                        <div class="upload-area" id="uploadArea" onclick="document.getElementById('thumbnail').click()">
                            <svg class="upload-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <p class="upload-text">Drag & drop Gambar</p>
                            <p class="upload-hint">Format: PNG atau JPG (Max 1MB)</p>
                            <button type="button" class="btn-upload" onclick="event.stopPropagation(); document.getElementById('thumbnail').click();">Pilih File</button>
                        </div>
                        <div id="previewContainer" class="preview-container" style="display: none;">
                            <img id="previewImage" class="preview-image" alt="Preview">
                            <button type="button" class="preview-remove" onclick="removeThumbnail(event)">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Informasi Dasar Section -->
                    <h3 style="font-size: 0.9375rem; font-weight: 600; color: #1A1A1A; margin-bottom: 1rem;">Informasi Dasar</h3>
                    
                    <div class="form-group">
                        <label class="form-label">Nama Kursus <span class="required">*</span></label>
                        <input type="text" name="judul" id="judul" class="form-input" placeholder="Contoh: Peran & Tugas Frontend Developer" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Kategori <span class="required">*</span></label>
                            <select name="kategori" id="kategori" class="form-select" required>
                                <option value="" disabled selected hidden>Pilih Kategori</option>
                                @foreach($categories as $kategori)
                                    <option value="{{ $kategori->slug }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tipe Kursus <span class="required">*</span></label>
                            <select name="tipe_kursus" id="tipe_kursus" class="form-select" required>
                                <option value="" disabled selected hidden>Pilih Tipe Kursus</option>
                                <option value="online">Online</option>
                                <option value="hybrid">Hybrid</option>
                                <option value="offline">Offline</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi Kursus</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-textarea" placeholder="Jelaskan tentang kursus ini..." rows="3"></textarea>
                    </div>

                    <!-- Detail Kursus Section -->
                    <h3 style="font-size: 0.9375rem; font-weight: 600; color: #1A1A1A; margin-bottom: 1rem; margin-top: 1.5rem;">Detail Kursus</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Durasi <span class="required">*</span></label>
                            <input type="number" name="durasi" id="durasi" class="form-input" placeholder="Contoh: 8" min="1" step="1" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Harga <span class="required">*</span></label>
                            <input type="text" name="harga" id="harga" class="form-input" placeholder="Contoh: Rp 2.500.000" inputmode="numeric" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kapasitas Peserta</label>
                            <input type="number" name="kapasitas" id="kapasitas" class="form-input" placeholder="Contoh: 30" min="1" step="1">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn-primary">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span id="btnSubmitText">Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Admin Style Delete Confirmation Modal -->
    <style>
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes fadeOut { from { opacity: 1; } to { opacity: 0; } }
        @keyframes slideIn { from { opacity: 0; transform: scale(0.9) translateY(-20px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        @keyframes slideOut { from { opacity: 1; transform: scale(1) translateY(0); } to { opacity: 0; transform: scale(0.9) translateY(-20px); } }
        
        #deleteKursusModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        #deleteKursusModal.active { display: flex; animation: fadeIn 0.2s ease-out forwards; }
        #deleteKursusModal.closing { animation: fadeOut 0.2s ease-out forwards; }
        #deleteKursusModal .delete-modal-content {
            background: white;
            border-radius: 16px;
            max-width: 400px;
            width: 90%;
            padding: 2rem;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            position: relative;
            animation: slideIn 0.3s ease-out forwards;
        }
        #deleteKursusModal.closing .delete-modal-content { animation: slideOut 0.2s ease-out forwards; }
    </style>
    <div id="deleteKursusModal">
        <div class="delete-modal-content">
            <button onclick="closeDeleteKursusModal()" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #64748B; line-height: 1;">&times;</button>
            <div style="text-align: center;">
                <div style="width: 60px; height: 60px; background: #FEE2E2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2M10 11v6M14 11v6"/>
                    </svg>
                </div>
                <h2 style="color: #1E293B; margin: 0 0 0.5rem; font-size: 1.25rem; font-weight: 600;">Konfirmasi Hapus</h2>
                <p style="color: #64748B; font-size: 0.875rem; margin: 0 0 1.5rem;">Apakah Anda yakin ingin menghapus kursus ini? Tindakan ini tidak dapat dibatalkan.</p>
                <div style="display: flex; justify-content: center; gap: 1rem;">
                    <button type="button" onclick="closeDeleteKursusModal()" style="padding: 0.625rem 1.5rem; border-radius: 8px; font-weight: 500; background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0; cursor: pointer;">Batal</button>
                    <button type="button" onclick="confirmDeleteKursus()" style="background: #DC2626; color: white; padding: 0.625rem 1.5rem; border-radius: 8px; font-weight: 500; border: none; cursor: pointer;">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');
        
        // --- Helper Functions ---
        function normalizeHarga(value) {
            const raw = String(value ?? '');
            if (!raw) return 0;
            if (raw.includes('.') && !raw.includes('Rp')) {
                const numeric = parseFloat(raw);
                return Number.isNaN(numeric) ? 0 : Math.round(numeric);
            }
            const digits = raw.replace(/[^0-9]/g, '');
            return digits ? parseInt(digits, 10) : 0;
        }

        function formatRupiah(value) {
            const numericValue = normalizeHarga(value);
            if (!numericValue) return '';
            const formatted = new Intl.NumberFormat('id-ID').format(numericValue);
            return 'Rp ' + formatted;
        }

        // --- Price Input Formatting ---
        const hargaInput = document.getElementById('harga');
        const maxHarga = 99999999;
        if (hargaInput) {
            hargaInput.addEventListener('input', function (e) {
                e.target.value = formatRupiah(e.target.value);
            });
        }
        
        // --- Search Functionality ---
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchKursus');
            const courseCards = document.querySelectorAll('.course-card');
            
            if (searchInput) {
                const noMatchEl = document.getElementById('noMatchMessage');
                searchInput.addEventListener('input', function(e) {
                    const rawValue = e.target.value || '';
                    const searchTerm = rawValue.toLowerCase().trim();
                    let visibleCount = 0;
                    
                    courseCards.forEach(card => {
                        const title = card.querySelector('.course-title');
                        if (title) {
                            const titleText = title.textContent.toLowerCase();
                            if (searchTerm === '' || titleText.includes(searchTerm)) {
                                card.style.display = '';
                                visibleCount++;
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });

                    if (noMatchEl) {
                        if (visibleCount === 0 && searchTerm !== '') {
                            noMatchEl.style.display = 'block';
                            noMatchEl.textContent = 'Tidak dapat menemukan kursus dengan nama "' + rawValue + '"';
                        } else {
                            noMatchEl.style.display = 'none';
                        }
                    }
                });
            }
        });

        // --- Edit Modal Functions ---
        function openModal(mode = 'edit') {
            const modal = document.getElementById('modalKursus');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            if (mode === 'edit') {
                document.getElementById('modalTitle').textContent = 'Edit Kursus';
                document.getElementById('btnSubmitText').textContent = 'Update';
            }
        }

        function closeModal() {
            const modal = document.getElementById('modalKursus');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
            document.getElementById('formKursus').reset();
            resetPreview();
        }

        function resetPreview() {
            document.getElementById('uploadArea').style.display = 'flex';
            document.getElementById('previewContainer').style.display = 'none';
            document.getElementById('thumbnail').value = '';
        }

        function previewThumbnail(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                    document.getElementById('uploadArea').style.display = 'none';
                    document.getElementById('previewContainer').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }

        function removeThumbnail(event) {
            event.stopPropagation();
            document.getElementById('thumbnail').value = '';
            document.getElementById('uploadArea').style.display = 'flex';
            document.getElementById('previewContainer').style.display = 'none';
        }

        function editKursus(id) {
            // Fetch and Populate
            fetch(`/admin/pelatihan/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    const form = document.getElementById('formKursus');
                    form.reset();
                    
                    document.getElementById('formMethod').value = 'PUT';
                    form.action = `/admin/pelatihan/${id}`;
                    
                    document.getElementById('kursusId').value = data.id;
                    document.getElementById('judul').value = data.judul;
                    document.getElementById('kategori').value = data.kategori;
                    document.getElementById('tipe_kursus').value = data.tipe_kursus || 'online';
                    document.getElementById('deskripsi').value = data.deskripsi || '';
                    document.getElementById('durasi').value = data.durasi || '';
                    document.getElementById('harga').value = formatRupiah(data.harga);
                    document.getElementById('kapasitas').value = data.kapasitas || '';
                    
                    // Fixed pengajar_id to current user
                    document.getElementById('pengajar_id').value = '{{ auth()->id() }}';

                    if (data.thumbnail) {
                        const thumbnailUrl = data.thumbnail.startsWith('http') 
                            ? data.thumbnail 
                            : '{{ asset("storage") }}/' + data.thumbnail;
                        document.getElementById('previewImage').src = thumbnailUrl;
                        document.getElementById('uploadArea').style.display = 'none';
                        document.getElementById('previewContainer').style.display = 'block';
                    } else {
                        document.getElementById('uploadArea').style.display = 'flex';
                        document.getElementById('previewContainer').style.display = 'none';
                    }
                    
                    openModal('edit');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat data kursus');
                });
        }

        // --- Delete Modal Functions ---
        let deleteKursusId = null;

        function deleteKursus(id) {
            deleteKursusId = id;
            const modal = document.getElementById('deleteKursusModal');
            modal.classList.remove('closing');
            modal.classList.add('active');
        }

        function closeDeleteKursusModal() {
            const modal = document.getElementById('deleteKursusModal');
            modal.classList.add('closing');
            setTimeout(() => {
                modal.classList.remove('active', 'closing');
                deleteKursusId = null;
            }, 200);
        }

        function confirmDeleteKursus() {
            if (!deleteKursusId) return;
            
            fetch(`/admin/pelatihan/${deleteKursusId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeDeleteKursusModal();
                    location.reload();
                } else {
                    alert('Gagal menghapus kursus');
                    closeDeleteKursusModal();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus kursus');
                closeDeleteKursusModal();
            });
        }

        // --- Close Modals on Overlay/Escape ---
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
                closeDeleteKursusModal();
            }
        });
        
        document.getElementById('modalKursus')?.addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
        
        document.getElementById('deleteKursusModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeDeleteKursusModal();
        });
    </script>
@endpush
