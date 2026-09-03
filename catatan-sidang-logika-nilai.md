# Catatan Sidang: Logika Nilai

Catatan ini khusus untuk memahami dan menjawab pertanyaan tentang logika nilai pada aplikasi raport atau sistem akademik.

## 1. Gambaran Besar Logika Nilai

Logika nilai adalah proses bagaimana nilai siswa:

- Diinput oleh guru atau admin.
- Divalidasi oleh sistem.
- Disimpan ke database.
- Dihubungkan dengan siswa, kelas, mata pelajaran, semester, dan tahun ajaran.
- Ditampilkan kembali sebagai nilai atau raport.

Alur sederhananya:

```text
Guru memilih siswa / kelas / mata pelajaran
Guru mengisi nilai
Form dikirim ke route
Route memanggil Controller
Controller memvalidasi nilai
Controller memanggil Model Nilai
Model menyimpan data ke tabel nilai
Nilai ditampilkan kembali di halaman nilai atau raport
```

## 2. Jawaban Singkat Kalau Ditanya Logika Nilai

Jika penguji bertanya:

```text
Jelaskan bagaimana logika nilai pada aplikasi Anda.
```

Jawaban aman:

```text
Logika nilai dimulai ketika guru menginput nilai siswa berdasarkan mata 
pelajaran, kelas, semester, dan tahun ajaran. Data nilai dikirim ke 
Controller melalui form. Di Controller, data divalidasi agar nilai tidak 
kosong dan sesuai format angka. Setelah valid, Controller memanggil Model 
Nilai untuk menyimpan data ke tabel nilai. Data nilai tersebut kemudian 
dapat ditampilkan kembali pada halaman nilai atau raport siswa.
```

## 3. Data yang Biasanya Dibutuhkan dalam Nilai

Biasanya tabel nilai membutuhkan data seperti:

```text
id
siswa_id
mapel_id
guru_id
kelas_id
semester_id
tahun_ajaran_id
nilai_tugas
nilai_uts
nilai_uas
nilai_akhir
predikat
keterangan
```

Tidak semua aplikasi memakai kolom yang sama. Sesuaikan dengan aplikasi sendiri.

Yang penting dipahami:

- `siswa_id`: menunjukkan nilai milik siswa siapa.
- `mapel_id`: menunjukkan nilai untuk mata pelajaran apa.
- `guru_id`: menunjukkan guru yang menginput nilai.
- `kelas_id`: menunjukkan kelas siswa.
- `semester_id`: menunjukkan semester.
- `tahun_ajaran_id`: menunjukkan tahun ajaran.
- `nilai_akhir`: hasil perhitungan nilai.

## 4. Relasi Model Nilai

Model `Nilai` biasanya berhubungan dengan beberapa model lain.

Contoh relasi:

```text
Nilai belongsTo Siswa
Nilai belongsTo Mapel
Nilai belongsTo Guru
Nilai belongsTo Kelas
Nilai belongsTo Semester
```

Artinya:

```text
Satu data nilai dimiliki oleh satu siswa.
Satu data nilai dimiliki oleh satu mata pelajaran.
Satu data nilai bisa diinput oleh satu guru.
Satu data nilai berada pada satu kelas dan semester.
```

Contoh kode:

```php
public function siswa()
{
    return $this->belongsTo(Siswa::class);
}

public function mapel()
{
    return $this->belongsTo(Mapel::class);
}

public function guru()
{
    return $this->belongsTo(Guru::class);
}
```

## 5. Pertanyaan yang Sering Ditanyakan Penguji

### Pertanyaan

```text
Bagaimana proses input nilai?
```

Jawaban:

```text
Guru membuka halaman input nilai, kemudian memilih data yang dibutuhkan 
seperti siswa, kelas, mata pelajaran, semester, dan tahun ajaran. Setelah 
guru mengisi nilai, data dikirim ke Controller. Controller memvalidasi input, 
lalu menggunakan Model Nilai untuk menyimpan data ke database.
```

### Pertanyaan

```text
Bagaimana nilai akhir dihitung?
```

Jawaban umum:

```text
Nilai akhir dihitung dari beberapa komponen nilai, misalnya nilai tugas, UTS, 
dan UAS. Setiap komponen bisa memiliki bobot tertentu. Setelah dihitung, 
hasilnya disimpan sebagai nilai akhir.
```

Contoh sederhana:

```text
nilai_akhir = (nilai_tugas + nilai_uts + nilai_uas) / 3
```

Contoh dengan bobot:

```text
nilai_akhir = (tugas x 30%) + (UTS x 30%) + (UAS x 40%)
```

Jawaban sidang:

```text
Pada aplikasi saya, nilai akhir dihitung berdasarkan komponen nilai yang 
digunakan, seperti tugas, UTS, dan UAS. Nilai tersebut diproses di Controller 
sebelum disimpan, sehingga hasil akhirnya dapat langsung ditampilkan pada 
halaman nilai atau raport.
```

