<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pratinjau {{ $title }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <style>
        {!! $fontFaces !!}

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #475569;
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #0f172a;
        }

        /* Toolbar */
        .preview-toolbar {
            position: sticky;
            top: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            padding: 12px 20px;
            background: #0f172a;
            color: #fff;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.35);
        }
        .preview-toolbar .meta { min-width: 0; }
        .preview-toolbar .meta h1 {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .preview-toolbar .meta p {
            margin: 2px 0 0;
            font-size: 12px;
            color: #94a3b8;
        }
        .preview-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 16px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid transparent;
            cursor: pointer;
            transition: background .15s, border-color .15s;
        }
        .btn svg { width: 16px; height: 16px; }
        .btn-ghost { background: transparent; color: #e2e8f0; border-color: #334155; }
        .btn-ghost:hover { background: #1e293b; }
        .btn-light { background: #1e293b; color: #e2e8f0; }
        .btn-light:hover { background: #334155; }
        .btn-primary { background: #0d9488; color: #fff; }
        .btn-primary:hover { background: #0f766e; }

        /* Page workspace */
        .preview-stage {
            padding: 28px 16px 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 24px;
        }

        .a4-page {
            background: #fff;
            width: 21cm;
            min-height: 29.7cm;
            padding: 1cm;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.35);
            border-radius: 2px;
        }

        @media (max-width: 900px) {
            .a4-page {
                width: 100%;
                min-height: auto;
                padding: 14px;
            }
        }

        /* ===== Report styles (mirrors rekap/print.blade.php) ===== */
        .a4-page { font-family: 'Arial', sans-serif; font-size: 11pt; line-height: 1.3; }
        .header { text-align: center; border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 15px; position: relative; }
        .logo { position: absolute; left: 0; top: 0; width: 80px; height: 80px; }
        .header-text { padding-top: 5px; }
        .header h3 { margin: 2px 0; font-size: 14pt; font-weight: bold; }
        .header p { margin: 2px 0; font-size: 9pt; }
        .title { text-align: center; font-weight: bold; font-size: 13pt; margin: 15px 0; text-decoration: underline; }
        .student-info { margin-bottom: 15px; font-size: 10pt; }
        .student-info table { width: 100%; }
        .student-info td { padding: 2px 0; }
        .student-info .left { width: 50%; vertical-align: top; }
        .student-info .right { width: 50%; vertical-align: top; padding-left: 30px; }
        .report-page { page-break-inside: avoid; }
        .split-table { width: 100%; border-collapse: separate; border-spacing: 8px 0; margin-bottom: 10px; }
        .split-table td { vertical-align: top; padding: 0; }
        .panel-left, .panel-right { width: 50%; }
        table.nilai { width: 100%; border-collapse: collapse; margin-bottom: 0; font-size: 9pt; }
        table.nilai th, table.nilai td { border: 1px solid #111; padding: 2px 4px; vertical-align: middle; }
        table.nilai th { background-color: #f0f0f0; font-weight: bold; text-align: center; }
        table.nilai .category { background-color: #e8e8e8; font-weight: bold; text-align: center; }
        table.nilai .arabic { text-align: center; font-size: 12pt; font-family: 'ArabicNaskh', 'Traditional Arabic', serif; line-height: 1.15; }
        table.nilai .arabic-words { font-size: 10pt; line-height: 1.05; }
        .arabic-inline { font-family: 'ArabicNaskh', 'Traditional Arabic', serif; display: inline-block; font-size: 12pt; line-height: 1.15; }
        .arabic-right { text-align: right; }
        .kepribadian { margin-top: 0; font-size: 9pt; }
        .kepribadian table { width: 100%; border-collapse: collapse; }
        .kepribadian th, .kepribadian td { border: 1px solid #111; padding: 2px 4px; }
        .arabic-head { font-family: 'ArabicNaskh', 'Traditional Arabic', serif; font-size: 13pt; font-weight: bold; padding-top: 3px; }
        .catatan { margin-top: 12px; border: 1px solid #000; padding: 0; min-height: 68px; font-size: 10pt; }
        .catatan-title { text-align: center; font-weight: bold; border-bottom: 1px solid #000; padding: 2px 0; }
        .catatan-body { text-align: center; font-style: italic; padding: 8px 10px 20px; }
        .signature { margin-top: 10px; font-size: 10pt; }
        .signature table { width: 100%; }
        .signature td { text-align: center; vertical-align: top; padding: 5px; }
        .signature .name { margin-top: 58px; font-weight: bold; text-decoration: underline; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }

        @media print {
            body { background: #fff; }
            .preview-toolbar { display: none; }
            .preview-stage { padding: 0; gap: 0; }
            .a4-page { width: auto; min-height: auto; box-shadow: none; border-radius: 0; padding: 0; page-break-after: always; }
            .a4-page:last-child { page-break-after: auto; }
        }
    </style>
</head>
<body>
    <div class="preview-toolbar">
        <div class="meta">
            <h1>{{ $title }}</h1>
            <p>{{ $subtitle }}</p>
        </div>
        <div class="preview-actions">
            <a href="{{ $backUrl }}" class="btn btn-ghost">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <button type="button" onclick="window.print()" class="btn btn-light">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Browser
            </button>
            <a href="{{ $downloadUrl }}" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Download PDF
            </a>
        </div>
    </div>

    <div class="preview-stage">
        @foreach($pages as $page)
            <div class="a4-page">
                @include('rekap.partials.report_page', [
                    'santri' => $page['santri'],
                    'periode' => $page['periode'],
                    'rekap' => $page['rekap'],
                    'absensi' => $page['absensi'],
                    'nilaiMapel' => $page['nilaiMapel'],
                    'totalSantri' => $page['totalSantri'],
                    'logoSrc' => $logoSrc,
                ])
            </div>
        @endforeach
    </div>
</body>
</html>
