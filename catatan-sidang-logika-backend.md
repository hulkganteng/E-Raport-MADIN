# Catatan Sidang: Pertanyaan Logika Backend, Controller, dan Model

Catatan ini berisi pertanyaan yang sering ditanyakan penguji saat sidang aplikasi, terutama tentang logika backend, controller, model, database, dan alur aplikasi.

## 1. Pertanyaan Umum Tentang Logika Aplikasi

### Pertanyaan

```text
Jelaskan alur aplikasi Anda dari awal sampai data tersimpan.
```

### Cara Menjawab

Jawab dengan alur sederhana:

```text
User mengisi form, lalu data dikirim ke route. Route akan memanggil method 
di Controller. Controller menerima request, melakukan validasi, lalu 
memanggil Model untuk menyimpan data ke database. Setelah berhasil, 
Controller mengarahkan user kembali ke halaman tertentu dan menampilkan 
pesan berhasil.
```

### Pola Jawaban

```text
User -> Route -> Controller -> Validasi -> Model -> Database -> Response/View
```

## 2. Pertanyaan Tentang Controller

### Pertanyaan yang Sering Muncul

```text
Apa fungsi Controller pada aplikasi Anda?
```

Jawaban:

```text
Controller berfungsi untuk mengatur alur proses aplikasi. Controller 
menerima request dari user, memvalidasi data, memanggil Model untuk 
mengambil atau menyimpan data, lalu mengirim hasilnya ke View atau 
mengarahkan user ke halaman lain.
```

### Pertanyaan

```text
Kenapa logika simpan data diletakkan di Controller?
```

Jawaban:

```text
Karena Controller bertugas menangani request dari user. Saat user mengirim 
form, Controller menerima data tersebut, memvalidasi input, lalu meminta 
Model untuk menyimpan data ke database.
```

### Pertanyaan

```text
Apa fungsi method index, create, store, edit, update, dan destroy?
```

Jawaban:

```text
index digunakan untuk menampilkan daftar data.
create digunakan untuk menampilkan form tambah data.
store digunakan untuk menyimpan data baru.
edit digunakan untuk menampilkan form edit data.
update digunakan untuk menyimpan perubahan data.
destroy digunakan untuk menghapus data.
```

### Pertanyaan

```text
Apa yang terjadi saat tombol Simpan ditekan?
```

Jawaban:

```text
Saat tombol Simpan ditekan, form mengirim data ke route dengan method POST. 
Route memanggil method store pada Controller. Di dalam method store, data 
divalidasi terlebih dahulu. Jika valid, Controller memanggil Model untuk 
menyimpan data ke database. Setelah itu user diarahkan kembali ke halaman 
yang ditentukan.
```

## 3. Pertanyaan Tentang Model

### Pertanyaan

```text
Apa fungsi Model pada aplikasi Anda?
```

Jawaban:

```text
Model berfungsi untuk menghubungkan aplikasi dengan database. Model 
mewakili tabel tertentu dan digunakan untuk mengambil, menyimpan, mengubah, 
menghapus data, serta membuat relasi antar tabel.
```

### Pertanyaan

```text
Kenapa harus menggunakan Model?
```

Jawaban:

```text
Karena Model membuat pengelolaan data menjadi lebih rapi. Dengan Model, 
Controller tidak perlu menulis query database secara langsung terlalu banyak. 
Controller cukup memanggil Model untuk mengambil atau menyimpan data.
```

### Pertanyaan

```text
Apa hubungan Model dengan tabel database?
```

Jawaban:

```text
Model biasanya mewakili satu tabel di database. Misalnya Model Siswa 
mewakili tabel siswa, Model Nilai mewakili tabel nilai, dan Model Kelas 
mewakili tabel kelas.
```

### Pertanyaan

```text
Apa fungsi fillable pada Model?
```

Jawaban:

```text
fillable digunakan untuk menentukan kolom mana saja yang boleh diisi secara 
massal menggunakan create atau update. Ini berguna agar hanya field tertentu 
yang dapat disimpan melalui input aplikasi.
```

Contoh:

```php
protected $fillable = [
    'nama',
    'nis',
    'kelas_id',
];
```

## 4. Pertanyaan Tentang Relasi Model

### Pertanyaan

```text
Apa itu relasi pada Model?
```

Jawaban:

```text
Relasi pada Model adalah hubungan antar tabel. Misalnya tabel siswa 
berhubungan dengan tabel kelas, karena satu siswa berada di satu kelas. 
Relasi ini dibuat agar data yang saling berhubungan bisa dipanggil dengan 
lebih mudah.
```

