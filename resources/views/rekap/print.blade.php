<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapot - {{ $santri->nama_lengkap }}</title>
    <style>
        @page {
            size: A4;
            margin: 1cm;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
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
        table.nilai {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10pt;
        }
        table.nilai th, table.nilai td {
            border: 1px solid #000;
            padding: 4px 6px;
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
            font-size: 9pt;
        }
        .kepribadian {
            margin-top: 10px;
            font-size: 10pt;
        }
        .kepribadian table {
            width: 100%;
            border-collapse: collapse;
        }
        .kepribadian th, .kepribadian td {
            border: 1px solid #000;
            padding: 4px 6px;
        }
        .catatan {
            margin-top: 10px;
            border: 1px solid #000;
            padding: 10px;
            min-height: 50px;
            font-size: 10pt;
        }
        .signature {
            margin-top: 30px;
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
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    @php
    function convertToArabicNumber($number) {
        $western = ['0','1','2','3','4','5','6','7','8','9'];
        $eastern = ['Uÿ','U­','U›','Uœ','U','U','UÝ','U','U"','Uc'];
        return str_replace($western, $eastern, $number);
    }

    function convertPredikatToArabic($predikat) {
        $map = ['A' => 'Oœ', 'B' => 'O"', 'C' => 'Oª', 'D' => 'O_', 'E' => 'UØU?'];
        return $map[$predikat] ?? $predikat;
    }

    function getArabicSubjectName($name) {
        $map = [
            'AKHLAK' => 'OU,OœOrU,OU,',
            'HADITS' => 'OU,O-O_USO®',
            'FIQIH' => 'OU,U?U,UØ',
            'TAUHID' => 'OU,O¦U^O-USO_',
            'TARIKH' => 'OU,O¦OOñUSOr',
            'NAHWU' => 'OU,U+O-U^',
            'SHOROF' => 'OU,OæOñU?',
            'BAHASA ARAB' => 'OU,U,O§Oc OU,O1OñO"USOc',
            'FAROIDH' => 'OU,U?OñOOÝO',
            'BMK' => 'O¦O1U,USU. U,OñOOc OU,UŸO¦OO"',
        ];
        return $map[strtoupper($name)] ?? '-';
    }
    @endphp

    @for($copy = 1; $copy <= ($copies ?? 1); $copy++)
        <!-- Header -->
        <div class="header">
            <img src="{{ public_path('logo.jpg') }}" alt="Logo" class="logo">
            <div class="header-text">
                <h3>MADRASAH DINIYAH</h3>
                <h3>PONDOK PESANTREN ASSYAFI'IYAH</h3>
                <p>321235250108</p>
                <p>JL. NONGKO KEREP RT 02 RW 01, BUNGAH, BUNGAH, GRESIK, JAWA TIMUR, 61152</p>
                <p>Email: assyafiyahbungahgresik@gmail.com</p>
            </div>
        </div>

        <!-- Title -->
        <div class="title">LAPORAN HASIL BELAJAR SANTRI</div>

        <!-- Student Info -->
        <div class="student-info">
            <table>
                <tr>
                    <td class="left">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 30%;">Nama Santri</td>
                                <td style="width: 5%;">:</td>
                                <td style="width: 65%; font-weight: bold;">{{ strtoupper($santri->nama_lengkap) }}</td>
                            </tr>
                            <tr>
                                <td>No. Induk</td>
                                <td>:</td>
                                <td>{{ $santri->nis }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td>{{ $santri->biodata->alamat ?? '-' }}</td>
                            </tr>
                        </table>
                    </td>
                    <td class="right">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 35%;">Kelas</td>
                                <td style="width: 5%;">:</td>
                                <td style="width: 60%;">{{ $santri->kelas->tingkatan }} ({{ $santri->kelas->nama_kelas }})</td>
                            </tr>
                            <tr>
                                <td>Semester</td>
                                <td>:</td>
                                <td>{{ $periode->nama_periode }}</td>
                            </tr>
                            <tr>
                                <td>Tahun Pelajaran</td>
                                <td>:</td>
                                <td>{{ $santri->kelas->tahun_ajar }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Nilai Table -->
        <table class="nilai">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 30px;">No</th>
                    <th rowspan="2" style="width: 180px;">Mata Pelajaran</th>
                    <th colspan="2">Hasil Tes</th>
                    <th colspan="2" class="arabic">OU,O_OñOªOO¦ OU,O1U,U.USOc</th>
                    <th rowspan="2" class="arabic" style="width: 120px;">OU,U.U^OO_ OU,O_OñOO3USOc</th>
                </tr>
                <tr>
                    <th style="width: 50px;">Angka</th>
                    <th style="width: 50px;">Huruf</th>
                    <th class="arabic" style="width: 50px;">OñU,U.</th>
                    <th class="arabic" style="width: 50px;">OrOú</th>
                </tr>
            </thead>
            <tbody>
                <!-- Category A: UMUM -->
                <tr>
                    <td class="category" colspan="7">A - UMUM (OU,O1OU.)</td>
                </tr>
                @php
                    $no = 1;
                    $totalNilai = 0;
                    $jumlahMapel = 0;
                    $nilaiUmum = $nilaiMapel->filter(function($n) { return $n->kelasMapel->mapel->kategori == 'umum'; });
                    $nilaiKhusus = $nilaiMapel->filter(function($n) { return $n->kelasMapel->mapel->kategori == 'khusus'; });
                    $nilaiCabang = $nilaiMapel->filter(function($n) { return $n->kelasMapel->mapel->kategori == 'cabang'; });
                @endphp
                
                @foreach($nilaiUmum as $nilai)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ strtoupper($nilai->kelasMapel->mapel->nama_mapel) }}</td>
                    <td class="text-center">{{ round($nilai->nilai_akhir) }}</td>
                    <td class="text-center">{{ $nilai->predikat }}</td>
                    <td class="text-center arabic">{{ convertToArabicNumber(round($nilai->nilai_akhir)) }}</td>
                    <td class="text-center arabic">{{ convertPredikatToArabic($nilai->predikat) }}</td>
                    <td class="text-center arabic">{{ getArabicSubjectName($nilai->kelasMapel->mapel->nama_mapel) }}</td>
                </tr>
                @php
                    $totalNilai += $nilai->nilai_akhir;
                    $jumlahMapel++;
                @endphp
                @endforeach

                <!-- Category B: KHUSUS -->
                <tr>
                    <td class="category" colspan="7">B - KHUSUS (OU,OrOOæ)</td>
                </tr>
                @foreach($nilaiKhusus as $nilai)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ strtoupper($nilai->kelasMapel->mapel->nama_mapel) }}</td>
                    <td class="text-center">{{ round($nilai->nilai_akhir) }}</td>
                    <td class="text-center">{{ $nilai->predikat }}</td>
                    <td class="text-center arabic">{{ convertToArabicNumber(round($nilai->nilai_akhir)) }}</td>
                    <td class="text-center arabic">{{ convertPredikatToArabic($nilai->predikat) }}</td>
                    <td class="text-center arabic">{{ getArabicSubjectName($nilai->kelasMapel->mapel->nama_mapel) }}</td>
                </tr>
                @php
                    $totalNilai += $nilai->nilai_akhir;
                    $jumlahMapel++;
                @endphp
                @endforeach

                <!-- Category C: CABANG -->
                <tr>
                    <td class="category" colspan="7">C - CABANG (OU,U?OñO1)</td>
                </tr>
                @foreach($nilaiCabang as $nilai)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ strtoupper($nilai->kelasMapel->mapel->nama_mapel) }}</td>
                    <td class="text-center">{{ round($nilai->nilai_akhir) }}</td>
                    <td class="text-center">{{ $nilai->predikat }}</td>
                    <td class="text-center arabic">{{ convertToArabicNumber(round($nilai->nilai_akhir)) }}</td>
                    <td class="text-center arabic">{{ convertPredikatToArabic($nilai->predikat) }}</td>
                    <td class="text-center arabic">{{ getArabicSubjectName($nilai->kelasMapel->mapel->nama_mapel) }}</td>
                </tr>
                @php
                    $totalNilai += $nilai->nilai_akhir;
                    $jumlahMapel++;
                @endphp
                @endforeach

                <!-- Summary -->
                <tr>
                    <td colspan="2" class="fw-bold text-center">JUMLAH (OU,OªU.U,Oc)</td>
                    <td class="text-center fw-bold">{{ round($totalNilai) }}</td>
                    <td></td>
                    <td class="text-center arabic fw-bold">{{ convertToArabicNumber(round($totalNilai)) }}</td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td colspan="2" class="fw-bold text-center">RANGKING (OU,O_OñOªOc)</td>
                    <td class="text-center fw-bold">{{ $rekap->ranking ?? '-' }}</td>
                    <td colspan="4" class="text-center">Dari {{ $totalSantri ?? 0 }} Santri</td>
                </tr>
            </tbody>
        </table>

        <!-- Kepribadian & Absensi -->
        <div class="kepribadian">
            <table>
                <tr>
                    <th style="width: 50%;">KEPRIBADIAN (O¦O-U,USU, OU,OúOU,O")</th>
                    <th style="width: 50%;">Absensi (UŸO'U? OU,O§USOO")</th>
                </tr>
                <tr>
                    <td style="vertical-align: top;">
                        <table style="width: 100%; border: none;">
                            <tr>
                                <td style="border: none; width: 50%;">Akhlaq</td>
                                <td style="border: none; width: 10%;">:</td>
                                <td style="border: none; width: 10%;" class="fw-bold">{{ $rekap->akhlaq ?? 'A' }}</td>
                                <td style="border: none; width: 30%;" class="arabic text-right">OœOrU,OU,</td>
                            </tr>
                            <tr>
                                <td style="border: none;">Kerajinan</td>
                                <td style="border: none;">:</td>
                                <td style="border: none;" class="fw-bold">{{ $rekap->kerajinan ?? 'A' }}</td>
                                <td style="border: none;" class="arabic text-right">OªO_USOc</td>
                            </tr>
                            <tr>
                                <td style="border: none;">Kedisiplinan</td>
                                <td style="border: none;">:</td>
                                <td style="border: none;" class="fw-bold">{{ $rekap->kedisiplinan ?? 'A' }}</td>
                                <td style="border: none;" class="arabic text-right">OU+OO"OOú</td>
                            </tr>
                            <tr>
                                <td style="border: none;">Kerapihan</td>
                                <td style="border: none;">:</td>
                                <td style="border: none;" class="fw-bold">{{ $rekap->kerapihan ?? 'A' }}</td>
                                <td style="border: none;" class="arabic text-right">U+O,OU?Oc</td>
                            </tr>
                        </table>
                    </td>
                    <td style="vertical-align: top;">
                        <table style="width: 100%; border: none;">
                            <tr>
                                <td style="border: none; width: 40%;">Sakit</td>
                                <td style="border: none; width: 10%;">:</td>
                                <td style="border: none; width: 20%;">{{ $absensi->sakit ?? 0 }}</td>
                                <td style="border: none; width: 30%;" class="arabic text-right">O"O1OøOñ</td>
                            </tr>
                            <tr>
                                <td style="border: none;">Izin</td>
                                <td style="border: none;">:</td>
                                <td style="border: none;">{{ $absensi->izin ?? 0 }}</td>
                                <td style="border: none;" class="arabic text-right">O"O1OøOñ OæO-US</td>
                            </tr>
                            <tr>
                                <td style="border: none;">Alpa</td>
                                <td style="border: none;">:</td>
                                <td style="border: none;">{{ $absensi->alpha ?? 0 }}</td>
                                <td style="border: none;" class="arabic text-right">O"O_U^U+ O1OøOñ</td>
                            </tr>
                            <tr>
                                <td style="border: none;">Jumlah</td>
                                <td style="border: none;">:</td>
                                <td style="border: none;" class="fw-bold">{{ ($absensi->sakit ?? 0) + ($absensi->izin ?? 0) + ($absensi->alpha ?? 0) }}</td>
                                <td style="border: none;" class="arabic text-right">OU,OªU.U,Oc</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Catatan Guru -->
        <div class="catatan">
            <strong>CATATAN GURU</strong><br>
            <em>Tetap semangat | Kana seterossu bakurunnya</em>
        </div>

        <!-- Signature -->
        <div class="signature">
            <div class="text-right" style="margin-bottom: 5px;">
                GRESIK, {{ strtoupper(\Carbon\Carbon::now()->translatedFormat('d F Y')) }}
            </div>
            <table>
                <tr>
                    <td style="width: 33%;">Orang Tua/Wali</td>
                    <td style="width: 34%;">Guru Wali Kelas</td>
                    <td style="width: 33%;">Kepala Madrasah</td>
                </tr>
                <tr>
                    <td>
                        <div class="name">...................................</div>
                    </td>
                    <td>
                        <div class="name">{{ strtoupper($santri->kelas->waliKelas->name ?? '-') }}</div>
                    </td>
                    <td>
                        <div class="name">M. SHOLAHUDDIN MALIKI, S.Pd.I</div>
                    </td>
                </tr>
            </table>
        </div>

        @if($copy < ($copies ?? 1))
            <div class="page-break"></div>
        @endif
    @endfor
</body>
</html>
