# Catatan Belajar: Logika Models dan Controllers

Catatan ini dibuat untuk memahami alur berpikir saat ditanya tentang `Model` dan `Controller`, terutama pada aplikasi berbasis MVC seperti Laravel.

## 1. Gambaran Besar MVC

MVC adalah singkatan dari:

- `Model`: bagian yang berhubungan dengan data dan database.
- `View`: bagian tampilan yang dilihat user.
- `Controller`: bagian penghubung antara request user, model, dan view.

Alur sederhananya:

```text
User membuka halaman
        |
        v
Route menerima URL
        |
        v
Controller menjalankan logika
        |
        v
Model mengambil atau menyimpan data
        |
        v
Controller mengirim data ke View
        |
        v
User melihat hasilnya
```

Contoh:

```text
User buka halaman daftar siswa
Route memanggil SiswaController@index
Controller meminta data ke Model Siswa
Model mengambil data dari tabel siswa
Controller mengirim data siswa ke halaman view
```

## 2. Apa Itu Model?

Model adalah class yang mewakili tabel di database.

Kalau ada tabel:

```text
siswa
```

Biasanya ada model:

```php
Siswa
```

Tugas utama Model:

- Mengambil data dari database.
- Menyimpan data ke database.
- Mengubah data di database.
- Menghapus data dari database.
- Menjelaskan relasi antar tabel.

Contoh logika Model:

```php
$siswa = Siswa::all();
```

Artinya:

```text
Ambil semua data dari tabel siswa.
```

Contoh lain:

```php
Siswa::create([
    'nama' => 'Ahmad',
    'nis' => '12345',
    'kelas_id' => 1,
]);
```

Artinya:

```text
Simpan data siswa baru ke database.
```

## 3. Model Bukan Tempat Tampilan

Model tidak mengurus tampilan halaman.

Model tidak seharusnya berisi:

```php
return view('siswa.index');
```

Karena `view` adalah urusan Controller.

Model fokus ke data.

## 4. Apa Itu Controller?

Controller adalah class yang mengatur alur kerja aplikasi.

Controller menerima request dari user, lalu menentukan apa yang harus dilakukan.

Tugas utama Controller:

- Menerima input dari form.
- Memvalidasi data.
- Memanggil Model.
- Mengatur proses simpan, edit, hapus, dan tampil data.
- Mengirim data ke View.
- Mengarahkan user ke halaman lain.

Contoh Controller:

```php
public function index()
{
    $siswa = Siswa::all();

    return view('siswa.index', compact('siswa'));
}
```

Artinya:

```text
Ambil semua data siswa menggunakan Model Siswa.
Kirim data tersebut ke view siswa.index.
```

## 5. Contoh Pembagian Tugas

Misalnya ada fitur tambah siswa.

Yang dilakukan user:

```text
Mengisi form tambah siswa lalu menekan tombol Simpan.
```

Alurnya:

```text
Form dikirim ke route
Route memanggil SiswaController@store
Controller memvalidasi input
Controller memanggil Model Siswa
Model menyimpan data ke tabel siswa
Controller mengarahkan user kembali ke halaman daftar siswa
```

Contoh kode:

```php
public function store(Request $request)
{
    $request->validate([
        'nama' => 'required',
        'nis' => 'required',
        'kelas_id' => 'required',
    ]);

    Siswa::create([
        'nama' => $request->nama,
        'nis' => $request->nis,
        'kelas_id' => $request->kelas_id,
    ]);

    return redirect()->route('siswa.index')
        ->with('success', 'Data siswa berhasil ditambahkan.');
}
```

Penjelasan:

- `Request $request`: berisi data yang dikirim dari form.
- `validate`: memastikan data wajib diisi.
- `Siswa::create`: menyimpan data ke database lewat Model.
- `redirect`: mengarahkan user ke halaman lain.

## 6. Relasi di Model

Model juga digunakan untuk menjelaskan hubungan antar tabel.

Contoh:

```text
Satu kelas memiliki banyak siswa.
Satu siswa berada di satu kelas.
```

Di Model `Kelas`:

```php
public function siswa()
{
    return $this->hasMany(Siswa::class);
}
```

Artinya:

```text
Satu kelas punya banyak siswa.
```

Di Model `Siswa`:

```php
public function kelas()
{
    return $this->belongsTo(Kelas::class);
}
```

Artinya:

```text
Satu siswa milik satu kelas.
```

Contoh penggunaan:

```php
$siswa = Siswa::with('kelas')->get();
```

Artinya:

```text
Ambil data siswa beserta data kelasnya.
```

## 7. Perbedaan Model dan Controller

