@extends('layouts.template')

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')

        <main class="main-content p-6">
            <header class="mb-6">
                <h1 class="text-2xl font-semibold">Sertifikat Saya</h1>
                <p class="text-sm text-gray-600">Dapatkan sertifikat untuk setiap pelatihan yang Anda selesaikan</p>
            </header>

            @if(!empty($dbError) && $dbError)
                <div class="mb-4 p-3 rounded bg-red-50 text-red-700">Terjadi masalah koneksi database. Beberapa data mungkin tidak tersedia.</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="col-span-1">
                    @if(isset($sertifikats) && $sertifikats->isEmpty())
                        <div class="bg-white border rounded-lg p-6 text-gray-600">Belum ada sertifikat ditemukan.</div>
                    @else
                        @foreach($sertifikats ?? collect() as $s)
                            <div class="bg-white rounded-lg shadow-sm border mb-6 overflow-hidden">
                                <div class="p-6">
                                    <div class="flex items-center justify-center mb-4" style="height:120px;background:linear-gradient(180deg,#f3f5ff 0%, #fff 100%);">
                                        <div class="text-center">
                                            <svg width="56" height="56" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 2C13.1046 2 14 2.89543 14 4C14 5.10457 13.1046 6 12 6C10.8954 6 10 5.10457 10 4C10 2.89543 10.8954 2 12 2Z" stroke="#2563EB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M6 20C6 16 9 14 12 14C15 14 18 16 18 20" stroke="#2563EB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <div class="inline-block mt-2 text-xs bg-blue-600 text-white px-3 py-1 rounded">Sertifikat Tersedia</div>
                                        </div>
                                    </div>

                                    <h3 class="text-lg font-medium mb-1">{{ $s->kursus->judul ?? '—' }}</h3>
                                    <div class="text-sm text-gray-500 mb-3">Oleh {{ $s->kursus->pengajar->name ?? $s->user->name ?? '—' }}</div>

                                    <div class="text-sm text-gray-600 mb-3">
                                        <div>Tanggal Selesai: <span class="font-semibold text-gray-800">{{ optional($s->tanggal_terbit)->format('d F Y') ?? '-' }}</span></div>
                                        <div>Nilai Akhir: <span class="font-bold text-green-600">{{ $s->nilai_akhir ?? ($s->nilai ?? '-') }}/100</span></div>
                                    </div>

                                    <div class="mt-4">
                                        <a href="{{ route('sertifikat.show', $s->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded shadow"> 
                                            <svg class="mr-2" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 3v12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 11l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            Download Sertifikat (PDF)
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="col-span-2">
                    <div class="bg-white border rounded-lg p-6 shadow-sm">
                        <h3 class="font-semibold mb-3">Cara Mendapatkan Sertifikat</h3>
                        <ul class="list-disc list-inside text-gray-700">
                            <li class="mb-2">Selesaikan semua modul dalam pelatihan (100%).</li>
                            <li class="mb-2">Lulus quiz final dengan nilai minimal 70.</li>
                            <li>Sertifikat dapat didownload dalam format PDF.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <div style="text-align:center;margin:18px 0;color:#6b7280;font-size:14px">
                    Jika sudah punya sertifikat, <a href="{{ route('sertifikat.verify.form') }}" style="color:#5b21b6;font-weight:700;text-decoration:none">verifikasi di sini!</a>
                </div>

                @if(isset($sertifikats))
                    {{ $sertifikats->links() }}
                @endif
            </div>
        </main>
    </div>
@endsection