### Pertanyaan

```text
Apa perbedaan hasMany dan belongsTo?
```

Jawaban:

```text
hasMany digunakan jika satu data memiliki banyak data lain. Contohnya satu 
kelas memiliki banyak siswa. belongsTo digunakan jika satu data dimiliki 
oleh data lain. Contohnya satu siswa dimiliki oleh satu kelas.
```

Contoh:

```php
// Model Kelas
public function siswa()
{
    return $this->hasMany(Siswa::class);
}
```

```php
// Model Siswa
public function kelas()
{
    return $this->belongsTo(Kelas::class);
}
```

### Pertanyaan

```text
Kenapa relasi penting?
```

Jawaban:

```text
Relasi penting karena aplikasi biasanya memiliki data yang saling terhubung. 
Dengan relasi, kita bisa mengambil data utama beserta data terkaitnya, 
misalnya mengambil data siswa sekaligus nama kelasnya.
```

## 5. Pertanyaan Tentang Validasi

### Pertanyaan

```text
Kenapa data harus divalidasi?
```

Jawaban:

```text
Data harus divalidasi agar data yang masuk ke database sesuai aturan. 
Misalnya nama wajib diisi, nilai harus berupa angka, dan NIS tidak boleh 
kosong. Validasi membantu mencegah data kosong, salah format, atau tidak 
sesuai kebutuhan sistem.
```

### Pertanyaan

```text
Di mana validasi dilakukan?
```

Jawaban:

```text
Validasi biasanya dilakukan di Controller, sebelum data disimpan ke 
database. Jadi data yang tidak valid tidak akan diteruskan ke proses 
penyimpanan.
```

Contoh:

```php
$request->validate([
    'nama' => 'required',
    'nis' => 'required',
    'nilai' => 'required|numeric',
]);
```

## 6. Pertanyaan Tentang Database

### Pertanyaan

```text
Bagaimana data bisa tersimpan ke database?
```

Jawaban:

```text
Data dari form dikirim ke Controller melalui request. Setelah divalidasi, 
Controller menggunakan Model untuk menjalankan proses penyimpanan ke tabel 
database. Biasanya proses ini menggunakan create atau save.
```

### Pertanyaan

```text
Apa fungsi primary key dan foreign key?
```

Jawaban:

```text
Primary key adalah identitas unik dari setiap data pada tabel. Foreign key 
adalah kolom yang digunakan untuk menghubungkan satu tabel dengan tabel 
lain. Contohnya kelas_id pada tabel siswa digunakan untuk menghubungkan 
siswa dengan tabel kelas.
```

### Pertanyaan

```text
Kenapa tabel perlu direlasikan?
```

Jawaban:

```text
Tabel direlasikan agar data tidak berulang dan lebih mudah dikelola. 
Misalnya nama kelas tidak perlu ditulis berulang di setiap data siswa, 
cukup menyimpan kelas_id yang mengarah ke tabel kelas.
```

## 7. Pertanyaan Tentang CRUD

### Pertanyaan

```text
Jelaskan proses CRUD di aplikasi Anda.
```

Jawaban:

```text
CRUD terdiri dari Create, Read, Update, dan Delete. Create digunakan untuk 
menambah data, Read untuk menampilkan data, Update untuk mengubah data, 
dan Delete untuk menghapus data. Pada aplikasi saya, proses CRUD dijalankan 
melalui Controller dan data dikelola menggunakan Model.
```

### Pertanyaan

```text
Apa perbedaan store dan update?
```

Jawaban:

```text
store digunakan untuk menyimpan data baru, sedangkan update digunakan untuk 
mengubah data yang sudah ada. store biasanya dipanggil dari form tambah, 
sedangkan update dipanggil dari form edit.
```

### Pertanyaan

```text
Bagaimana cara aplikasi menghapus data?
```

Jawaban:

```text
User menekan tombol hapus, lalu request dikirim ke route delete. Route 
memanggil method destroy pada Controller. Controller mencari data berdasarkan 
id, lalu memanggil Model untuk menghapus data tersebut dari database.
```

## 8. Pertanyaan Tentang Route

### Pertanyaan

```text
Apa fungsi route?
```

Jawaban:

```text
Route berfungsi untuk menghubungkan URL dengan Controller. Ketika user 
mengakses alamat tertentu, route menentukan Controller dan method mana yang 
akan dijalankan.
```

### Pertanyaan