## 6. Pertanyaan Tentang Validasi Nilai

### Pertanyaan

```text
Kenapa nilai harus divalidasi?
```

Jawaban:

```text
Nilai harus divalidasi agar data yang masuk benar. Misalnya nilai tidak boleh 
kosong, harus berupa angka, dan berada pada rentang tertentu seperti 0 sampai 
100. Dengan validasi, sistem dapat mencegah data salah masuk ke database.
```

Contoh validasi:

```php
$request->validate([
    'siswa_id' => 'required',
    'mapel_id' => 'required',
    'nilai_tugas' => 'required|numeric|min:0|max:100',
    'nilai_uts' => 'required|numeric|min:0|max:100',
    'nilai_uas' => 'required|numeric|min:0|max:100',
]);
```

## 7. Pertanyaan Tentang Menampilkan Nilai

### Pertanyaan

```text
Bagaimana aplikasi menampilkan nilai siswa?
```

Jawaban:

```text
Controller mengambil data nilai dari database menggunakan Model Nilai. Data 
tersebut biasanya difilter berdasarkan siswa, kelas, semester, atau tahun 
ajaran. Setelah data ditemukan, Controller mengirim data ke View untuk 
ditampilkan dalam bentuk tabel nilai atau raport.
```

Contoh logika:

```php
$nilai = Nilai::with(['siswa', 'mapel'])
    ->where('siswa_id', $siswaId)
    ->where('semester_id', $semesterId)
    ->get();
```

Artinya:

```text
Ambil nilai milik siswa tertentu pada semester tertentu, lengkap dengan data 
siswa dan mata pelajarannya.
```

## 8. Pertanyaan Tentang Hak Akses Nilai

### Pertanyaan

```text
Siapa yang boleh menginput nilai?
```

Jawaban:

```text
Yang boleh menginput nilai adalah user dengan hak akses tertentu, misalnya 
guru atau admin. Siswa tidak boleh menginput nilai, siswa hanya boleh melihat 
nilai miliknya.
```

### Pertanyaan

```text
Bagaimana sistem memastikan siswa hanya melihat nilainya sendiri?
```

Jawaban:

```text
Sistem mengambil data berdasarkan user yang sedang login. Jika user tersebut 
adalah siswa, maka Controller hanya mengambil nilai dengan siswa_id yang 
sesuai dengan akun siswa tersebut. Jadi siswa tidak bisa melihat nilai siswa 
lain.
```

## 9. Pertanyaan Tentang Mencegah Nilai Ganda

### Pertanyaan

```text
Bagaimana mencegah nilai yang sama diinput dua kali?
```

Jawaban:

```text
Sistem dapat memeriksa terlebih dahulu apakah nilai untuk siswa, mata 
pelajaran, semester, dan tahun ajaran yang sama sudah ada. Jika sudah ada, 
sistem bisa menolak input baru atau mengarahkan ke proses update.
```

Contoh logika:

```text
Cek apakah ada nilai dengan siswa_id, mapel_id, semester_id, dan tahun_ajaran_id yang sama.
Jika ada, jangan simpan data baru.
Jika belum ada, simpan sebagai data nilai baru.
```

Jawaban sidang:

```text
Untuk mencegah nilai ganda, aplikasi dapat mengecek kombinasi siswa, mata 
pelajaran, semester, dan tahun ajaran sebelum menyimpan. Jika data sudah ada, 
maka sistem tidak membuat data baru, tetapi bisa memperbarui data yang lama.
```

## 10. Pertanyaan Tentang Edit Nilai

### Pertanyaan

```text
Bagaimana proses edit nilai?
```

Jawaban:

```text
User memilih data nilai yang ingin diedit. Controller mengambil data nilai 
berdasarkan id dan menampilkannya di form edit. Setelah nilai diubah dan 
form dikirim, Controller memvalidasi data baru, lalu Model Nilai melakukan 
update ke database.
```

Alur:

```text
Klik edit
Controller mengambil data nilai berdasarkan id
View menampilkan form edit
User mengubah nilai
Controller memvalidasi data
Model mengupdate database
User kembali ke halaman nilai
```

## 11. Pertanyaan Tentang Hapus Nilai

### Pertanyaan

```text
Bagaimana proses hapus nilai?
```

Jawaban:

```text
User menekan tombol hapus pada data nilai. Request dikirim ke Controller. 
Controller mencari data nilai berdasarkan id. Jika data ditemukan, Model 
Nilai menghapus data tersebut dari database. Setelah berhasil, user 
dikembalikan ke halaman nilai.
```

## 12. Pertanyaan Tentang Predikat

