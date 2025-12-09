<!DOCTYPE html>
<html>

<head>
    
    <meta charset="UTF-8">
<link rel="stylesheet" href="{{ public_path('css/peserta/sertifikat-template.css') }}">
</head>

<body>
    <div class="cert">
        <div class="logo">
            <img src="{{ public_path('template/img/icon-logo.png') }}">
            <span>Algorify</span>
        </div>
        <div class="title">Sertifikat Penyelesaian</div>
        <div class="subtitle">Certificate of Completion</div>
        <div class="label">Diberikan kepada</div>
        <div class="name">{{ $nama }}</div>
        <div class="course-label">Telah berhasil menyelesaikan pelatihan</div>
        <div class="course">{{ $kursus }}</div>
        <div class="desc">dengan menunjukkan dedikasi, pemahaman mendalam, dan keterampilan praktis dalam bidang
            {{ strtolower($kursus) }}.</div>
        <table class="info-table">
            <tr>
                <td>
                    <div class="info-box"><span class="lbl">Tanggal Selesai</span><span
                            class="val">{{ $tanggal }}</span></div>
                </td>
                <td>
                    <div class="info-box"><span class="lbl">Nilai Akhir</span><span
                            class="val score">{{ $nilai }}</span></div>
                </td>
            </tr>
        </table>
        <div class="footer">
            <table>
                <tr>
                    <td>
                        <div class="qr"></div>
                        <div class="qr-text">Scan untuk verifikasi<br>{{ $kode }}</div>
                    </td>
                    <td>
                        <div class="badge"></div>
                        <div class="badge-text">Sertifikat Resmi</div>
                    </td>
                    <td>
                        <div class="dir">Anton Ahim</div>
                        <div class="dir-title">Direktur Algorify</div>
                        <div class="dir-loc">Jakarta, Indonesia</div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="verify">Sertifikat ini dapat diverifikasi di <a href="#">algorify.com/verify</a></div>
    </div>
</body>

</html>
