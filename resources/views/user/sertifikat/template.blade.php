<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat - {{ $certificate->nomor_sertifikat }}</title>
    <style>
        @page {
            margin: 0;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Times New Roman', serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px;
            margin: 0;
        }
        .certificate-container {
            background: white;
            padding: 60px 80px;
            border: 20px solid #5D3FFF;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            position: relative;
            min-height: 700px;
        }
        .certificate-border {
            border: 3px solid #5D3FFF;
            padding: 40px;
            position: relative;
        }
        .cert-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .cert-logo {
            font-size: 48px;
            color: #5D3FFF;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .cert-type {
            font-size: 32px;
            color: #1E293B;
            font-weight: bold;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .cert-subtitle {
            font-size: 18px;
            color: #64748B;
            font-style: italic;
        }
        .cert-body {
            text-align: center;
            margin: 50px 0;
        }
        .cert-text {
            font-size: 18px;
            color: #475569;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        .cert-recipient {
            font-size: 42px;
            color: #5D3FFF;
            font-weight: bold;
            margin: 30px 0;
            text-decoration: underline;
            text-decoration-color: #5D3FFF;
            text-decoration-thickness: 3px;
            text-underline-offset: 8px;
        }
        .cert-course {
            font-size: 28px;
            color: #1E293B;
            font-weight: bold;
            margin: 30px 0;
            font-style: italic;
        }
        .cert-footer {
            display: table;
            width: 100%;
            margin-top: 60px;
        }
        .cert-signature {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 20px;
        }
        .signature-line {
            border-top: 2px solid #1E293B;
            margin-top: 80px;
            padding-top: 10px;
            font-size: 16px;
            font-weight: bold;
            color: #1E293B;
        }
        .signature-title {
            font-size: 14px;
            color: #64748B;
            margin-top: 5px;
        }
        .signature-img {
            max-width: 150px;
            max-height: 60px;
            margin-bottom: 10px;
        }
        .cert-number {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 12px;
            color: #64748B;
        }
        .cert-date {
            font-size: 16px;
            color: #475569;
            margin-top: 30px;
        }
        .decorative-line {
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #5D3FFF, transparent);
            margin: 20px auto;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-border">
            <!-- Header -->
            <div class="cert-header">
                <div class="cert-logo">Algorify</div>
                <div class="cert-type">Sertifikat</div>
                <div class="cert-subtitle">Certificate of Completion</div>
                <div class="decorative-line"></div>
            </div>

            <!-- Body -->
            <div class="cert-body">
                <div class="cert-text">
                    Dengan bangga kami berikan sertifikat ini kepada
                </div>

                <div class="cert-recipient">
                    {{ $user->name }}
                </div>

                <div class="cert-text">
                    Telah berhasil menyelesaikan pelatihan
                </div>

                <div class="cert-course">
                    "{{ $kursus->judul }}"
                </div>

                <div class="cert-date">
                    Diberikan pada tanggal {{ $tanggal_terbit }}
                </div>
            </div>

            <!-- Footer / Signatures -->
            <div class="cert-footer">
                <div class="cert-signature">
                    @if($signature && file_exists($signature))
                        <img src="{{ $signature }}" alt="Signature" class="signature-img">
                    @endif
                    <div class="signature-line">
                        {{ $pengajar->name ?? 'Pengajar' }}
                    </div>
                    <div class="signature-title">Pengajar</div>
                </div>
                
                <div class="cert-signature">
                    @if($signature && file_exists($signature))
                        <img src="{{ $signature }}" alt="Signature" class="signature-img">
                    @endif
                    <div class="signature-line">
                        Direktur Algorify
                    </div>
                    <div class="signature-title">Direktur Pelatihan</div>
                </div>
            </div>

            <!-- Certificate Number -->
            <div class="cert-number">
                No. Sertifikat: {{ $certificate->nomor_sertifikat }}
            </div>
        </div>
    </div>
</body>
</html>