Jika aplikasi memakai predikat seperti A, B, C, D, maka penguji bisa bertanya:

```text
Bagaimana menentukan predikat nilai?
```

Jawaban:

```text
Predikat ditentukan berdasarkan nilai akhir. Misalnya jika nilai akhir lebih 
dari atau sama dengan 90 maka predikat A, 80 sampai 89 predikat B, 70 sampai 
79 predikat C, dan di bawah 70 predikat D. Aturan ini bisa disesuaikan 
dengan ketentuan sekolah.
```

Contoh:

```text
90 - 100 = A
80 - 89  = B
70 - 79  = C
0  - 69  = D
```

## 13. Pertanyaan Tentang KKM

Jika aplikasi memakai KKM, siapkan jawaban ini.

### Pertanyaan

```text
Bagaimana menentukan siswa tuntas atau tidak tuntas?
```

Jawaban:

```text
Siswa dinyatakan tuntas jika nilai akhirnya lebih besar atau sama dengan 
KKM. Jika nilai akhir di bawah KKM, maka siswa dinyatakan belum tuntas.
```

Contoh:

```text
KKM = 75
Nilai akhir 80 = Tuntas
Nilai akhir 70 = Belum tuntas
```

## 14. Pertanyaan Tentang Raport

### Pertanyaan

```text
Apa hubungan nilai dengan raport?
```

Jawaban:

```text
Raport adalah hasil akhir dari kumpulan nilai siswa. Data nilai yang sudah 
diinput berdasarkan mata pelajaran, semester, dan tahun ajaran akan 
dikumpulkan lalu ditampilkan dalam bentuk raport.
```

### Pertanyaan

```text
Bagaimana raport mengambil data nilai?
```

Jawaban:

```text
Controller raport mengambil data nilai dari Model Nilai berdasarkan siswa, 
semester, dan tahun ajaran. Setelah data nilai terkumpul, data tersebut 
dikirim ke View raport untuk ditampilkan.
```

## 15. Contoh Jawaban Lengkap Logika Nilai

Gunakan jawaban ini jika penguji meminta penjelasan panjang:

```text
Pada aplikasi saya, logika nilai dimulai dari guru yang memilih siswa, mata 
pelajaran, semester, dan tahun ajaran, lalu menginput nilai. Setelah form 
dikirim, route akan memanggil method pada Controller nilai. Controller 
melakukan validasi agar nilai tidak kosong dan harus berupa angka. Setelah 
valid, Controller menghitung nilai akhir jika ada rumus perhitungan. 
Kemudian Controller menggunakan Model Nilai untuk menyimpan data ke tabel 
nilai. Data nilai tersebut memiliki relasi dengan siswa, mata pelajaran, 
kelas, semester, dan tahun ajaran. Saat raport ditampilkan, Controller 
mengambil data nilai berdasarkan siswa dan semester, kemudian mengirimkannya 
ke View raport.
```

## 16. Jika Ditanya Kenapa Nilai Pakai Relasi

Jawaban:

```text
Karena nilai tidak berdiri sendiri. Nilai harus terhubung dengan siswa, mata 
pelajaran, guru, kelas, semester, dan tahun ajaran. Dengan relasi, data nilai 
bisa ditampilkan lebih lengkap, misalnya nama siswa, nama mata pelajaran, 
dan semester.
```

## 17. Jika Tidak Ada Perhitungan Nilai Akhir

Kalau aplikasi hanya menyimpan nilai langsung, jawab seperti ini:

```text
Pada aplikasi saya, nilai tidak dihitung otomatis dengan rumus khusus. Sistem 
hanya menyimpan nilai yang diinput sesuai kebutuhan. Namun tetap ada validasi 
agar nilai yang masuk berupa angka dan sesuai format.
```

## 18. Kalimat Kunci Untuk Dihafal

```text
Nilai diinput oleh guru, diproses oleh Controller, divalidasi, lalu disimpan 
melalui Model Nilai ke database.
```

```text
Nilai memiliki relasi dengan siswa, mata pelajaran, kelas, semester, dan 
tahun ajaran.
```

```text
Raport menampilkan kumpulan nilai siswa berdasarkan semester dan tahun 
ajaran.
```

```text
Validasi nilai digunakan agar nilai tidak kosong, harus angka, dan sesuai 
rentang yang ditentukan.
```

## 19. Pola Jawaban Saat Panik

Kalau bingung, jawab dengan pola ini:

```text
Tujuannya adalah mengelola nilai siswa.
Alurnya dari form masuk ke Controller.
Controller melakukan validasi.
Jika ada rumus, nilai dihitung dulu.
Setelah itu disimpan lewat Model Nilai.
Data nilai direlasikan dengan siswa, mapel, kelas, semester, dan tahun ajaran.
Kemudian nilai bisa ditampilkan di halaman nilai atau raport.
```

