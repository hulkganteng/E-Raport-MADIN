<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapot Kelas {{ $kelas->nama_kelas }}</title>
    <style>
        @font-face {
            font-family: 'ArabicNaskh';
            font-style: normal;
            font-weight: 400;
            src: url('file:///{{ str_replace("\\", "/", resource_path("fonts/arabtype.ttf")) }}') format('truetype');
        }
        @font-face {
            font-family: 'ArabicNaskh';
            font-style: normal;
            font-weight: 700;
            src: url('file:///{{ str_replace("\\", "/", resource_path("fonts/tradbdo.ttf")) }}') format('truetype');
        }
        @page {
            size: A4;
            margin: 1cm;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.3;
        }
        .page-break {
            page-break-after: always;
        }
        /* Reusing styles from print.blade.php */
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
            position: relative;
        }
        .logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 80px;
            height: 80px;
        }
        .header-text {
            padding-top: 5px;
        }
        .header h3 {
            margin: 2px 0;
            font-size: 14pt;
            font-weight: bold;
        }
        .header p {
            margin: 2px 0;
            font-size: 9pt;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 13pt;
            margin: 15px 0;
            text-decoration: underline;
        }
        .student-info {
            margin-bottom: 15px;
            font-size: 10pt;
        }
        .student-info table {
            width: 100%;
        }
        .student-info td {
            padding: 2px 0;
        }
        .student-info .left {
            width: 50%;
            vertical-align: top;
        }
        .student-info .right {
            width: 50%;
            vertical-align: top;
            padding-left: 30px;
        }
        .report-page {
            page-break-inside: avoid;
        }
        .split-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin-bottom: 10px;
        }
        .split-table td {
            vertical-align: top;
            padding: 0;
        }
        .panel-left, .panel-right {
            width: 50%;
        }
        table.nilai {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            font-size: 9pt;
        }
        table.nilai th, table.nilai td {
            border: 1px solid #111;
            padding: 2px 4px;
            vertical-align: middle;
        }
        table.nilai th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        table.nilai .category {
            background-color: #e8e8e8;
            font-weight: bold;
            text-align: center;
        }
        table.nilai .arabic {
            text-align: center;
            font-size: 12pt;
            font-family: 'ArabicNaskh', 'Traditional Arabic', serif;
            line-height: 1.15;
        }
        table.nilai .arabic-words {
            font-size: 10pt;
            line-height: 1.05;
        }
        .arabic-inline {
            font-family: 'ArabicNaskh', 'Traditional Arabic', serif;
            display: inline-block;
            font-size: 12pt;
            line-height: 1.15;
        }
        .arabic-right {
            text-align: right;
        }
        .kepribadian {
            margin-top: 0;
            font-size: 9pt;
        }
        .kepribadian table {
            width: 100%;
            border-collapse: collapse;
        }
        .kepribadian th, .kepribadian td {
            border: 1px solid #111;
            padding: 2px 4px;
        }
        .arabic-head {
            font-family: 'ArabicNaskh', 'Traditional Arabic', serif;
            font-size: 13pt;
            font-weight: bold;
            padding-top: 3px;
        }
        .catatan {
            margin-top: 12px;
            border: 1px solid #000;
            padding: 0;
            min-height: 68px;
            font-size: 10pt;
        }
        .catatan-title {
            text-align: center;
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding: 2px 0;
        }
        .catatan-body {
            text-align: center;
            font-style: italic;
            padding: 8px 10px 20px;
        }
        .signature {
            margin-top: 10px;
            font-size: 10pt;
        }
        .signature table {
            width: 100%;
        }
        .signature td {
            text-align: center;
            vertical-align: top;
            padding: 5px;
        }
        .signature .name {
            margin-top: 58px;
            font-weight: bold;
            text-decoration: underline;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
    </style>
</head>
<body>
    @for($copy = 1; $copy <= ($copies ?? 1); $copy++)
        @foreach($data as $item)
            @php
                $santri = $item['santri'];
                $rekap = $item['rekap']; // Can be null
                $absensi = $item['absensi']; 
                $nilaiMapel = $item['nilaiMapel'];
                $totalSantri = $item['totalSantri'];
            @endphp
            @include('rekap.partials.report_page', [
                'santri' => $santri,
                'periode' => $periode,
                'rekap' => $rekap,
                'absensi' => $absensi,
                'nilaiMapel' => $nilaiMapel,
                'totalSantri' => $totalSantri,
                'logoSrc' => $logoSrc ?? null,
            ])

            @if(!($loop->last && $copy === ($copies ?? 1)))
            <div class="page-break"></div>
            @endif
        @endforeach
    @endfor
</body>
</html>