| Bagian | Fokus | Contoh Tugas |
| --- | --- | --- |
| Model | Data dan database | Ambil data siswa, simpan nilai, relasi kelas |
| Controller | Alur proses | Validasi form, panggil model, kirim data ke view |
| View | Tampilan | Tabel siswa, form input, tombol edit |

Kalimat mudah diingat:

```text
Model mengurus data.
Controller mengatur proses.
View menampilkan hasil.
```

## 8. Contoh Jawaban Kalau Ditanya

Pertanyaan:

```text
Apa fungsi Model?
```

Jawaban:

```text
Model berfungsi untuk mengatur data yang berhubungan dengan database. 
Di Laravel, Model biasanya mewakili satu tabel, misalnya Model Siswa 
mewakili tabel siswa. Model digunakan untuk mengambil, menyimpan, 
mengubah, menghapus data, dan membuat relasi antar tabel.
```

Pertanyaan:

```text
Apa fungsi Controller?
```

Jawaban:

```text
Controller berfungsi untuk mengatur alur proses aplikasi. Controller 
menerima request dari user, memvalidasi input, memanggil Model untuk 
mengambil atau menyimpan data, lalu mengirim hasilnya ke View atau 
mengarahkan user ke halaman lain.
```

Pertanyaan:

```text
Apa perbedaan Model dan Controller?
```

Jawaban:

```text
Model fokus pada data dan database, sedangkan Controller fokus pada 
alur proses aplikasi. Contohnya, saat menampilkan daftar siswa, 
Controller memanggil Model Siswa untuk mengambil data dari database, 
lalu Controller mengirim data itu ke View untuk ditampilkan.
```

## 9. Pola Berpikir Saat Membuat Fitur

Saat membuat fitur, gunakan pertanyaan ini:

```text
1. User mau melakukan apa?
2. Route mana yang menerima request?
3. Controller method apa yang dijalankan?
4. Data apa yang perlu diambil atau disimpan?
5. Model apa yang dipakai?
6. Setelah selesai, user diarahkan ke mana?
```

Contoh fitur input nilai:

```text
User mengisi nilai siswa.
Route memanggil NilaiController@store.
Controller memvalidasi nilai.
Controller memanggil Model Nilai.
Model menyimpan nilai ke tabel nilai.
Controller mengarahkan user kembali ke halaman nilai.
```

## 10. Contoh Struktur Controller Umum

```php
class SiswaController extends Controller
{
    public function index()
    {
        // Menampilkan semua data
    }

    public function create()
    {
        // Menampilkan form tambah data
    }

    public function store(Request $request)
    {
        // Menyimpan data baru
    }

    public function edit($id)
    {
        // Menampilkan form edit data
    }

    public function update(Request $request, $id)
    {
        // Mengubah data
    }

    public function destroy($id)
    {
        // Menghapus data
    }
}
```

Penjelasan singkat:

- `index`: menampilkan daftar data.
- `create`: menampilkan form tambah.
- `store`: menyimpan data baru.
- `edit`: menampilkan form edit.
- `update`: menyimpan perubahan data.
- `destroy`: menghapus data.

## 11. Kesalahan yang Sering Terjadi

Kesalahan umum:

- Logika database ditulis terlalu banyak di View.
- Controller terlalu panjang dan sulit dibaca.
- Model hanya dibuat, tetapi relasinya tidak ditulis.
- Nama Model dan tabel tidak konsisten.
- Lupa menambahkan field di `$fillable`.

Contoh `$fillable`:

```php
protected $fillable = [
    'nama',
    'nis',
    'kelas_id',
];
```

Artinya:

```text
Field nama, nis, dan kelas_id boleh diisi secara massal lewat create atau update.
```

## 12. Ringkasan Super Singkat

```text
Route menentukan alamat.
Controller mengatur alur.
Model mengurus data.
View menampilkan halaman.
```

Atau:

```text
Controller bertanya ke Model.
Model mengambil data dari database.
Controller memberikan data ke View.
View menampilkan data ke user.
```

## 13. Latihan Jawaban Lisan

Latihan 1:

```text
Jika saya membuat fitur daftar siswa, Controller akan memanggil Model 
Siswa untuk mengambil data dari database. Setelah data didapat, 
Controller mengirim data itu ke View agar bisa ditampilkan dalam tabel.
```

Latihan 2:

```text
Jika saya membuat fitur tambah nilai, Controller menerima data dari form, 
memvalidasi input, lalu memakai Model Nilai untuk menyimpan data ke 
database. Setelah berhasil, user diarahkan kembali ke halaman nilai.
```

Latihan 3:

```text
Relasi Model digunakan untuk menghubungkan data antar tabel. Misalnya 
Model Siswa memiliki relasi belongsTo ke Model Kelas, karena satu siswa 
berada dalam satu kelas.
```

