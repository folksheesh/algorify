<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
@page { margin: 0; padding: 0; size: auto; }
html, body { margin: 0; padding: 0; height: auto; }
body { 
    font-family: Arial, sans-serif; 
    background: #EBEBFF; 
    padding: 20px; 
}
.cert {
    background: #fff;
    border: 3px solid #6366F1;
    border-radius: 8px;
    padding: 30px 40px;
    text-align: center;
    max-width: 900px;
    margin: 0 auto;
}
.logo img { width: 35px; height: 35px; vertical-align: middle; }
.logo span { font-size: 20px; font-weight: bold; color: #6366F1; vertical-align: middle; margin-left: 8px; }
.title { font-size: 28px; color: #6366F1; font-style: italic; font-weight: 300; margin: 15px 0 5px; }
.subtitle { font-size: 10px; color: #999; margin-bottom: 20px; }
.label { font-size: 11px; color: #666; font-style: italic; }
.name { font-size: 24px; font-weight: bold; color: #222; margin: 8px 0 15px; }
.course-label { font-size: 11px; color: #666; }
.course { font-size: 18px; font-weight: bold; color: #6366F1; margin: 8px 0 10px; }
.desc { font-size: 10px; color: #999; margin-bottom: 20px; }
.info-table { margin: 0 auto 20px; }
.info-table td { padding: 0 15px; }
.info-box { background: #F5F5FF; padding: 10px 20px; border-radius: 5px; text-align: center; }
.info-box .lbl { font-size: 9px; color: #999; display: block; }
.info-box .val { font-size: 13px; font-weight: bold; color: #333; }
.info-box .val.score { color: #6366F1; }
.footer { border-top: 1px solid #eee; padding-top: 15px; margin-top: 10px; }
.footer table { width: 100%; }
.footer td { width: 33%; text-align: center; vertical-align: top; }
.qr { width: 50px; height: 50px; border: 1px solid #ddd; background: #fafafa; margin: 0 auto 5px; }
.qr-text { font-size: 8px; color: #999; }
.badge { width: 40px; height: 40px; border: 2px solid #6366F1; border-radius: 50%; background: #F0F0FF; margin: 0 auto 5px; }
.badge-text { font-size: 9px; color: #6366F1; }
.dir { font-size: 11px; font-weight: bold; color: #6366F1; }
.dir-title { font-size: 8px; color: #6366F1; text-decoration: underline; }
.dir-loc { font-size: 8px; color: #999; }
.verify { font-size: 8px; color: #999; margin-top: 10px; padding-top: 10px; border-top: 1px solid #eee; }
.verify a { color: #6366F1; text-decoration: none; }
</style>
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
    <div class="desc">dengan menunjukkan dedikasi, pemahaman mendalam, dan keterampilan praktis dalam bidang {{ strtolower($kursus) }}.</div>
    <table class="info-table"><tr>
        <td><div class="info-box"><span class="lbl">Tanggal Selesai</span><span class="val">{{ $tanggal }}</span></div></td>
        <td><div class="info-box"><span class="lbl">Nilai Akhir</span><span class="val score">{{ $nilai }}</span></div></td>
    </tr></table>
    <div class="footer"><table><tr>
        <td><div class="qr"></div><div class="qr-text">Scan untuk verifikasi<br>{{ $kode }}</div></td>
        <td><div class="badge"></div><div class="badge-text">Sertifikat Resmi</div></td>
        <td><div class="dir">Anton Ahim</div><div class="dir-title">Direktur Algorify</div><div class="dir-loc">Jakarta, Indonesia</div></td>
    </tr></table></div>
    <div class="verify">Sertifikat ini dapat diverifikasi di <a href="#">algorify.com/verify</a></div>
</div>
</body>
</html>