```text
Apa hubungan route dengan Controller?
```

Jawaban:

```text
Route adalah pintu masuk request. Setelah request masuk, route akan 
mengarahkan request tersebut ke method Controller yang sesuai.
```

Contoh:

```php
Route::get('/siswa', [SiswaController::class, 'index']);
Route::post('/siswa', [SiswaController::class, 'store']);
```

## 9. Pertanyaan Tentang Login dan Hak Akses

### Pertanyaan

```text
Bagaimana aplikasi membedakan admin, guru, dan siswa?
```

Jawaban:

```text
Aplikasi membedakan user berdasarkan role atau level pengguna. Setelah user 
login, sistem memeriksa role user tersebut. Jika role admin, maka diarahkan 
ke halaman admin. Jika role guru, diarahkan ke halaman guru. Jika role siswa, 
diarahkan ke halaman siswa.
```

### Pertanyaan

```text
Kenapa hak akses diperlukan?
```

Jawaban:

```text
Hak akses diperlukan agar setiap user hanya bisa membuka fitur yang sesuai 
dengan perannya. Misalnya admin bisa mengelola data master, guru bisa 
menginput nilai, dan siswa hanya bisa melihat raport atau nilai miliknya.
```

## 10. Pertanyaan Tentang Keamanan Backend

### Pertanyaan

```text
Bagaimana mencegah user memasukkan data sembarangan?
```

Jawaban:

```text
Aplikasi menggunakan validasi pada Controller. Dengan validasi, data yang 
tidak sesuai aturan tidak akan disimpan ke database.
```

### Pertanyaan

```text
Bagaimana mencegah user mengakses halaman yang tidak boleh diakses?
```

Jawaban:

```text
Aplikasi menggunakan autentikasi dan pengecekan role. Jika user belum login 
atau rolenya tidak sesuai, maka user tidak bisa masuk ke halaman tersebut.
```

### Pertanyaan

```text
Kenapa password tidak boleh disimpan secara langsung?
```

Jawaban:

```text
Password tidak boleh disimpan dalam bentuk asli karena berbahaya jika 
database bocor. Password harus di-hash agar tidak mudah dibaca.
```

## 11. Pertanyaan Tentang Error

### Pertanyaan

```text
Apa yang terjadi jika data yang dicari tidak ada?
```

Jawaban:

```text
Jika data tidak ada, aplikasi dapat menampilkan halaman error atau kembali 
ke halaman sebelumnya dengan pesan bahwa data tidak ditemukan. Pada Laravel, 
bisa menggunakan findOrFail agar otomatis menampilkan error jika data tidak 
ditemukan.
```

### Pertanyaan

```text
Apa yang terjadi jika validasi gagal?
```

Jawaban:

```text
Jika validasi gagal, data tidak disimpan ke database. User akan dikembalikan 
ke halaman form dan ditampilkan pesan error sesuai field yang salah.
```

## 12. Pertanyaan Tentang Fitur Raport atau Nilai

### Pertanyaan

```text
Bagaimana alur input nilai?
```

Jawaban:

```text
Guru membuka form input nilai, memilih siswa atau kelas, lalu mengisi nilai. 
Data dikirim ke Controller. Controller memvalidasi nilai agar sesuai format, 
lalu memanggil Model Nilai untuk menyimpan data ke tabel nilai. Setelah 
berhasil, guru diarahkan kembali ke halaman nilai.
```

### Pertanyaan

```text
Bagaimana siswa melihat raportnya?
```

Jawaban:

```text
Siswa login ke aplikasi, lalu sistem mengambil data siswa berdasarkan akun 
yang sedang login. Controller memanggil Model Nilai atau Raport untuk 
mengambil nilai siswa tersebut, kemudian data dikirim ke View untuk 
ditampilkan sebagai raport.
```

### Pertanyaan

```text
Bagaimana aplikasi memastikan siswa hanya melihat data miliknya?
```

Jawaban:

```text
Aplikasi mengambil data berdasarkan user yang sedang login. Jadi query data 
dibatasi dengan id siswa atau user_id milik akun tersebut. Dengan begitu, 
siswa tidak bisa melihat data milik siswa lain.
```

## 13. Pertanyaan Tentang Kode yang Sering Ditunjuk Penguji

Penguji biasanya menunjuk bagian kode dan bertanya:

```text
Ini fungsinya untuk apa?
```

Siapkan jawaban untuk bagian berikut:

