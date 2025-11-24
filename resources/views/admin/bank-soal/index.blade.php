@extends('layouts.template')

@section('title', 'Bank Soal - Admin')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .page-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: #1E293B;
            margin: 0;
        }
        
        .page-subtitle {
            color: #64748B;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .btn-group-custom {
            display: flex;
            gap: 0.75rem;
        }

        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-outline-primary {
            background: white;
            color: #667eea;
            border: 1px solid #667eea;
        }

        .btn-outline-primary:hover {
            background: #667eea;
            color: white;
        }

        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .soal-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
            height: 100%;
        }

        .soal-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .badge {
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
        }
    </style>
@endpush

@section('content')
<div class="main-content">
    <x-sidebar />
    <div class="content-wrapper">
        <div class="page-header">
            <div>
                <h1 class="page-title">Bank Soal</h1>
                <p class="page-subtitle">Kelola koleksi soal untuk ujian dan kuis</p>
            </div>
            @hasanyrole('admin|pengajar')
            <div class="btn-group-custom">
                <a href="{{ route('admin.kategori-soal.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-edit"></i>
                    Kelola Kategori
                </a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSoal" onclick="resetForm()">
                    <i class="fas fa-plus"></i>
                    Tambah Soal
                </button>
            </div>
            @endhasanyrole
        </div>

        <!-- Filter -->
        <div class="filter-card">
            <form method="GET" action="{{ route('admin.bank-soal.index') }}" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Cari pertanyaan..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="kategori_id" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="tingkat_kesulitan" class="form-select">
                        <option value="">Semua Tingkat</option>
                        <option value="mudah" {{ request('tingkat_kesulitan') == 'mudah' ? 'selected' : '' }}>Mudah</option>
                        <option value="sedang" {{ request('tingkat_kesulitan') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="sulit" {{ request('tingkat_kesulitan') == 'sulit' ? 'selected' : '' }}>Sulit</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- List Soal -->
        <div class="row">
            @forelse($bankSoal as $soal)
            <div class="col-md-6 mb-4">
                <div class="soal-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge" style="background-color: {{ $soal->kategori->warna }}">
                                {{ $soal->kategori->nama }}
                            </span>
                            @if($soal->tingkat_kesulitan == 'mudah')
                            <span class="badge bg-success">Mudah</span>
                            @elseif($soal->tingkat_kesulitan == 'sedang')
                            <span class="badge bg-warning text-dark">Sedang</span>
                            @else
                            <span class="badge bg-danger">Sulit</span>
                            @endif
                        </div>
                        @hasanyrole('admin|pengajar')
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="editSoal({{ $soal->id }})">
                                    <i class="fas fa-edit"></i> Edit
                                </a></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteSoal({{ $soal->id }})">
                                    <i class="fas fa-trash"></i> Hapus
                                </a></li>
                            </ul>
                        </div>
                        @endhasanyrole
                    </div>
                    
                    <h6 class="mb-3">{{ $soal->pertanyaan }}</h6>
                    
                    <div class="mt-3">
                        @foreach($soal->pilihan as $index => $pilihan)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" disabled {{ $pilihan->is_correct ? 'checked' : '' }}>
                            <label class="form-check-label {{ $pilihan->is_correct ? 'text-success fw-bold' : '' }}">
                                {{ chr(65 + $index) }}. {{ $pilihan->pilihan }}
                                @if($pilihan->is_correct)
                                <i class="fas fa-check-circle text-success"></i>
                                @endif
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada soal di bank soal</p>
                    @hasanyrole('admin|pengajar')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSoal" onclick="resetForm()">
                        <i class="fas fa-plus"></i> Tambah Soal Pertama
                    </button>
                    @endhasanyrole
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $bankSoal->links() }}
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Soal -->
<div class="modal fade" id="modalSoal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Soal ke Bank</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formSoal">
                @csrf
                <input type="hidden" id="soal_id" name="soal_id">
                <input type="hidden" id="form_method" value="POST">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori_id" id="kategori_id" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tingkat Kesulitan <span class="text-danger">*</span></label>
                        <select name="tingkat_kesulitan" id="tingkat_kesulitan" class="form-select" required>
                            <option value="mudah">Mudah</option>
                            <option value="sedang" selected>Sedang</option>
                            <option value="sulit">Sulit</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Pertanyaan <span class="text-danger">*</span></label>
                        <textarea name="pertanyaan" id="pertanyaan" class="form-control" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Pilihan Jawaban <span class="text-danger">*</span></label>
                        <div id="pilihanContainer">
                            <div class="input-group mb-2">
                                <span class="input-group-text">A</span>
                                <input type="text" name="pilihan[]" class="form-control" required>
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="kunci_jawaban" value="0" required>
                                </div>
                            </div>
                            <div class="input-group mb-2">
                                <span class="input-group-text">B</span>
                                <input type="text" name="pilihan[]" class="form-control" required>
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="kunci_jawaban" value="1" required>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addPilihan()">
                            <i class="fas fa-plus"></i> Tambah Pilihan
                        </button>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('template/assets/extensions/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/assets/compiled/js/app.js') }}"></script>
