@extends('layouts.template')

@section('title', 'Data Kursus - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/pelatihan-index.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Topbar Layout Adjustment for Pengajar */
        .dashboard-container.with-topbar {
            padding-top: 64px;
        }
        
        .dashboard-container.with-topbar .main-content {
            padding-top: 1.5rem;
        }
        
        @media (max-width: 992px) {
            .dashboard-container.with-topbar .main-content {
                margin-left: 0;
            }
        }
    </style>
@endpush

@section('content')
    {{-- Topbar Pengajar --}}
    @role('pengajar')
    @include('components.topbar-pengajar')
    @endrole
    
    <div class="dashboard-container @role('pengajar') with-topbar @endrole">
        @include('components.sidebar')
        <main class="main-content">
            <div class="page-container">
                <!-- Page Header -->
                <div class="page-header">
                    <h1 class="page-title">Data Kursus</h1>
                    
                    <!-- Search Bar -->
                    <div class="search-wrapper">
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
                </div>

                @hasanyrole('admin|super admin')
                <!-- Floating Add Button -->
                <button class="btn-add-floating" title="Tambah Kursus" onclick="openModal()">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                </button>
                @endhasanyrole

                <!-- Modal Add/Edit Kursus -->
                <div class="modal-overlay" id="modalKursus">
                    <div class="modal-container">
                        <div class="modal-header">
                            <div>
                                <h2 class="modal-title" id="modalTitle">Tambah Kursus</h2>
                                <p class="modal-subtitle">Masukkan informasi kursus pembelajaran</p>
                            </div>
                            <button class="modal-close" onclick="closeModal()">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                        
                        <form id="formKursus" action="{{ route('admin.pelatihan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" id="formMethod" value="POST">
                            <input type="hidden" name="kursus_id" id="kursusId">
                            
                            <div class="modal-body">
                                <!-- Upload Gambar Section -->
                                <div class="form-group" style="margin-bottom: 2rem;">
                                    <label class="form-label">
                                        Upload Gambar Kursus
                                    </label>
                                    <input 
                                        type="file" 
                                        name="thumbnail" 
                                        id="thumbnail"
                                        accept="image/png,image/jpeg,image/jpg"
                                        style="display: none;"
                                        onchange="previewThumbnail(event)"
                                    >
                                    <div class="upload-area" id="uploadArea" onclick="document.getElementById('thumbnail').click()">
                                        <svg class="upload-icon" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <p class="upload-text">Drag & drop Gambar</p>
                                        <p class="upload-hint">Format: PNG atau JPG (Max 500KB)</p>
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
                                    <label class="form-label">
                                        Nama Kursus <span class="required">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        name="judul" 
                                        id="judul"
                                        class="form-input" 
                                        placeholder="Contoh: Peran & Tugas Frontend Developer"
                                        required
                                    >
                                    @error('judul')
                                        <span style="color: #DC2626; font-size: 0.75rem;">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Kategori <span class="required">*</span>
                                        </label>
                                        <select name="kategori" id="kategori" class="form-select" required>
                                            <option value="">UI/UX DESIGN</option>
                                            <option value="programming">Programming</option>
                                            <option value="design">UI/UX Design</option>
                                            <option value="data_science">Data Science</option>
                                            <option value="business">Business</option>
                                            <option value="marketing">Marketing</option>
                                            <option value="other">Lainnya</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">
                                            Tipe Kursus <span class="required">*</span>
                                        </label>
                                        <select name="tipe_kursus" id="tipe_kursus" class="form-select" required>
                                            <option value="">Online</option>
                                            <option value="online">Online</option>
                                            <option value="hybrid">Hybrid</option>
                                            <option value="offline">Offline</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        Deskripsi Kursus
                                    </label>
                                    <textarea 
                                        name="deskripsi" 
                                        id="deskripsi"
                                        class="form-textarea" 
                                        placeholder="Jelaskan tentang kursus ini..."
                                        rows="3"
                                    ></textarea>
                                </div>

                                <!-- Detail Kursus Section -->
                                <h3 style="font-size: 0.9375rem; font-weight: 600; color: #1A1A1A; margin-bottom: 1rem; margin-top: 1.5rem;">Detail Kursus</h3>
                                
                                <div class="form-group">
                                    <label class="form-label">
                                        Nama Pengajar <span class="required">*</span>
                                    </label>
                                    <select name="pengajar_id" id="pengajar_id" class="form-select" required>
                                        <option value="">Pilih Pengajar</option>
                                        @foreach($pengajars as $pengajar)
                                            <option value="{{ $pengajar->id }}" {{ $pengajar->id == auth()->id() ? 'selected' : '' }}>
                                                {{ $pengajar->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Durasi <span class="required">*</span>
                                        </label>
                                        <input 
                                            type="text" 
                                            name="durasi" 
                                            id="durasi"
                                            class="form-input" 
                                            placeholder="Contoh: 8 Minggu"
                                            required
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">
                                            Harga <span class="required">*</span>
                                        </label>
                                        <input 
                                            type="text" 
                                            name="harga" 
                                            id="harga"
                                            class="form-input" 
                                            placeholder="Contoh: Rp 2.500.000"
                                            required
                                        >
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

                @if($kursus->count() > 0)
                    <!-- Courses Grid -->
                    <div class="courses-grid">
                        @foreach($kursus as $course)
                        <div class="course-card" onclick="window.location='{{ route('admin.pelatihan.show', $course->id) }}'" style="cursor: pointer;">
                            <div class="course-thumbnail-container">
                                @php
                                    $courseThumbnailUrl = $course->thumbnail ? resolve_thumbnail_url($course->thumbnail) : null;
                                @endphp
                                @if($course->thumbnail)
                                    <img src="{{ $courseThumbnailUrl }}" 
                                         alt="{{ $course->judul }}" 
                                         class="course-thumbnail"
                                         onerror="this.style.display='none'; this.parentElement.style.background='linear-gradient(135deg, #667eea 0%, #764ba2 100%)';">
                                @else
                                    <div class="course-thumbnail" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                                @endif
                                <span class="course-badge">{{ strtoupper(str_replace('_', ' ', $course->kategori ?? 'OTHER')) }}</span>
                                @hasrole('peserta')
                                <button class="course-favorite" onclick="event.stopPropagation();">
                                    <svg viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                @endhasrole

                                @hasanyrole('admin|super admin')
                                <!-- Overlay with action buttons -->
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
                                @endhasanyrole
                            </div>
                            <div class="course-content">
                                <h3 class="course-title">{{ $course->judul }}</h3>
                                <p class="course-type">{{ ucfirst($course->status ?? 'Hybrid') }}</p>
                            </div>
                        </div>
                        @endforeach
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
                        <p>Mulai tambahkan kursus baru untuk platform Anda</p>
                    </div>
                @endif
            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');
        
        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchKursus');
            const courseCards = document.querySelectorAll('.course-card');
            
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase().trim();
                    
                    courseCards.forEach(card => {
                        const title = card.querySelector('.course-title');
                        if (title) {
                            const titleText = title.textContent.toLowerCase();
                            
                            if (titleText.includes(searchTerm)) {
                                card.style.display = 'flex';
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });
                });
            }


        });

        // Modal functions
        function openModal(mode = 'add') {
            const modal = document.getElementById('modalKursus');
            const form = document.getElementById('formKursus');
            const modalTitle = document.getElementById('modalTitle');
            const btnSubmitText = document.getElementById('btnSubmitText');
            
            if (mode === 'add') {
                modalTitle.textContent = 'Tambah Kursus';
                btnSubmitText.textContent = 'Simpan';
                form.reset();
                document.getElementById('formMethod').value = 'POST';
                form.action = '{{ route("admin.pelatihan.store") }}';
                resetPreview();
            } else if (mode === 'edit') {
                // Edit mode - modal title and button will be set by editKursus function
                // Just open the modal
            }
            
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
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
            // Close any open modal first
            const modal = document.getElementById('modalKursus');
            modal.classList.remove('active');
            
            // Fetch course data via AJAX
            fetch(`/admin/pelatihan/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    const form = document.getElementById('formKursus');
                    const modalTitle = document.getElementById('modalTitle');
                    const btnSubmitText = document.getElementById('btnSubmitText');
                    
                    // Reset form first
                    form.reset();
                    
                    modalTitle.textContent = 'Edit Kursus';
                    btnSubmitText.textContent = 'Update';
                    
                    // Set form method to PUT
                    document.getElementById('formMethod').value = 'PUT';
                    form.action = `/admin/pelatihan/${id}`;
                    
                    // Fill form fields
                    document.getElementById('kursusId').value = data.id;
                    document.getElementById('judul').value = data.judul;
                    document.getElementById('kategori').value = data.kategori;
                    document.getElementById('tipe_kursus').value = data.tipe_kursus || 'online';
                    document.getElementById('deskripsi').value = data.deskripsi || '';
                    document.getElementById('pengajar_id').value = data.user_id || '{{ auth()->id() }}';
                    document.getElementById('durasi').value = data.durasi || '';
                    document.getElementById('harga').value = data.harga;
                    
                    if (data.thumbnail) {
                        document.getElementById('previewImage').src = '{{ asset("") }}' + data.thumbnail;
                        document.getElementById('uploadArea').style.display = 'none';
                        document.getElementById('previewContainer').style.display = 'block';
                    } else {
                        document.getElementById('uploadArea').style.display = 'flex';
                        document.getElementById('previewContainer').style.display = 'none';
                    }
                    
                    // Open modal with edit mode
                    setTimeout(() => {
                        openModal('edit');
                    }, 100);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Gagal memuat data kursus', 'error');
                });
        }

        function deleteKursus(id) {
            if (confirm('Apakah Anda yakin ingin menghapus kursus ini?')) {
                fetch(`/admin/pelatihan/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Kursus berhasil dihapus', 'success');
                        location.reload();
                    } else {
                        showToast('Gagal menghapus kursus', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan saat menghapus kursus', 'error');
                });
            }
        }

        // Close modal on overlay click
        document.getElementById('modalKursus')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        // Toast Notification Function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast-notification ${type}`;
            
            const icon = type === 'success' 
                ? '<svg class="toast-icon success" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>'
                : '<svg class="toast-icon error" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>';
            
            const title = type === 'success' ? 'Berhasil!' : 'Error!';
            
            toast.innerHTML = `
                ${icon}
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="this.parentElement.remove()">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            `;
            
            document.body.appendChild(toast);
            
            // Auto dismiss after 5 seconds
            setTimeout(() => {
                toast.classList.add('hiding');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 5000);
        }

        // Show toast on page load if there are messages
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                showToast('{{ $error }}', 'error');
            @endforeach
        @endif
    </script>
@endpush