- `Route::get`: untuk menampilkan halaman atau mengambil data.
- `Route::post`: untuk mengirim dan menyimpan data dari form.
- `Request $request`: untuk mengambil data input dari user.
- `$request->validate`: untuk memvalidasi input.
- `Model::all()`: untuk mengambil semua data.
- `Model::find($id)`: untuk mencari data berdasarkan id.
- `Model::create()`: untuk menyimpan data baru.
- `$data->update()`: untuk mengubah data.
- `$data->delete()`: untuk menghapus data.
- `return view()`: untuk menampilkan halaman view.
- `redirect()`: untuk mengarahkan user ke halaman lain.
- `compact()`: untuk mengirim variabel ke view.

## 14. Cara Menjawab Jika Tidak Hafal Kode

Jika tidak hafal detail kode, jangan diam. Jawab dengan alurnya.

Contoh:

```text
Saya tidak hafal persis semua baris kodenya, tetapi alurnya adalah data 
dari form masuk ke Controller, lalu divalidasi, setelah itu disimpan 
menggunakan Model ke database.
```

Atau:

```text
Bagian ini digunakan untuk menghubungkan proses dari input user ke database. 
Controller menerima datanya, sedangkan Model yang berhubungan langsung 
dengan tabel database.
```

## 15. Kalimat Kunci yang Perlu Dihafal

```text
Controller mengatur alur proses aplikasi.
Model mengatur data dan hubungan dengan database.
Route menghubungkan URL dengan Controller.
View menampilkan data ke user.
Validasi memastikan data yang masuk sesuai aturan.
Relasi menghubungkan satu tabel dengan tabel lain.
Middleware atau role digunakan untuk membatasi hak akses.
```

## 16. Simulasi Pertanyaan Cepat

### Penguji

```text
Kenapa Anda menggunakan Controller?
```

### Jawaban

```text
Karena Controller digunakan untuk mengatur proses aplikasi, mulai dari 
menerima request, memvalidasi data, memanggil Model, sampai mengembalikan 
halaman atau redirect.
```

### Penguji

```text
Kenapa Anda menggunakan Model?
```

### Jawaban

```text
Karena Model digunakan untuk mengelola data yang berhubungan dengan 
database, seperti mengambil, menyimpan, mengubah, menghapus data, dan 
membuat relasi antar tabel.
```

### Penguji

```text
Kalau data nilai disimpan, prosesnya bagaimana?
```

### Jawaban

```text
Data nilai dikirim dari form ke Controller. Controller melakukan validasi, 
misalnya nilai harus angka dan tidak boleh kosong. Setelah valid, Controller 
memanggil Model Nilai untuk menyimpan data ke tabel nilai. Setelah berhasil, 
user diarahkan kembali ke halaman nilai.
```

### Penguji

```text
Kalau ada siswa dan kelas, relasinya bagaimana?
```

### Jawaban

```text
Relasinya adalah satu kelas memiliki banyak siswa, jadi di Model Kelas 
menggunakan hasMany. Sedangkan satu siswa berada di satu kelas, jadi di 
Model Siswa menggunakan belongsTo.
```

## 17. Rumus Jawaban Aman Saat Sidang

Gunakan pola ini saat menjawab:

```text
Pertama jelaskan tujuannya.
Kedua jelaskan alurnya.
Ketiga sebutkan bagian kode yang terlibat.
Keempat hubungkan dengan database atau tampilan.
```

Contoh:

```text
Fitur ini bertujuan untuk menyimpan data siswa. Alurnya, user mengisi form, 
lalu data dikirim ke Controller melalui route. Controller memvalidasi input, 
kemudian menggunakan Model Siswa untuk menyimpan data ke tabel siswa. 
Setelah berhasil, aplikasi mengarahkan user kembali ke halaman daftar siswa.
```

## 18. Hal yang Perlu Dipahami Sebelum Sidang

Pastikan bisa menjelaskan:

- Alur login.
- Alur tambah data.
- Alur edit data.
- Alur hapus data.
- Alur tampil data.
- Tabel apa saja yang digunakan.
- Relasi antar tabel.
- Controller apa saja yang penting.
- Model apa saja yang penting.
- Siapa saja role pengguna.
- Data apa yang boleh diakses setiap role.

## 19. Penutup

Yang paling penting saat sidang bukan menghafal semua kode, tetapi memahami alurnya.

Kalimat pegangan:

```text
Saya pahami alurnya: request masuk melalui route, diproses oleh Controller, 
data dikelola oleh Model, disimpan atau diambil dari database, lalu hasilnya 
ditampilkan ke View.
```

