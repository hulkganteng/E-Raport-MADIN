@php
    $nilaiUmum = $nilaiMapel->filter(fn ($n) => $n->kelasMapel->mapel->kategori === 'umum')->values();
    $nilaiKhusus = $nilaiMapel->filter(fn ($n) => $n->kelasMapel->mapel->kategori === 'khusus')->values();
    $nilaiCabang = $nilaiMapel->filter(fn ($n) => $n->kelasMapel->mapel->kategori === 'cabang')->values();
    $scoreGroups = [
        ['code' => 'A', 'label' => 'UMUM', 'arabic' => \App\Support\RaportArabic::label('general'), 'items' => $nilaiUmum],
        ['code' => 'B', 'label' => 'KHUSUS', 'arabic' => \App\Support\RaportArabic::label('special'), 'items' => $nilaiKhusus],
        ['code' => 'C', 'label' => 'CABANG', 'arabic' => \App\Support\RaportArabic::label('branch'), 'items' => $nilaiCabang],
    ];
    $scoreRows = [];
    $totalNilai = 0;
    $jumlahMapel = 0;

    foreach ($scoreGroups as $group) {
        $scoreRows[] = ['type' => 'category'] + $group;

        foreach ($group['items'] as $index => $nilai) {
            $rounded = (int) round($nilai->nilai_akhir);
            $totalNilai += $rounded;
            $jumlahMapel++;
            $scoreRows[] = [
                'type' => 'item',
                'group' => $group,
                'no' => $index + 1,
                'mapel' => strtoupper($nilai->kelasMapel->mapel->nama_mapel),
                'score' => $rounded,
                'score_words' => \App\Support\RaportArabic::spellIndonesian($rounded),
                'score_ar' => \App\Support\RaportArabic::digits($rounded),
                'score_ar_words' => \App\Support\RaportArabic::spellIndonesianArabicScript($rounded),
                'subject_ar' => \App\Support\RaportArabic::subjectName($nilai->kelasMapel->mapel->nama_mapel),
            ];
        }
    }

    $rataRata = $rekap && $rekap->rata_rata !== null
        ? (float) $rekap->rata_rata
        : ($jumlahMapel > 0 ? $totalNilai / $jumlahMapel : 0);
    $rataRataRounded = (int) round($rataRata);

    $personalityRows = [
        ['label' => 'Akhlaq', 'value' => $rekap->akhlaq ?? 'A', 'arabic' => \App\Support\RaportArabic::label('akhlaq')],
        ['label' => 'Kerajinan', 'value' => $rekap->kerajinan ?? 'A', 'arabic' => \App\Support\RaportArabic::label('diligence')],
        ['label' => 'Kedisiplinan', 'value' => $rekap->kedisiplinan ?? 'A', 'arabic' => \App\Support\RaportArabic::label('discipline')],
        ['label' => 'Kerapihan', 'value' => $rekap->kerapihan ?? 'A', 'arabic' => \App\Support\RaportArabic::label('neatness')],
    ];

    $attendanceRows = [
        ['label' => 'Sakit', 'value' => (int) ($absensi->sakit ?? 0), 'arabic' => \App\Support\RaportArabic::label('sick')],
        ['label' => 'Izin', 'value' => (int) ($absensi->izin ?? 0), 'arabic' => \App\Support\RaportArabic::label('permission')],
        ['label' => 'Alpa', 'value' => (int) ($absensi->alpha ?? 0), 'arabic' => \App\Support\RaportArabic::label('absent')],
        ['label' => 'Jumlah', 'value' => (int) (($absensi->sakit ?? 0) + ($absensi->izin ?? 0) + ($absensi->alpha ?? 0)), 'arabic' => \App\Support\RaportArabic::label('attendance_total')],
    ];

    $catatanGuru = trim((string) ($rekap->catatan_wali ?? ''));
    $namaKepalaMadrasah = trim((string) ($periode->nama_kepala_madrasah ?? ''));
    if ($namaKepalaMadrasah === '') {
        $namaKepalaMadrasah = 'M. SHOLAHUDDIN MALIKI, S.Pd.I';
    }
    $waliKelasName = \App\Models\WaliKelas::with('user')
        ->where('kelas_id', $santri->kelas_id)
        ->where('periode_id', $periode->id)
        ->first()
        ?->user
        ?->name;
    $waliKelasName = trim((string) ($waliKelasName ?? ''));
