/* ========================================
   PENGAJAR MANAGEMENT JAVASCRIPT
   Script untuk halaman Data Pengajar Admin
   ======================================== */

// ========================================
// GLOBAL VARIABLES
// ========================================

let pengajarData = [];  // Array untuk menyimpan semua data pengajar
let deleteId = null;    // ID pengajar yang akan dihapus

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
        input.addEventListener('input', function() {
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
 * Load data pengajar dari server saat halaman dimuat
 */
document.addEventListener('DOMContentLoaded', function() {
    loadPengajarData();
    
    // Setup auto-clear error saat user mulai mengetik
    clearFieldError('formName', 'nameError');
    clearFieldError('formEmail', 'emailError');
    clearFieldError('formPhone', 'phoneError');
    clearFieldError('formPassword', 'passwordError');
    clearFieldError('formPasswordConfirm', 'passwordError');
});

/**
 * Fetch data pengajar dari API
 */
function loadPengajarData() {
    fetch(apiRoutes.getData)
        .then(response => response.json())
        .then(data => {
            // Urutkan data berdasarkan ID dari kecil ke besar
            pengajarData = data.sort((a, b) => a.id - b.id);
            renderTable(pengajarData);
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Gagal Memuat Data', 'Tidak dapat memuat data pengajar. Silakan refresh halaman.', 'error');
        });
}

/**
 * Render tabel dengan data pengajar
 * @param {Array} data - Array data pengajar yang akan ditampilkan
 */
function renderTable(data) {
    const tbody = document.getElementById('pengajarTableBody');
    
    // Jika tidak ada data
    if (data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" style="text-align: center; padding: 2rem; color: #94A3B8;">
                    Tidak ada data pengajar
                </td>
            </tr>
        `;
        return;
    }

    // Render setiap baris data
    tbody.innerHTML = data.map((item, index) => {
        // Ambil nama kursus (max 2, sisanya ...)
        const kursusNames = item.kursus && item.kursus.length > 0 
            ? item.kursus.map(k => k.judul).slice(0, 2).join(', ') + (item.kursus.length > 2 ? '...' : '')
            : '-';
        const jumlahKelas = item.kursus_count || 0;
        const totalSiswa = item.total_siswa || 0;
        const status = item.status || 'active';
        const statusDisplay = status === 'active' ? 'Aktif' : 'Nonaktif';

        return `
        <tr onclick="showDetail(${item.id})">
            <td>${String(item.id).padStart(3, '0')}</td>
            <td>${item.name}</td>
            <td>${item.email}</td>
            <td>${kursusNames}</td>
            <td>
                <span class="status-badge ${status}">${statusDisplay}</span>
            </td>
            <td>${jumlahKelas}</td>
            <td>${totalSiswa}</td>
            <td onclick="event.stopPropagation()">
                <div class="action-buttons">
                    <button class="btn-action btn-edit" onclick="openEditModal(${item.id})" title="Edit">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                        </svg>
                    </button>
                    <button class="btn-action btn-delete" onclick="openDeleteModal(${item.id})" title="Hapus">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
    `;
    }).join('');
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
document.getElementById('searchInput').addEventListener('input', function(e) {
    filterData();
});

/**
 * Event listener untuk status filter dropdown
 */
document.getElementById('statusFilter').addEventListener('change', function(e) {
    filterData();
});

/**
 * Filter data berdasarkan search term dan status
 */
function filterData() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;

    const filtered = pengajarData.filter(item => {
        // Search di nama, email, dan nama kursus
        const nameMatch = item.name.toLowerCase().includes(searchTerm);
        const emailMatch = item.email.toLowerCase().includes(searchTerm);
        
        // Search di nama kursus
        let kursusMatch = false;
        if (item.kursus && item.kursus.length > 0) {
            kursusMatch = item.kursus.some(k => k.judul.toLowerCase().includes(searchTerm));
        }
        
        const matchSearch = nameMatch || emailMatch || kursusMatch;
        const matchStatus = !statusFilter || (item.status || 'active') === statusFilter;
        return matchSearch && matchStatus;
    });

    // Urutkan hasil filter berdasarkan ID dari kecil ke besar
    const sortedFiltered = filtered.sort((a, b) => a.id - b.id);

    renderTable(sortedFiltered);
}

// ========================================
// MODAL FUNCTIONS - DETAIL
// ========================================

/**
 * Menampilkan modal detail pengajar (readonly)
 * @param {number} id - ID pengajar yang akan ditampilkan
 */
function showDetail(id) {
    const pengajar = pengajarData.find(p => p.id === id);
    if (!pengajar) return;

    const kursusNames = pengajar.kursus && pengajar.kursus.length > 0 
        ? pengajar.kursus.map(k => k.judul).join(', ')
        : 'Belum ada kursus';

    // Populate form fields
    document.getElementById('detailName').value = pengajar.name;
    document.getElementById('detailEmail').value = pengajar.email;
    document.getElementById('detailPhone').value = pengajar.phone || '-';
    document.getElementById('detailAddress').value = pengajar.address || '-';
    document.getElementById('detailTanggalLahir').value = pengajar.tanggal_lahir || '';
    document.getElementById('detailJenisKelamin').value = pengajar.jenis_kelamin === 'L' ? 'Laki-laki' : pengajar.jenis_kelamin === 'P' ? 'Perempuan' : '-';
    document.getElementById('detailKeahlian').value = pengajar.keahlian || '-';
    document.getElementById('detailPengalaman').value = pengajar.pengalaman || '-';
    
    // Sertifikasi
    const sertifikasiContainer = document.getElementById('detailSertifikasiContainer');
    if (pengajar.sertifikasi) {
        const fileName = pengajar.sertifikasi.split('/').pop();
        sertifikasiContainer.innerHTML = `<a href="/storage/${pengajar.sertifikasi}" target="_blank" style="color: #5D3FFF; text-decoration: none; font-size: 0.875rem;">📎 ${fileName}</a>`;
    } else {
        sertifikasiContainer.innerHTML = '<span style="color: #94A3B8; font-size: 0.875rem;">Tidak ada sertifikasi</span>';
    }
    
    document.getElementById('detailKursus').value = kursusNames;
    document.getElementById('detailJumlahKelas').value = (pengajar.kursus_count || 0) + ' Kelas';
    document.getElementById('detailTotalSiswa').value = (pengajar.total_siswa || 0) + ' Siswa';
    const statusDisplay = (pengajar.status || 'active') === 'active' ? 'Aktif' : (pengajar.status === 'inactive' ? 'Nonaktif' : 'Ditangguhkan');
    document.getElementById('detailStatus').value = statusDisplay;
    document.getElementById('detailDate').value = pengajar.created_at ? pengajar.created_at.split('T')[0] : '';

    // Show modal
    document.getElementById('detailModal').classList.add('active');
}

// ========================================
// MODAL FUNCTIONS - ADD
// ========================================

/**
 * Membuka modal untuk tambah pengajar baru
 */
function openAddModal() {
    // Set judul modal
    document.getElementById('formModalTitle').textContent = 'Tambah Pengajar Baru';
    document.getElementById('formModalDesc').textContent = 'Lengkapi form di bawah untuk menambah pengajar';
    
    // Reset form
    document.getElementById('pengajarForm').reset();
    document.getElementById('pengajarId').value = '';
    
    // Password required untuk tambah data
    document.getElementById('formPassword').required = true;
    document.getElementById('formPasswordConfirm').required = true;
    document.getElementById('passwordLabel').innerHTML = 'Password *';
    document.getElementById('confirmPasswordLabel').innerHTML = 'Konfirmasi Password *';
    document.getElementById('passwordHint').style.display = 'none';
    
    // Show password fields
    document.getElementById('passwordGroup').style.display = 'block';
    document.getElementById('confirmPasswordGroup').style.display = 'block';
    document.getElementById('currentSertifikasi').style.display = 'none';
    
    // Clear all errors
    clearAllErrors();
    
    // Reset upload area
    document.getElementById('filePreview').classList.remove('active');
    document.getElementById('uploadArea').style.display = 'block';
    document.getElementById('formSertifikasi').value = '';
    
    // Show modal
    document.getElementById('formModal').classList.add('active');
}

// ========================================
// MODAL FUNCTIONS - EDIT
// ========================================

/**
 * Membuka modal untuk edit data pengajar
 * @param {number} id - ID pengajar yang akan diedit
 */
function openEditModal(id) {
    const pengajar = pengajarData.find(p => p.id === id);
    if (!pengajar) return;

    // Set judul modal
    document.getElementById('formModalTitle').textContent = 'Edit Data Pengajar';
    document.getElementById('formModalDesc').textContent = 'Perbarui informasi pengajar di bawah ini';
    
    // Populate form dengan data yang ada
    document.getElementById('pengajarId').value = pengajar.id;
    document.getElementById('formName').value = pengajar.name;
    document.getElementById('formEmail').value = pengajar.email;
    document.getElementById('formPhone').value = pengajar.phone || '';
    document.getElementById('formAddress').value = pengajar.address || '';
    document.getElementById('formTanggalLahir').value = pengajar.tanggal_lahir || '';
    document.getElementById('formJenisKelamin').value = pengajar.jenis_kelamin || '';
    document.getElementById('formStatus').value = pengajar.status || 'active';
    document.getElementById('formKeahlian').value = pengajar.keahlian || '';
    document.getElementById('formPengalaman').value = pengajar.pengalaman || '';
    document.getElementById('formPassword').value = '';
    document.getElementById('formPasswordConfirm').value = '';
    
    // Password optional untuk edit
    document.getElementById('formPassword').required = false;
    document.getElementById('formPasswordConfirm').required = false;
    
    // Sembunyikan field password saat edit
    document.getElementById('passwordGroup').style.display = 'none';
    document.getElementById('confirmPasswordGroup').style.display = 'none';
    
    // Reset upload area
    document.getElementById('filePreview').classList.remove('active');
    document.getElementById('uploadArea').style.display = 'block';
    document.getElementById('formSertifikasi').value = '';
    
    // Show current sertifikasi if exists
    if (pengajar.sertifikasi) {
        const fileName = pengajar.sertifikasi.split('/').pop();
        document.getElementById('sertifikasiFileName').textContent = fileName;
        document.getElementById('currentSertifikasi').style.display = 'block';
    } else {
        document.getElementById('currentSertifikasi').style.display = 'none';
    }
    
    // Show modal
    document.getElementById('formModal').classList.add('active');
}

// ========================================
// FORM SUBMISSION
// ========================================

/**
 * Handle form submit untuk tambah/edit pengajar
 */
document.getElementById('pengajarForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Clear all previous errors
    clearAllErrors();
    
    const id = document.getElementById('pengajarId').value;
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
    formData.append('keahlian', document.getElementById('formKeahlian').value);
    formData.append('pengalaman', document.getElementById('formPengalaman').value);
    
    if (password) {
        formData.append('password', password);
    }
    
    const sertifikasiFile = document.getElementById('formSertifikasi').files[0];
    if (sertifikasiFile) {
        formData.append('sertifikasi', sertifikasiFile);
    }
    
    formData.append('_token', csrfToken);
    if (id) {
        formData.append('_method', 'PUT');
    }

    // Tentukan URL berdasarkan mode (create/update)
    const url = id ? `/admin/pengajar/${id}` : apiRoutes.store;

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
            loadPengajarData();
        } else {
            // Handle validation errors dari server
            if (data.errors) {
                // Mapping field errors
                const errorMapping = {
                    'name': { errorId: 'nameError', inputId: 'formName' },
                    'email': { errorId: 'emailError', inputId: 'formEmail' },
                    'password': { errorId: 'passwordError', inputId: 'formPassword' },
                    'phone': { errorId: 'phoneError', inputId: 'formPhone' },
                    'sertifikasi': { errorId: 'fileError', inputId: 'formSertifikasi' }
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
        console.error('Error:', error);
        showToast('Gagal Menyimpan', 'Tidak dapat menyimpan data. Silakan coba lagi.', 'error');
    });
});

// ========================================
// MODAL FUNCTIONS - DELETE
// ========================================

/**
 * Membuka modal konfirmasi hapus
 * @param {number} id - ID pengajar yang akan dihapus
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

    fetch(`/admin/pengajar/${deleteId}`, {
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
            loadPengajarData();
            deleteId = null;
        } else {
            closeModal('deleteModal');
            showToast('Gagal Menghapus', data.message || 'Tidak dapat menghapus data pengajar', 'warning');
        }
    })
    .catch(error => {
        console.error('Error:', error);
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
    modal.addEventListener('click', function(e) {
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
fileInput.addEventListener('change', function(e) {
    handleFile(this.files[0]);
});

/**
 * Handle drag over event
 */
uploadArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('dragover');
});

/**
 * Handle drag leave event
 */
uploadArea.addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
});

/**
 * Handle drop event
 */
uploadArea.addEventListener('drop', function(e) {
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
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}
