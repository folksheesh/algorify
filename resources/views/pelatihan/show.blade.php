@extends('layouts.template')

@section('title', isset($enrollment->kursus->judul) ? $enrollment->kursus->judul : 'Pelatihan')

@section('content')
    <div class="container py-6">
    <div class="row">
        <div class="col-12">
            <a href="{{ route('pelatihan.index') }}" class="btn btn-sm btn-outline-secondary mb-3">&larr; Kembali ke Pelatihan Saya</a>

            @if(!empty($dbError) && $dbError)
                <div class="alert alert-warning">Terjadi masalah koneksi database. Detil pelatihan tidak dapat ditampilkan saat ini.</div>
            @elseif(empty($enrollment))
                <div class="alert alert-info">Detil pelatihan tidak ditemukan.</div>
            @else
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">{{ $enrollment->kursus->judul ?? 'Judul tidak tersedia' }}</h2>
                        <p class="text-muted">Kategori: {{ strtoupper(str_replace('_', ' ', $enrollment->kursus->kategori ?? 'N/A')) }}</p>

                        @if(!empty($enrollment->kursus->thumbnail))
                            <img src="{{ asset('storage/' . $enrollment->kursus->thumbnail) }}" alt="thumbnail" class="img-fluid mb-3" style="max-height:300px; object-fit:cover;">
                        @endif

                        <p>{{ $enrollment->kursus->deskripsi ?? 'Deskripsi tidak tersedia.' }}</p>

                        <ul class="list-group mt-4">
                            <li class="list-group-item">Status pendaftaran: {{ $enrollment->status ?? 'N/A' }}</li>
                            <li class="list-group-item">Tanggal daftar: {{ optional($enrollment->tanggal_daftar)->toDateString() ?? 'N/A' }}</li>
                            <li class="list-group-item">Progress: {{ $enrollment->progress ?? '0%' }}</li>
                            <li class="list-group-item">Nilai akhir: {{ $enrollment->nilai_akhir ?? '-' }}</li>
                        </ul>

                        <div class="mt-3">
                            <a href="{{ route('kursus.show', $enrollment->kursus->id ?? '#') }}" class="btn btn-primary">Lihat halaman kursus</a>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