@endphp

<div class="report-page">
    <div class="header">
        <img src="{{ $logoSrc ?? ('file:///' . str_replace('\\', '/', public_path('logo.jpg'))) }}" alt="Logo" class="logo">
        <div class="header-text">
            <h3>MADRASAH DINIYAH</h3>
            <h3>PONDOK PESANTREN ASSYAFI'IYAH</h3>
            <p>321235250108</p>
            <p>JL. NONGKO KEREP RT 02 RW 01, BUNGAH, BUNGAH, GRESIK, JAWA TIMUR, 61152</p>
            <p>Email: assyafiyahbungahgresik@gmail.com</p>
        </div>
    </div>

    <div class="title">LAPORAN HASIL BELAJAR SANTRI</div>

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
                            <td>{{ strtoupper($santri->alamat ?? '-') }}</td>
                        </tr>
                    </table>
                </td>
                <td class="right">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 35%;">Kelas</td>
                            <td style="width: 5%;">:</td>
                            <td style="width: 60%;">{{ $santri->kelas->tingkatan }} ({{ strtoupper($santri->kelas->nama_kelas) }})</td>
                        </tr>
                        <tr>
                            <td>Semester</td>
                            <td>:</td>
                            <td>{{ ucfirst($periode->semester ?? '-') }}</td>
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

    <table class="split-table">
        <tr>
            <td class="panel-left">
                <table class="nilai indo-table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 28px;">No</th>
                            <th rowspan="2">Mata Pelajaran</th>
                            <th colspan="2">Hasil Tes</th>
                        </tr>
                        <tr>
                            <th style="width: 58px;">Angka</th>
                            <th style="width: 110px;">Huruf</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($scoreRows as $row)
                            @if($row['type'] === 'category')
                                <tr>
                                    <td class="category text-center">{{ $row['code'] }}</td>
                                    <td class="category" colspan="3">{{ $row['label'] }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td class="text-center">{{ $row['no'] }}</td>
                                    <td>{{ $row['mapel'] }}</td>
                                    <td class="text-center">{{ $row['score'] }}</td>
                                    <td>{{ $row['score_words'] }}</td>
                                </tr>
                            @endif
                        @endforeach
                        <tr>
                            <td class="fw-bold" colspan="2">JUMLAH</td>
                            <td class="text-center fw-bold">{{ $totalNilai }}</td>
                            <td>{{ \App\Support\RaportArabic::spellIndonesian($totalNilai) }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold" colspan="2">RATA-RATA</td>
                            <td class="text-center fw-bold">{{ number_format($rataRata, 2) }}</td>
                            <td>{{ \App\Support\RaportArabic::spellIndonesian($rataRataRounded) }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold" colspan="2">RANGKING</td>
                            <td class="text-center fw-bold">{{ $rekap->ranking ?? '-' }}</td>
                            <td>Dari {{ $totalSantri ?? 0 }} Santri</td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td class="panel-right">
                <table class="nilai arab-table">
                    <thead>
                        <tr>
                            <th rowspan="2" class="arabic">{{ \App\Support\RaportArabic::label('subject_name') }}</th>
                            <th colspan="2" class="arabic">{{ \App\Support\RaportArabic::label('exam_results') }}</th>
                            <th rowspan="2" class="arabic" style="width: 28px;"></th>
                        </tr>
                        <tr>
                            <th class="arabic" style="width: 58px;">{{ \App\Support\RaportArabic::label('number') }}</th>
                            <th class="arabic" style="width: 72px;">{{ \App\Support\RaportArabic::label('letter') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($scoreRows as $row)
                            @if($row['type'] === 'category')
                                <tr>
                                    <td class="category arabic arabic-right" colspan="4">{{ $row['arabic'] }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td class="arabic arabic-right">{{ $row['subject_ar'] }}</td>
                                    <td class="text-center arabic">{{ $row['score_ar'] }}</td>
                                    <td class="text-center arabic arabic-words">{{ $row['score_ar_words'] }}</td>
                                    <td class="text-center arabic">{{ \App\Support\RaportArabic::digits($row['no']) }}</td>
                                </tr>
                            @endif
                        @endforeach
                        <tr>
                            <td class="arabic arabic-right fw-bold">{{ \App\Support\RaportArabic::label('total') }}</td>
                            <td class="text-center arabic fw-bold">{{ \App\Support\RaportArabic::digits($totalNilai) }}</td>
                            <td class="text-center arabic arabic-words">{{ \App\Support\RaportArabic::spellIndonesianArabicScript($totalNilai) }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="arabic arabic-right fw-bold">{{ \App\Support\RaportArabic::label('average') }}</td>
                            <td class="text-center arabic fw-bold">{{ \App\Support\RaportArabic::digits(number_format($rataRata, 2)) }}</td>
                            <td class="text-center arabic arabic-words">{{ \App\Support\RaportArabic::spellIndonesianArabicScript($rataRataRounded) }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="arabic arabic-right fw-bold">{{ \App\Support\RaportArabic::label('ranking') }}</td>
                            <td class="text-center arabic fw-bold">{{ \App\Support\RaportArabic::digits($rekap->ranking ?? 0) }}</td>
                            <td class="arabic arabic-right" colspan="2">من {{ \App\Support\RaportArabic::digits($totalSantri ?? 0) }} {{ \App\Support\RaportArabic::label('student_count_suffix') }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <table class="split-table">
        <tr>
            <td class="panel-left">
                <table class="kepribadian">
                    <thead>
                        <tr>
                            <th colspan="6">KEPRIBADIAN (<span class="arabic-inline">{{ \App\Support\RaportArabic::label('personality') }}</span>)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($personalityRows as $row)
                            <tr>
                                <td style="border: none; width: 35%;">{{ $row['label'] }}</td>
                                <td style="border: none; width: 5%;">:</td>
                                <td style="border: none; width: 10%;" class="fw-bold">{{ $row['value'] }}</td>
                                <td style="border: none; width: 25%;" class="arabic arabic-right">{{ $row['arabic'] }}</td>
                                <td style="border: none; width: 5%;" class="arabic">:</td>
                                <td style="border: none; width: 20%;" class="arabic arabic-right">{{ \App\Support\RaportArabic::gradeDescriptionArabic($row['value']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
            <td class="panel-right">
                <table class="kepribadian">
                    <thead>
                        <tr>
                            <th colspan="6">Absensi (<span class="arabic-inline">{{ \App\Support\RaportArabic::label('attendance') }}</span>)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendanceRows as $row)
                            <tr>
                                <td style="border: none; width: 30%;">{{ $row['label'] }}</td>
                                <td style="border: none; width: 5%;">:</td>
                                <td style="border: none; width: 10%;" class="fw-bold">{{ $row['value'] }}</td>
                                <td style="border: none; width: 25%;" class="arabic arabic-right">{{ $row['arabic'] }}</td>
                                <td style="border: none; width: 5%;" class="arabic">:</td>
                                <td style="border: none; width: 25%;" class="arabic text-center">{{ \App\Support\RaportArabic::digits($row['value']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <div class="catatan">
        <div class="catatan-title">CATATAN GURU</div>
        <div class="catatan-body">{{ $catatanGuru !== '' ? $catatanGuru : 'Pertahankan prestasi yang kamu raih kalau bisa tingkatkan lagi' }}</div>
    </div>

    <div class="signature">
        <div class="text-right" style="margin-bottom: 8px;">
            GRESIK, {{ strtoupper(\Carbon\Carbon::now()->translatedFormat('d F Y')) }}
        </div>
        <table>
            <tr>
                <td style="width: 33%;">Orang Tua/Wali</td>
                <td style="width: 34%;">Guru Wali Kelas</td>
                <td style="width: 33%;">Kepala Madrasah</td>
            </tr>
            <tr>
                <td><div class="name">..............................</div></td>
                <td><div class="name">{{ $waliKelasName !== '' ? strtoupper($waliKelasName) : '-' }}</div></td>
                <td><div class="name">{{ strtoupper($namaKepalaMadrasah) }}</div></td>
            </tr>
        </table>
    </div>
</div>