<script>
// Check if Bootstrap is loaded
console.log('jQuery loaded:', typeof $ !== 'undefined');
console.log('Bootstrap loaded:', typeof bootstrap !== 'undefined');

let pilihanCount = 2;

function resetForm() {
    document.getElementById('modalTitle').textContent = 'Tambah Soal ke Bank';
    document.getElementById('formSoal').reset();
    document.getElementById('soal_id').value = '';
    document.getElementById('form_method').value = 'POST';
    pilihanCount = 2;
    resetPilihan();
}

function resetPilihan() {
    const container = document.getElementById('pilihanContainer');
    container.innerHTML = `
        <div class="input-group mb-2">
            <span class="input-group-text">A</span>
            <input type="text" name="pilihan[]" class="form-control" required>
            <div class="input-group-text">
                <input class="form-check-input mt-0" type="radio" name="kunci_jawaban" value="0" required>
            </div>
        </div>
        <div class="input-group mb-2">
            <span class="input-group-text">B</span>
            <input type="text" name="pilihan[]" class="form-control" required>
            <div class="input-group-text">
                <input class="form-check-input mt-0" type="radio" name="kunci_jawaban" value="1" required>
            </div>
        </div>
    `;
}

function addPilihan() {
    const container = document.getElementById('pilihanContainer');
    const letter = String.fromCharCode(65 + pilihanCount);
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <span class="input-group-text">${letter}</span>
        <input type="text" name="pilihan[]" class="form-control" required>
        <div class="input-group-text">
            <input class="form-check-input mt-0" type="radio" name="kunci_jawaban" value="${pilihanCount}" required>
        </div>
        <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);
    pilihanCount++;
}

document.getElementById('formSoal').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const method = document.getElementById('form_method').value;
    const soalId = document.getElementById('soal_id').value;
    
    let url = '{{ route("admin.bank-soal.store") }}';
    if (method === 'PUT') {
        url = '/admin/bank-soal/' + soalId;
        formData.append('_method', 'PUT');
    }
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#modalSoal').modal('hide');
            location.reload();
        } else {
            alert('Gagal: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
});

function editSoal(id) {
    fetch(`/admin/bank-soal/${id}/edit`, {
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const soal = data.data;
            document.getElementById('modalTitle').textContent = 'Edit Soal';
            document.getElementById('soal_id').value = soal.id;
            document.getElementById('form_method').value = 'PUT';
            document.getElementById('kategori_id').value = soal.kategori_id;
            document.getElementById('tingkat_kesulitan').value = soal.tingkat_kesulitan;
            document.getElementById('pertanyaan').value = soal.pertanyaan;
            
            const container = document.getElementById('pilihanContainer');
            container.innerHTML = '';
            pilihanCount = 0;
            
            soal.pilihan.forEach((p, index) => {
                const letter = String.fromCharCode(65 + index);
                const div = document.createElement('div');
                div.className = 'input-group mb-2';
                div.innerHTML = `
                    <span class="input-group-text">${letter}</span>
                    <input type="text" name="pilihan[]" class="form-control" value="${p.pilihan}" required>
                    <div class="input-group-text">
                        <input class="form-check-input mt-0" type="radio" name="kunci_jawaban" value="${index}" ${p.is_correct ? 'checked' : ''} required>
                    </div>
                    ${index > 1 ? '<button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>' : ''}
                `;
                container.appendChild(div);
                pilihanCount++;
            });
            
            $('#modalSoal').modal('show');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal mengambil data soal');
    });
}

function deleteSoal(id) {
    if (!confirm('Yakin ingin menghapus soal ini?')) return;
    
    fetch(`/admin/bank-soal/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal menghapus: ' + (data.message || 'Unknown error'));
        }
    });
}
</script>
@endpush
