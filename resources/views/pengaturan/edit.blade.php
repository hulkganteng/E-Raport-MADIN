@extends('layouts.app')

@section('header', 'Pengaturan Lembaga')

@php
    $labelCls = 'block text-sm font-medium text-slate-700 mb-1.5';
    $inputCls = 'w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 focus:outline-none';
    $hintCls = 'mt-1.5 text-xs text-slate-400';
@endphp

@section('content')
<div class="max-w-3xl mx-auto">

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-800 rounded-lg shadow-sm">
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('pengaturan.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Preview Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="p-6 sm:p-8 flex flex-col sm:flex-row items-center gap-5 bg-gradient-to-br from-teal-50 via-emerald-50 to-white border-b border-slate-200">
                <div class="flex-shrink-0">
                    <label for="logo" class="block relative w-20 h-20 group cursor-pointer">
                        <img id="logo-preview" src="{{ lembaga_logo_url() }}" alt="Logo"
                             class="w-20 h-20 rounded-2xl border border-slate-200 bg-white object-cover shadow-sm"
                             onerror="this.style.display='none'">
                        <span class="absolute inset-0 rounded-2xl bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-xs font-semibold transition">Ganti</span>
                    </label>
                </div>
                <div class="text-center sm:text-left">
                    <p class="text-xs font-semibold uppercase tracking-widest text-teal-600 mb-1" id="jenjang-preview">{{ $settings->jenjang }}</p>
                    <h2 class="text-xl font-bold text-slate-800 leading-snug" id="nama-preview">{{ $settings->nama_lembaga ?: 'Nama Lembaga' }}</h2>
                    <p class="text-sm text-slate-500 mt-1">Pengaturan ini tampil pada sidebar, halaman login, dan kop raport.</p>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="mb-6">
            <div class="inline-flex w-full sm:w-auto rounded-xl bg-slate-100 p-1 gap-1" role="tablist" aria-label="Seksi pengaturan">
                <button type="button" data-tab="identitas" class="tab-btn flex-1 sm:flex-none px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-slate-800 transition" id="tab-identitas">Identitas</button>
                <button type="button" data-tab="alamat" class="tab-btn flex-1 sm:flex-none px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-slate-800 transition" id="tab-alamat">Alamat & Kontak</button>
                <button type="button" data-tab="akademik" class="tab-btn flex-1 sm:flex-none px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-slate-800 transition" id="tab-akademik">Akademik</button>
            </div>
        </div>

        <!-- Panel: Identitas -->
        <section data-panel="identitas" class="panel bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="px-6 py-5 border-b border-slate-200">
                <h3 class="text-base font-bold text-slate-800">Identitas Lembaga</h3>
                <p class="text-sm text-slate-500 mt-0.5">Nama, identitas resmi, logo, dan kepala lembaga.</p>
            </div>
            <div class="p-6 grid grid-cols-1 gap-5">
                <div>
                    <label for="nama_lembaga" class="{{ $labelCls }}">Nama Lembaga</label>
                    <input type="text" id="nama_lembaga" name="nama_lembaga" value="{{ old('nama_lembaga', $settings->nama_lembaga) }}" maxlength="255" class="{{ $inputCls }}">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="jenjang" class="{{ $labelCls }}">Jenjang / Jenis</label>
                        <input type="text" id="jenjang" name="jenjang" value="{{ old('jenjang', $settings->jenjang) }}" maxlength="255" placeholder="Madrasah Diniyah" class="{{ $inputCls }}">
                    </div>
                    <div>
                        <label for="nama_sekolah" class="{{ $labelCls }}">Nama Sekolah</label>
                        <input type="text" id="nama_sekolah" name="nama_sekolah" value="{{ old('nama_sekolah', $settings->nama_sekolah) }}" maxlength="255" placeholder="Opsional" class="{{ $inputCls }}">
                    </div>
                </div>

                <div>
                    <label for="logo" class="{{ $labelCls }}">Logo Lembaga</label>
                    <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/gif,image/webp"
                           class="block w-full text-sm text-slate-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 cursor-pointer transition">
                    <p class="{{ $hintCls }}">Format JPG / PNG / GIF / WebP, maksimal 2MB. Kosongkan jika tidak mengganti logo.</p>
                </div>

                <div class="border-t border-slate-100 pt-5">
                    <h4 class="text-sm font-semibold text-slate-700 mb-4">Nomor Induk & Kepala Lembaga</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div>
                            <label for="npsn" class="{{ $labelCls }}">NPSN</label>
                            <input type="text" id="npsn" name="npsn" value="{{ old('npsn', $settings->npsn) }}" maxlength="20" class="{{ $inputCls }}">
                        </div>
                        <div>
                            <label for="nsm" class="{{ $labelCls }}">NSM</label>
                            <input type="text" id="nsm" name="nsm" value="{{ old('nsm', $settings->nsm) }}" maxlength="20" class="{{ $inputCls }}">
                        </div>
                        <div>
                            <label for="nss" class="{{ $labelCls }}">NSS</label>
                            <input type="text" id="nss" name="nss" value="{{ old('nss', $settings->nss) }}" maxlength="20" class="{{ $inputCls }}">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-5">
                        <div>
                            <label for="nama_kepala" class="{{ $labelCls }}">Nama Kepala Lembaga</label>
                            <input type="text" id="nama_kepala" name="nama_kepala" value="{{ old('nama_kepala', $settings->nama_kepala) }}" maxlength="255" class="{{ $inputCls }}">
                        </div>
                        <div>
                            <label for="nip_kepala" class="{{ $labelCls }}">NIP Kepala</label>
                            <input type="text" id="nip_kepala" name="nip_kepala" value="{{ old('nip_kepala', $settings->nip_kepala) }}" maxlength="255" class="{{ $inputCls }}">
                        </div>
                    </div>
                    <p class="{{ $hintCls }} mt-3">Pada raport, nama penanda tangan akan memakai nama kepala per periode bila diisi, selain itu memakai nama di atas.</p>
                </div>
            </div>
        </section>

        <!-- Panel: Alamat & Kontak -->
        <section data-panel="alamat" class="panel hidden bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="px-6 py-5 border-b border-slate-200">
                <h3 class="text-base font-bold text-slate-800">Alamat & Kontak</h3>
                <p class="text-sm text-slate-500 mt-0.5">Alamat lengkap dan cara menghubungi lembaga.</p>
            </div>
            <div class="p-6 grid grid-cols-1 gap-5">
                <div>
                    <label for="alamat" class="{{ $labelCls }}">Alamat / Nama Jalan</label>
                    <input type="text" id="alamat" name="alamat" value="{{ old('alamat', $settings->alamat) }}" maxlength="255" placeholder="Jl. Raya No. 123..." class="{{ $inputCls }}">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="provinsi" class="{{ $labelCls }}">Provinsi</label>
                        <select id="provinsi" name="provinsi" class="{{ $inputCls }}" data-initial="{{ old('provinsi', $settings->provinsi) }}">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov->name }}" data-code="{{ $prov->code }}" {{ (strcasecmp(old('provinsi', $settings->provinsi ?? ''), $prov->name) === 0) ? 'selected' : '' }}>
                                    {{ $prov->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="kabupaten" class="{{ $labelCls }}">Kabupaten / Kota</label>
                        <select id="kabupaten" name="kabupaten" class="{{ $inputCls }}" data-initial="{{ old('kabupaten', $settings->kabupaten) }}" disabled>
                            <option value="">-- Pilih Provinsi Terlebih Dahulu --</option>
                        </select>
                    </div>
                    <div>
                        <label for="kecamatan" class="{{ $labelCls }}">Kecamatan</label>
                        <select id="kecamatan" name="kecamatan" class="{{ $inputCls }}" data-initial="{{ old('kecamatan', $settings->kecamatan) }}" disabled>
                            <option value="">-- Pilih Kab/Kota Terlebih Dahulu --</option>
                        </select>
                    </div>
                    <div>
                        <label for="desa" class="{{ $labelCls }}">Desa / Kelurahan</label>
                        <select id="desa" name="desa" class="{{ $inputCls }}" data-initial="{{ old('desa', $settings->desa) }}" disabled>
                            <option value="">-- Pilih Kecamatan Terlebih Dahulu --</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label for="kode_pos" class="{{ $labelCls }}">Kode Pos</label>
                        <input type="text" id="kode_pos" name="kode_pos" value="{{ old('kode_pos', $settings->kode_pos) }}" maxlength="10" placeholder="Contoh: 61152" class="{{ $inputCls }}">
                    </div>
                    <div>
                        <label for="telepon" class="{{ $labelCls }}">Telepon</label>
                        <input type="text" id="telepon" name="telepon" value="{{ old('telepon', $settings->telepon) }}" maxlength="50" placeholder="08xxxxxxxxxx" class="{{ $inputCls }}">
                    </div>
                    <div>
                        <label for="website" class="{{ $labelCls }}">Website</label>
                        <input type="url" id="website" name="website" value="{{ old('website', $settings->website) }}" maxlength="255" placeholder="https://..." class="{{ $inputCls }}">
                    </div>
                </div>
                <div>
                    <label for="email" class="{{ $labelCls }}">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $settings->email) }}" maxlength="255" placeholder="lembaga@example.com" class="{{ $inputCls }}">
                </div>
            </div>
        </section>

        <!-- Panel: Akademik -->
        <section data-panel="akademik" class="panel hidden bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="px-6 py-5 border-b border-slate-200">
                <h3 class="text-base font-bold text-slate-800">Pengaturan Akademik</h3>
                <p class="text-sm text-slate-500 mt-0.5">KKM default dan batas nilai untuk menentukan predikat nilai akhir.</p>
            </div>
            <div class="p-6 space-y-5">
                <div class="max-w-xs">
                    <label for="kkm_default" class="{{ $labelCls }}">KKM Default</label>
                    <input type="number" id="kkm_default" name="kkm_default" value="{{ old('kkm_default', $settings->kkm_default) }}" min="0" max="100" step="0.01" class="{{ $inputCls }}">
                    <p class="{{ $hintCls }}">Dipakai sebagai saran nilai minimum saat mengatur mapel pada suatu kelas.</p>
                </div>

                <fieldset class="border-t border-slate-100 pt-5">
                    <legend class="text-sm font-semibold text-slate-700 mb-4">Batas Nilai Predikat</legend>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 max-w-xl">
                        <div>
                            <label for="grade_min_a" class="{{ $labelCls }}"><span class="inline-flex h-6 w-6 items-center justify-center rounded-md bg-emerald-600 text-white text-xs font-bold">A</span> Minimal</label>
                            <input type="number" id="grade_min_a" name="grade_min_a" value="{{ old('grade_min_a', $settings->grade_min_a) }}" min="0" max="100" step="0.01" class="{{ $inputCls }}">
                        </div>
                        <div>
                            <label for="grade_min_b" class="{{ $labelCls }}"><span class="inline-flex h-6 w-6 items-center justify-center rounded-md bg-sky-600 text-white text-xs font-bold">B</span> Minimal</label>
                            <input type="number" id="grade_min_b" name="grade_min_b" value="{{ old('grade_min_b', $settings->grade_min_b) }}" min="0" max="100" step="0.01" class="{{ $inputCls }}">
                        </div>
                        <div>
                            <label for="grade_min_c" class="{{ $labelCls }}"><span class="inline-flex h-6 w-6 items-center justify-center rounded-md bg-amber-500 text-white text-xs font-bold">C</span> Minimal</label>
                            <input type="number" id="grade_min_c" name="grade_min_c" value="{{ old('grade_min_c', $settings->grade_min_c) }}" min="0" max="100" step="0.01" class="{{ $inputCls }}">
                        </div>
                    </div>
                    <p class="{{ $hintCls }} mt-4">Predikat: skor &ge; Minimal A = <b>A</b>, &ge; Minimal B = <b>B</b>, &ge; Minimal C = <b>C</b>, selain itu <b>D</b>.</p>
                </fieldset>
            </div>
        </section>

        <!-- Sticky Action Bar -->
        <div class="sticky bottom-4 z-10 flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white/95 backdrop-blur px-4 py-3 shadow-lg">
            <p class="text-sm text-slate-500 hidden sm:block" id="save-hint">Perubahan langsung tersimpan dan diterapkan.</p>
            <button type="submit" class="ml-auto inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 text-white rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition text-sm font-semibold shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    (function () {
        // Tabs
        const tabs = Array.from(document.querySelectorAll('.tab-btn'));
        const panels = Array.from(document.querySelectorAll('.panel'));

        function activate(name) {
            tabs.forEach(btn => {
                const active = btn.getAttribute('data-tab') === name;
                btn.classList.toggle('bg-white', active);
                btn.classList.toggle('shadow-sm', active);
                btn.classList.toggle('text-teal-700', active);
                btn.classList.toggle('font-semibold', active);
                btn.classList.toggle('text-slate-600', !active);
            });
            panels.forEach(panel => {
                const show = panel.getAttribute('data-panel') === name;
                panel.classList.toggle('hidden', !show);
            });
        }

        tabs.forEach(btn => {
            btn.addEventListener('click', () => activate(btn.getAttribute('data-tab')));
        });
        activate('identitas');

        // Live preview: nama + jenjang
        const namaInput = document.getElementById('nama_lembaga');
        const jenjangInput = document.getElementById('jenjang');
        const namaPreview = document.getElementById('nama-preview');
        const jenjangPreview = document.getElementById('jenjang-preview');

        if (namaInput && namaPreview) {
            namaInput.addEventListener('input', () => {
                namaPreview.textContent = namaInput.value.trim() || 'Nama Lembaga';
            });
        }
        if (jenjangInput && jenjangPreview) {
            jenjangInput.addEventListener('input', () => {
                jenjangPreview.textContent = jenjangInput.value.trim() || 'Lembaga';
            });
        }

        // Logo live preview
        const logoInput = document.getElementById('logo');
        const logoPreview = document.getElementById('logo-preview');
        if (logoInput && logoPreview) {
            logoInput.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        logoPreview.style.display = '';
                        logoPreview.src = e.target.result;
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }

        // Cascading Wilayah Indonesia
        const provSelect = document.getElementById('provinsi');
        const kabSelect = document.getElementById('kabupaten');
        const kecSelect = document.getElementById('kecamatan');
        const desaSelect = document.getElementById('desa');
        const kodePosInput = document.getElementById('kode_pos');

        const initialKab = kabSelect ? kabSelect.getAttribute('data-initial') : '';
        const initialKec = kecSelect ? kecSelect.getAttribute('data-initial') : '';
        const initialDesa = desaSelect ? desaSelect.getAttribute('data-initial') : '';

        async function fetchJson(url) {
            try {
                const res = await fetch(url, { credentials: 'same-origin' });
                if (!res.ok) throw new Error('Network response not ok');
                return await res.json();
            } catch (err) {
                console.error('Fetch error:', err);
                return [];
            }
        }

        function resetSelect(select, defaultText, disabled = true) {
            select.innerHTML = `<option value="">${defaultText}</option>`;
            select.disabled = disabled;
        }

        async function loadKabupaten(provCode, targetValue = '') {
            resetSelect(kabSelect, 'Memuat data kabupaten/kota...', true);
            resetSelect(kecSelect, '-- Pilih Kab/Kota Terlebih Dahulu --', true);
            resetSelect(desaSelect, '-- Pilih Kecamatan Terlebih Dahulu --', true);

            if (!provCode) {
                resetSelect(kabSelect, '-- Pilih Provinsi Terlebih Dahulu --', true);
                return;
            }

            const cities = await fetchJson(`{{ route('wilayah.cities') }}?province_code=${provCode}`);
            kabSelect.innerHTML = '<option value="">-- Pilih Kabupaten / Kota --</option>';
            
            let matchedCode = '';
            cities.forEach(city => {
                const opt = document.createElement('option');
                opt.value = city.name;
                opt.textContent = city.name;
                opt.setAttribute('data-code', city.code);
                if (targetValue && (city.name.toUpperCase() === targetValue.trim().toUpperCase())) {
                    opt.selected = true;
                    matchedCode = city.code;
                }
                kabSelect.appendChild(opt);
            });

            kabSelect.disabled = false;

            if (matchedCode) {
                await loadKecamatan(matchedCode, initialKec);
            }
        }

        async function loadKecamatan(cityCode, targetValue = '') {
            resetSelect(kecSelect, 'Memuat data kecamatan...', true);
            resetSelect(desaSelect, '-- Pilih Kecamatan Terlebih Dahulu --', true);

            if (!cityCode) {
                resetSelect(kecSelect, '-- Pilih Kab/Kota Terlebih Dahulu --', true);
                return;
            }

            const districts = await fetchJson(`{{ route('wilayah.districts') }}?city_code=${cityCode}`);
            kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';

            let matchedCode = '';
            districts.forEach(dist => {
                const opt = document.createElement('option');
                opt.value = dist.name;
                opt.textContent = dist.name;
                opt.setAttribute('data-code', dist.code);
                if (targetValue && (dist.name.toUpperCase() === targetValue.trim().toUpperCase())) {
                    opt.selected = true;
                    matchedCode = dist.code;
                }
                kecSelect.appendChild(opt);
            });

            kecSelect.disabled = false;

            if (matchedCode) {
                await loadDesa(matchedCode, initialDesa);
            }
        }

        async function loadDesa(districtCode, targetValue = '') {
            resetSelect(desaSelect, 'Memuat data desa/kelurahan...', true);

            if (!districtCode) {
                resetSelect(desaSelect, '-- Pilih Kecamatan Terlebih Dahulu --', true);
                return;
            }

            const villages = await fetchJson(`{{ route('wilayah.villages') }}?district_code=${districtCode}`);
            desaSelect.innerHTML = '<option value="">-- Pilih Desa / Kelurahan --</option>';

            villages.forEach(vill => {
                const opt = document.createElement('option');
                opt.value = vill.name;
                opt.textContent = vill.name;
                opt.setAttribute('data-code', vill.code);
                if (vill.pos) {
                    opt.setAttribute('data-pos', vill.pos);
                }
                if (targetValue && (vill.name.toUpperCase() === targetValue.trim().toUpperCase())) {
                    opt.selected = true;
                }
                desaSelect.appendChild(opt);
            });

            desaSelect.disabled = false;
        }

        if (provSelect) {
            provSelect.addEventListener('change', function () {
                const selectedOpt = this.options[this.selectedIndex];
                const provCode = selectedOpt ? selectedOpt.getAttribute('data-code') : '';
                loadKabupaten(provCode);
            });
        }

        if (kabSelect) {
            kabSelect.addEventListener('change', function () {
                const selectedOpt = this.options[this.selectedIndex];
                const cityCode = selectedOpt ? selectedOpt.getAttribute('data-code') : '';
                loadKecamatan(cityCode);
            });
        }

        if (kecSelect) {
            kecSelect.addEventListener('change', function () {
                const selectedOpt = this.options[this.selectedIndex];
                const districtCode = selectedOpt ? selectedOpt.getAttribute('data-code') : '';
                loadDesa(districtCode);
            });
        }

        if (desaSelect) {
            desaSelect.addEventListener('change', function () {
                const selectedOpt = this.options[this.selectedIndex];
                const pos = selectedOpt ? selectedOpt.getAttribute('data-pos') : '';
                if (pos && kodePosInput) {
                    kodePosInput.value = pos;
                }
            });
        }

        // Initialize cascade on page load if province is selected
        if (provSelect) {
            const selectedOpt = provSelect.options[provSelect.selectedIndex];
            const provCode = selectedOpt ? selectedOpt.getAttribute('data-code') : '';
            if (provCode) {
                loadKabupaten(provCode, initialKab);
            }
        }
    })();
</script>
@endsection
