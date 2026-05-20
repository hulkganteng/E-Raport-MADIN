# Catatan Besar Belajar Sidang Skripsi

## Identitas Singkat Proyek

Judul aplikasi:

```text
E-Raport MADIN
```

Deskripsi singkat:

```text
E-Raport MADIN adalah aplikasi web berbasis Laravel untuk membantu proses pengelolaan raport Madrasah Diniyah, mulai dari data santri, kelas, mata pelajaran, periode pembelajaran, input nilai, rekap nilai, ranking, absensi, catatan wali kelas, kenaikan kelas, sampai cetak raport PDF.
```

Kalimat pembuka saat presentasi:

```text
Assalamu'alaikum warahmatullahi wabarakatuh.
Pada penelitian ini saya membangun aplikasi E-Raport MADIN, yaitu sistem informasi berbasis web yang digunakan untuk membantu pengelolaan nilai dan raport santri Madrasah Diniyah. Sistem ini dibuat karena proses pengolahan nilai secara manual berisiko menimbulkan keterlambatan, kesalahan perhitungan, dan kesulitan dalam rekapitulasi raport. Dengan aplikasi ini, admin, guru, wali kelas, dan kepala madrasah dapat bekerja sesuai hak akses masing-masing.
```

## Latar Belakang

Masalah utama yang diangkat:

- Pengelolaan nilai dan raport masih berpotensi dilakukan secara manual.
- Data santri, kelas, mapel, guru, absensi, dan nilai bisa tersebar di banyak file atau dokumen.
- Perhitungan nilai akhir, rata-rata, dan ranking membutuhkan ketelitian.
- Cetak raport membutuhkan format yang rapi dan konsisten.
- Hak akses pengguna perlu dibedakan agar data tidak diubah oleh pihak yang tidak berwenang.

Alasan sistem perlu dibuat:

- Mempercepat proses input dan rekap nilai.
- Mengurangi kesalahan perhitungan.
- Memudahkan wali kelas dalam melihat hasil nilai santri.
- Memudahkan pencetakan raport dalam format PDF.
- Menyimpan data secara lebih terstruktur di database.

Jawaban ringkas jika ditanya latar belakang:

```text
Latar belakang pembuatan aplikasi ini adalah kebutuhan madrasah untuk mengelola nilai dan raport santri secara lebih efektif. Jika dilakukan manual, proses input nilai, rekap, ranking, dan cetak raport membutuhkan waktu lama serta rawan kesalahan. Karena itu saya membuat sistem berbasis web agar setiap role, seperti admin, guru, wali kelas, dan kepala madrasah, dapat menjalankan tugasnya masing-masing secara terstruktur.
```

## Rumusan Masalah

Contoh rumusan masalah yang bisa dijelaskan saat sidang:

1. Bagaimana merancang sistem informasi raport Madrasah Diniyah berbasis web?
2. Bagaimana sistem dapat mengelola data santri, kelas, mata pelajaran, guru, periode, dan nilai?
3. Bagaimana sistem menghitung nilai akhir, rata-rata, dan ranking santri secara otomatis?
4. Bagaimana sistem membatasi hak akses sesuai role pengguna?
5. Bagaimana sistem menghasilkan raport dalam format PDF?

## Tujuan Penelitian

Tujuan utama:

- Membangun aplikasi raport digital untuk Madrasah Diniyah.
- Mempermudah admin dalam mengelola data master.
- Mempermudah guru dalam menginput nilai mapel.
- Mempermudah wali kelas dalam mengelola rekap raport, absensi, sikap, dan catatan.
- Mempermudah kepala madrasah dalam melihat laporan nilai.
- Menghasilkan raport PDF yang siap dicetak.

Jawaban ringkas:

```text
Tujuan penelitian ini adalah menghasilkan aplikasi E-Raport MADIN berbasis Laravel yang dapat membantu proses pengelolaan raport santri mulai dari data master, input nilai, rekapitulasi, ranking, sampai cetak raport PDF.
```

## Manfaat Penelitian

Manfaat bagi madrasah:

- Data akademik lebih rapi dan terpusat.
- Rekap nilai lebih cepat.
- Raport bisa dicetak dengan format yang konsisten.

Manfaat bagi guru:

- Guru hanya menginput nilai mapel yang ditugaskan.
- Nilai akhir dihitung otomatis berdasarkan bobot.

Manfaat bagi wali kelas:

- Dapat melihat rekap nilai seluruh santri di kelasnya.
- Dapat mengisi absensi, sikap, catatan wali kelas, dan keputusan.
- Dapat mencetak raport per santri atau satu kelas.

Manfaat bagi peneliti:

- Menerapkan ilmu pemrograman web, database, framework Laravel, autentikasi, otorisasi, dan pembuatan laporan PDF.

## Batasan Masalah

Batasan sistem:

- Sistem dibuat berbasis web menggunakan Laravel.
- Database utama menggunakan MySQL.
- Sistem fokus pada pengelolaan raport Madrasah Diniyah.
- Role utama terdiri dari `super_admin`, `guru`, `wali_kelas`,.
- Input nilai terdiri dari nilai harian dan nilai ujian.
- Nilai akhir dihitung berdasarkan bobot pada mata pelajaran.
- Cetak raport menggunakan PDF.
- Kenaikan kelas dilakukan pada periode aktif dan hanya untuk semester genap.

Jika ditanya kenapa ada batasan:

```text
Batasan masalah dibuat agar penelitian lebih fokus pada kebutuhan utama raport, yaitu pengelolaan data akademik, input nilai, rekap, ranking, dan cetak raport. Fitur di luar itu, seperti pembayaran, inventaris, atau presensi harian detail, tidak dimasukkan agar ruang lingkup penelitian tetap terukur.
```

## Teknologi Yang Digunakan

Teknologi utama:

- PHP 8.2 atau lebih baru.
- Laravel 12 sebagai framework backend.
- MySQL sebagai database.
- Blade sebagai template tampilan.
- Tailwind CSS untuk styling.
- Vite untuk build asset frontend.
- DomPDF atau renderer PDF berbasis Chrome untuk cetak raport.
- PHPUnit untuk testing.

Jawaban jika ditanya kenapa pakai Laravel:

```text
Saya menggunakan Laravel karena Laravel menyediakan struktur MVC yang rapi, fitur routing, migration, Eloquent ORM, authentication, middleware, validation, dan dukungan ekosistem yang baik. Dengan Laravel, pengembangan aplikasi menjadi lebih terstruktur dan mudah dipelihara.
```

Jawaban jika ditanya kenapa pakai MySQL:

```text
Saya menggunakan MySQL karena data pada aplikasi ini bersifat relasional, misalnya relasi santri dengan kelas, kelas dengan mata pelajaran, guru dengan mapel, dan nilai dengan periode. MySQL cocok untuk menyimpan data terstruktur seperti itu.
```

## Konsep MVC Pada Aplikasi

MVC adalah singkatan dari Model, View, Controller.

Penerapan di aplikasi:

- Model menyimpan representasi tabel database, contohnya `Santri`, `Kelas`, `Mapel`, `Periode`, `NilaiMapel`, `RekapNilai`.
- View menampilkan halaman kepada pengguna, contohnya file Blade di `resources/views`.
- Controller mengatur logika proses, contohnya `NilaiController`, `RekapController`, `KelasController`, dan `KenaikanKelasController`.

Jawaban sidang:

```text
Pada aplikasi ini saya menggunakan pola MVC Laravel. Model digunakan untuk berinteraksi dengan database, View digunakan untuk menampilkan halaman Blade, sedangkan Controller digunakan untuk memproses request, validasi, pengambilan data, penyimpanan data, dan pengiriman data ke tampilan.
```

## Role Dan Hak Akses

Role pada sistem:

| Role | Fungsi |
| --- | --- |
| `super_admin` | Mengelola seluruh data master dan semua fitur |
| `guru` | Menginput nilai mapel yang ditugaskan |
| `wali_kelas` | Mengelola rekap kelas yang menjadi tanggung jawabnya |
| `kepsek` | Melihat nilai dan rekap sesuai akses laporan |

Penjelasan:

- `super_admin` memiliki akses penuh.
- `guru` tidak dapat menginput semua nilai, hanya mapel yang ditugaskan.
- `wali_kelas` hanya dapat mengakses rekap kelas yang diampu pada periode aktif.
- `kepsek` dapat melihat nilai dan rekap sebagai pengawas akademik.

Jawaban jika ditanya keamanan akses:

```text
Pembatasan akses dilakukan menggunakan middleware role dan pengecekan tambahan pada controller. Contohnya, guru hanya dapat membuka halaman input nilai jika user tersebut merupakan guru pengampu pada data kelas_mapel. Wali kelas juga hanya dapat membuka rekap kelas jika tercatat sebagai wali kelas pada kelas dan periode tersebut.
```

## Struktur Data Utama

Tabel atau model penting:

| Model | Fungsi |
| --- | --- |
| `User` | Menyimpan data pengguna dan role |
| `Santri` | Menyimpan data santri |
| `Kelas` | Menyimpan data kelas |
| `Mapel` | Menyimpan data mata pelajaran |
| `Periode` | Menyimpan semester dan tahun ajaran |
| `KelasMapel` | Menghubungkan kelas, mapel, guru, dan periode |
| `WaliKelas` | Menghubungkan wali kelas, kelas, dan periode |
| `NilaiMapel` | Menyimpan nilai harian, ujian, akhir, dan predikat |
| `RekapNilai` | Menyimpan total nilai, rata-rata, ranking, sikap, dan catatan |
| `Absensi` | Menyimpan sakit, izin, dan alpha |
| `RiwayatKelas` | Menyimpan histori kenaikan, tinggal kelas, atau kelulusan |

Relasi penting:

- Satu kelas memiliki banyak santri.
- Satu santri memiliki banyak nilai mapel.
- Satu kelas memiliki banyak mapel melalui `kelas_mapel`.
- Satu mapel dapat digunakan di banyak kelas.
- Satu guru dapat ditugaskan ke banyak `kelas_mapel`.
- Satu periode menjadi dasar input nilai, rekap, wali kelas, dan kenaikan kelas.

## Alur Sistem Secara Umum

Alur penggunaan aplikasi:

1. User login.
2. Sistem mengecek role user.
3. Admin mengatur periode aktif.
4. Admin mengelola data master seperti santri, kelas, mapel, dan user.
5. Admin mengatur wali kelas dan mapel per kelas.
6. Guru menginput nilai sesuai tugas mengajar.
7. Sistem menghitung nilai akhir dan predikat.
8. Wali kelas membuka rekap kelas.
9. Wali kelas mengisi absensi, sikap, catatan, dan keputusan.
10. Sistem menghitung total nilai, rata-rata, dan ranking.
11. Raport dicetak dalam format PDF.
12. Pada akhir semester genap, admin dapat melakukan kenaikan kelas atau kelulusan.

## Alur Login

Alur login:

1. User membuka `/login`.
2. User memasukkan email dan password.
3. Sistem melakukan validasi kredensial.
4. Jika benar, user diarahkan ke dashboard.
5. Jika salah, sistem menampilkan pesan error.

Jawaban jika ditanya:

```text
Login digunakan untuk memastikan hanya pengguna terdaftar yang dapat mengakses sistem. Setelah login, sistem menggunakan role pengguna untuk menentukan menu dan fitur yang boleh diakses.
```

## Alur Periode Aktif

Periode aktif adalah semester atau tahun ajaran yang sedang berjalan.

Fungsi periode aktif:

- Menjadi dasar input nilai.
- Menjadi dasar rekap raport.
- Menjadi dasar penugasan wali kelas.
- Menjadi dasar penugasan mapel per kelas.
- Menjadi dasar kenaikan kelas.

Kenapa periode aktif penting:

```text
Periode aktif penting agar nilai dan rekap tidak tercampur antar semester atau tahun ajaran. Misalnya nilai semester ganjil dan genap harus dipisahkan agar histori akademik santri tetap jelas.
```

Jika ditanya bagaimana memastikan periode aktif:

```text
Sistem menggunakan middleware active_period dan pencarian data periode dengan is_active bernilai true. Fitur seperti input nilai, rekap, dan kenaikan kelas hanya berjalan jika ada periode aktif.
```

## Alur Input Nilai

Input nilai dilakukan oleh guru yang ditugaskan pada mapel tertentu.

Data yang diinput:

- Nilai harian.
- Nilai ujian.

Rumus nilai akhir:

```text
Nilai Akhir = ((Nilai Harian x Bobot Harian) + (Nilai Ujian x Bobot Ujian)) / Total Bobot
```

Contoh:

```text
Nilai harian = 80
Nilai ujian = 90
Bobot harian = 40
Bobot ujian = 60

Nilai akhir = ((80 x 40) + (90 x 60)) / 100
Nilai akhir = (3200 + 5400) / 100
Nilai akhir = 86
```

Predikat:

| Nilai Akhir | Predikat |
| --- | --- |
| >= 85 | A |
| >= 75 | B |
| >= 60 | C |
| < 60 | D |

Jawaban sidang:

```text
Pada proses input nilai, guru memilih kelas dan mata pelajaran yang menjadi tugasnya. Sistem menampilkan daftar santri aktif pada kelas tersebut. Guru mengisi nilai harian dan nilai ujian. Setelah disimpan, sistem menghitung nilai akhir berdasarkan bobot mapel dan menentukan predikat secara otomatis.
```

## Validasi Input Nilai

Validasi yang dilakukan:

- Nilai harus berupa angka.
- Nilai berada pada rentang 0 sampai 100.
- Nilai ujian wajib diisi jika nilai diproses.
- Guru hanya bisa menginput nilai mapel yang ditugaskan.
- Mapel harus termasuk periode aktif.

Jawaban jika ditanya kenapa ada validasi:

```text
Validasi digunakan agar data yang masuk ke database sesuai aturan. Tanpa validasi, nilai bisa kosong, melebihi 100, atau diinput oleh guru yang tidak berwenang.
```

## Alur Rekap Nilai

Rekap nilai dilakukan per kelas dan periode.

Data yang ditampilkan:

- Daftar santri.
- Nilai setiap mata pelajaran.
- Total nilai.
- Rata-rata.
- Ranking.
- Absensi sakit, izin, alpha.
- Sikap.
- Catatan wali kelas.
- Keputusan.

Perhitungan:

```text
Total Nilai = jumlah seluruh nilai akhir mapel
Rata-rata = total nilai / jumlah mapel yang memiliki nilai
Ranking = urutan berdasarkan total nilai tertinggi
```

Jawaban sidang:

```text
Rekap nilai dibuat berdasarkan nilai mapel santri pada periode aktif. Sistem menjumlahkan nilai akhir, menghitung rata-rata, kemudian mengurutkan santri berdasarkan total nilai untuk menentukan ranking.
```

## Alur Cetak Raport PDF

Cetak raport dapat dilakukan:

- Per santri.
- Seluruh santri dalam satu kelas.

Data yang masuk ke raport:

- Identitas santri.
- Identitas kelas dan periode.
- Nilai mapel.
- Predikat.
- Total nilai.
- Rata-rata.
- Ranking.
- Absensi.
- Sikap.
- Catatan wali kelas.
- Logo madrasah.

Jawaban jika ditanya:

```text
Cetak raport dibuat dalam format PDF agar hasilnya konsisten dan siap dicetak. Sistem mengambil data santri, nilai, rekap, absensi, dan periode, lalu merender tampilan raport menjadi dokumen PDF.
```

## Alur Kenaikan Kelas Dan Kelulusan

Kenaikan kelas dilakukan oleh `super_admin`.

Aturan utama:

- Membutuhkan periode aktif.
- Hanya dapat dilakukan pada semester genap.
- Santri dapat naik kelas.
- Santri dapat tinggal kelas.
- Santri kelas akhir Ula dapat lulus Ula atau lanjut Wustho.
- Santri kelas akhir Wustho dapat lulus.
- Riwayat kelas disimpan pada tabel `riwayat_kelas`.

Jawaban sidang:

```text
Fitur kenaikan kelas digunakan setelah nilai dan raport selesai diperiksa. Sistem menyimpan riwayat kelas santri agar histori akademik tetap tercatat. Untuk menjaga aturan akademik, proses kenaikan kelas hanya dapat dilakukan pada semester genap.
```

## Fitur Cek Nilai Publik

Sistem memiliki akses publik untuk cek nilai tanpa login.

Tujuan:

- Mempermudah santri atau wali santri melihat informasi nilai tertentu.
- Tidak perlu masuk sebagai admin atau guru.

Catatan jawaban:

```text
Walaupun ada akses publik, fitur tersebut tetap dibatasi hanya untuk pengecekan nilai, bukan untuk mengubah data. Proses perubahan data tetap membutuhkan login dan hak akses.
```

## Keamanan Sistem

Keamanan yang diterapkan:

- Login wajib untuk fitur internal.
- Role-based access control.
- Middleware `auth`.
- Middleware `role`.
- Middleware `active_period`.
- Validasi request.
- Pengecekan kepemilikan akses di controller.
- Transaksi database untuk proses penting.

Contoh:

- Guru tidak bisa input nilai mapel yang bukan tugasnya.
- Wali kelas tidak bisa membuka rekap kelas lain.
- Admin mengelola data master.
- Kenaikan kelas hanya untuk `super_admin`.

Jawaban jika ditanya keamanan:

```text
Keamanan sistem diterapkan melalui autentikasi, middleware role, validasi input, dan pengecekan hak akses pada controller. Jadi akses tidak hanya dibatasi dari tampilan menu, tetapi juga dari sisi backend.
```

## Transaksi Database

Transaksi database digunakan pada proses yang melibatkan lebih dari satu operasi.

Contoh:

- Simpan nilai beberapa santri.
- Update rekap.
- Proses kenaikan kelas dan simpan riwayat.

Kenapa transaksi penting:

```text
Transaksi digunakan agar data tetap konsisten. Jika salah satu proses gagal, perubahan dapat dibatalkan sehingga database tidak menyimpan data setengah jadi.
```

Jawaban sidang:

```text
Saya menggunakan transaksi database pada proses penting seperti penyimpanan nilai dan kenaikan kelas. Tujuannya agar jika terjadi error di tengah proses, data tidak tersimpan sebagian dan integritas data tetap terjaga.
```

## Hal Yang Harus Dikuasai Sebelum Sidang

Wajib hafal:

- Masalah yang diselesaikan aplikasi.
- Tujuan aplikasi.
- Role pengguna.
- Alur input nilai.
- Rumus nilai akhir.
- Alur rekap dan ranking.
- Fungsi periode aktif.
- Relasi tabel utama.
- Alasan memilih Laravel dan MySQL.
- Cara sistem membatasi akses.
- Cara sistem mencetak PDF.

Wajib bisa demo:

- Login sebagai admin.
- Membuat atau mengaktifkan periode.
- Menambah data santri.
- Menambah kelas.
- Menambah mapel.
- Mengatur wali kelas.
- Mengatur mapel dan guru pengampu.
- Login sebagai guru atau akses input nilai.
- Input nilai.
- Membuka rekap.
- Mengisi absensi dan catatan.
- Cetak raport PDF.
- Menjelaskan kenaikan kelas.

## Skenario Demo Saat Sidang

Skenario demo yang aman:

1. Buka halaman login.
2. Login sebagai `super_admin`.
3. Tunjukkan dashboard.
4. Tunjukkan menu periode dan jelaskan periode aktif.
5. Tunjukkan data santri, kelas, dan mapel.
6. Tunjukkan pengaturan mapel per kelas dan guru pengampu.
7. Masuk ke menu nilai.
8. Tunjukkan form input nilai.
9. Jelaskan rumus nilai akhir.
10. Simpan nilai contoh jika diperlukan.
11. Buka rekap kelas.
12. Jelaskan total, rata-rata, ranking, absensi, sikap, dan catatan.
13. Cetak raport PDF.
14. Jelaskan fitur kenaikan kelas.

Kalimat saat demo:

```text
Pada bagian ini admin dapat mengatur periode aktif. Periode aktif digunakan sebagai dasar semua proses akademik, sehingga nilai semester lain tidak tercampur.
```

```text
Pada bagian input nilai, sistem hanya menampilkan mapel sesuai penugasan guru. Nilai akhir dihitung otomatis berdasarkan bobot harian dan ujian.
```

```text
Pada bagian rekap, wali kelas dapat melihat nilai seluruh santri, mengisi absensi, sikap, catatan, dan mencetak raport.
```

## Pertanyaan Yang Mungkin Ditanyakan Dosen Penguji

### 1. Apa masalah utama yang diselesaikan oleh aplikasi ini?

Jawaban:

```text
Masalah utama yang diselesaikan adalah proses pengelolaan nilai dan raport yang jika dilakukan manual membutuhkan waktu lama, rawan salah hitung, dan sulit direkap. Aplikasi ini membantu mengelola data santri, nilai, rekap, ranking, dan cetak raport secara lebih terstruktur.
```

### 2. Mengapa memilih aplikasi berbasis web?

Jawaban:

```text
Karena aplikasi berbasis web dapat diakses dari berbagai perangkat melalui browser, tidak perlu instalasi di setiap komputer, dan lebih mudah dikelola secara terpusat.
```

### 3. Mengapa menggunakan Laravel?

Jawaban:

```text
Laravel dipilih karena memiliki struktur MVC yang jelas, mendukung routing, migration, Eloquent ORM, middleware, validation, authentication, dan ekosistem yang memudahkan pengembangan aplikasi web.
```

### 4. Mengapa menggunakan MySQL?

Jawaban:

```text
Karena data dalam sistem ini memiliki relasi yang kuat, seperti santri dengan kelas, kelas dengan mapel, guru dengan mapel, dan nilai dengan periode. MySQL cocok untuk data relasional seperti itu.
```

### 5. Apa fungsi periode aktif?

Jawaban:

```text
Periode aktif digunakan sebagai dasar proses akademik yang sedang berjalan, seperti input nilai, rekap raport, pengaturan wali kelas, pengaturan mapel, dan kenaikan kelas. Dengan periode aktif, data antar semester tidak tercampur.
```

### 6. Bagaimana cara sistem menghitung nilai akhir?

Jawaban:

```text
Nilai akhir dihitung dari nilai harian dan nilai ujian berdasarkan bobot pada mata pelajaran. Rumusnya adalah nilai harian dikali bobot harian ditambah nilai ujian dikali bobot ujian, lalu dibagi total bobot.
```

### 7. Bagaimana cara menentukan predikat?

Jawaban:

```text
Predikat ditentukan dari nilai akhir. Jika nilai akhir 85 ke atas mendapat A, 75 ke atas mendapat B, 60 ke atas mendapat C, dan di bawah 60 mendapat D.
```

### 8. Bagaimana cara menghitung ranking?

Jawaban:

```text
Ranking dihitung berdasarkan total nilai akhir seluruh mapel santri dalam satu kelas dan periode. Sistem mengurutkan total nilai dari tertinggi ke terendah, lalu memberikan nomor ranking.
```

### 9. Apa perbedaan nilai mapel dan rekap nilai?

Jawaban:

```text
Nilai mapel menyimpan nilai per mata pelajaran, seperti nilai harian, ujian, nilai akhir, dan predikat. Rekap nilai menyimpan hasil rangkuman santri, seperti total nilai, rata-rata, ranking, sikap, catatan wali kelas, dan keputusan.
```

### 10. Bagaimana sistem membatasi akses guru?

Jawaban:

```text
Guru hanya dapat menginput nilai pada mapel yang ditugaskan melalui data kelas_mapel. Sistem mengecek apakah user yang login sama dengan guru pengampu mapel tersebut.
```

### 11. Bagaimana sistem membatasi akses wali kelas?

Jawaban:

```text
Wali kelas hanya dapat mengakses rekap kelas jika user tersebut tercatat sebagai wali kelas pada kelas dan periode aktif. Jika tidak sesuai, sistem menolak akses.
```

### 12. Apa fungsi middleware?

Jawaban:

```text
Middleware digunakan sebagai penyaring request sebelum masuk ke controller. Pada aplikasi ini middleware digunakan untuk memastikan user sudah login, mengecek role, dan memastikan ada periode aktif.
```

### 13. Apa itu migration?

Jawaban:

```text
Migration adalah fitur Laravel untuk membuat dan mengubah struktur tabel database melalui kode. Dengan migration, struktur database lebih mudah dilacak dan dipindahkan ke komputer lain.
```

### 14. Apa itu seeder?

Jawaban:

```text
Seeder digunakan untuk mengisi data awal ke database, misalnya akun super admin dan periode awal. Ini membantu saat instalasi pertama agar aplikasi langsung bisa digunakan.
```

### 15. Apa itu Eloquent ORM?

Jawaban:

```text
Eloquent ORM adalah fitur Laravel yang memudahkan interaksi dengan database menggunakan model, sehingga query database bisa ditulis lebih rapi dan sesuai relasi antar tabel.
```

### 16. Apa fungsi validasi?

Jawaban:

```text
Validasi digunakan untuk memastikan data yang masuk sesuai aturan, misalnya nilai harus angka 0 sampai 100, role harus sesuai, dan data wajib tidak boleh kosong.
```

### 17. Bagaimana jika guru memasukkan nilai lebih dari 100?

Jawaban:

```text
Sistem akan menolak input tersebut karena nilai divalidasi harus berada pada rentang 0 sampai 100.
```

### 18. Bagaimana jika tidak ada periode aktif?

Jawaban:

```text
Fitur seperti input nilai, rekap, dan kenaikan kelas tidak dapat digunakan. User akan diarahkan atau diberi pesan bahwa periode aktif belum tersedia.
```

### 19. Mengapa kenaikan kelas hanya semester genap?

Jawaban:

```text
Karena secara akademik kenaikan kelas biasanya dilakukan pada akhir tahun ajaran atau setelah semester genap. Pembatasan ini dibuat agar proses kenaikan kelas tidak dilakukan pada semester yang belum tepat.
```

### 20. Apa fungsi riwayat kelas?

Jawaban:

```text
Riwayat kelas digunakan untuk menyimpan histori perpindahan atau status santri, seperti naik kelas, tinggal kelas, lulus, atau lanjut ke jenjang berikutnya.
```

### 21. Bagaimana sistem mencetak raport?

Jawaban:

```text
Sistem mengambil data santri, periode, nilai mapel, rekap, absensi, dan logo, lalu merender tampilan raport menjadi PDF. Raport dapat dicetak per santri atau seluruh santri dalam satu kelas.
```

### 22. Apa kelebihan aplikasi ini dibanding cara manual?

Jawaban:

```text
Kelebihannya adalah data lebih terstruktur, perhitungan nilai otomatis, ranking otomatis, hak akses lebih jelas, rekap lebih cepat, dan raport bisa dicetak dengan format konsisten.
```

### 23. Apa kekurangan aplikasi ini?

Jawaban:

```text
Kekurangannya, aplikasi masih berfokus pada raport dan belum mencakup seluruh administrasi madrasah seperti pembayaran, presensi harian detail, atau integrasi notifikasi ke wali santri.
```

### 24. Apa pengembangan selanjutnya?

Jawaban:

```text
Pengembangan selanjutnya dapat berupa fitur notifikasi WhatsApp, import data Excel, backup otomatis, presensi harian lebih detail, portal wali santri, dan grafik perkembangan nilai santri.
```

### 25. Bagaimana menjaga data agar tidak duplikat?

Jawaban:

```text
Data dijaga melalui relasi tabel, validasi, dan penggunaan updateOrCreate pada beberapa proses. Dengan cara itu, data seperti nilai santri pada mapel dan periode tertentu dapat diperbarui tanpa membuat duplikasi yang tidak perlu.
```

### 26. Bagaimana jika terjadi error saat menyimpan banyak nilai?

Jawaban:

```text
Pada proses penting, sistem menggunakan transaksi database. Jika terjadi error, proses dapat dibatalkan sehingga data tidak tersimpan sebagian.
```

### 27. Apa fungsi tabel kelas_mapel?

Jawaban:

```text
Tabel kelas_mapel digunakan untuk menghubungkan kelas, mata pelajaran, guru pengampu, dan periode. Dengan tabel ini, sistem tahu mapel apa yang berlaku di kelas tertentu dan siapa guru yang boleh menginput nilainya.
```

### 28. Apa fungsi tabel wali_kelas?

Jawaban:

```text
Tabel wali_kelas digunakan untuk mencatat user yang menjadi wali kelas pada kelas dan periode tertentu. Ini penting karena wali kelas dapat berubah setiap periode.
```

### 29. Kenapa data nilai memakai periode_id?

Jawaban:

```text
Karena nilai santri harus dipisahkan berdasarkan semester atau tahun ajaran. Dengan periode_id, nilai semester sebelumnya tetap tersimpan dan tidak tertimpa oleh nilai periode baru.
```

### 30. Apakah aplikasi ini responsive?

Jawaban:

```text
Ya, tampilan dibuat responsive agar dapat digunakan pada desktop, tablet, dan mobile. Beberapa halaman menggunakan pola tabel untuk desktop dan card untuk mobile agar tetap nyaman dibaca.
```

### 31. Apa fungsi dashboard?

Jawaban:

```text
Dashboard menampilkan ringkasan data seperti jumlah santri aktif, kelas, mapel, dan periode aktif. Tujuannya agar pengguna langsung melihat gambaran umum kondisi data.
```

### 32. Apa bedanya role guru dan wali kelas?

Jawaban:

```text
Guru bertugas menginput nilai mata pelajaran yang diajarkan. Wali kelas bertugas mengelola rekap kelas, mengisi absensi, sikap, catatan wali kelas, dan mencetak raport.
```

### 33. Mengapa kepala madrasah diberi akses?

Jawaban:

```text
Kepala madrasah membutuhkan akses untuk melihat laporan nilai dan rekap sebagai bentuk pengawasan akademik, tetapi tidak selalu harus mengelola data master seperti admin.
```

### 34. Apa peran super admin?

Jawaban:

```text
Super admin berperan sebagai pengelola utama sistem, mulai dari user, santri, kelas, mapel, periode, pengaturan wali kelas, pengaturan mapel, rekap, cetak raport, dan kenaikan kelas.
```

### 35. Bagaimana jika wali kelas berubah di periode berikutnya?

Jawaban:

```text
Karena wali kelas disimpan berdasarkan kelas dan periode, wali kelas dapat diganti pada periode baru tanpa menghapus data wali kelas pada periode sebelumnya.
```

### 36. Bagaimana jika guru pengampu berubah?

Jawaban:

```text
Guru pengampu disimpan pada data kelas_mapel berdasarkan periode. Jadi perubahan guru dapat dilakukan untuk periode tertentu tanpa mengganggu data periode lain.
```

### 37. Apa alasan nilai harian boleh kosong tetapi ujian wajib?

Jawaban:

```text
Pada implementasi ini nilai harian dapat dianggap 0 jika kosong, sedangkan nilai ujian wajib diisi agar sistem tahu bahwa nilai santri memang ingin diproses. Ini juga mencegah data nilai kosong tersimpan tanpa sengaja.
```

### 38. Bagaimana sistem menangani format angka Indonesia?

Jawaban:

```text
Sistem memiliki parser angka yang dapat membaca format koma sebagai desimal Indonesia, lalu mengubahnya ke format angka yang dapat dihitung oleh program.
```

### 39. Apakah sistem bisa digunakan multi-user?

Jawaban:

```text
Ya, sistem mendukung banyak user dengan role berbeda. Setiap user login dengan akun masing-masing dan aksesnya dibatasi sesuai role.
```

### 40. Mengapa menggunakan PDF untuk raport?

Jawaban:

```text
PDF dipilih karena formatnya stabil untuk dicetak, tampilannya konsisten di berbagai perangkat, dan cocok untuk dokumen resmi seperti raport.
```

## Pertanyaan Teknis Coding Yang Mungkin Muncul

### Apa itu Controller?

```text
Controller adalah bagian yang memproses request dari user. Controller mengambil data dari model, menjalankan validasi atau logika, lalu mengirim data ke view.
```

### Apa itu Model?

```text
Model adalah representasi tabel database di Laravel. Model digunakan untuk mengambil, menyimpan, mengubah, dan menghapus data.
```

### Apa itu View?

```text
View adalah bagian tampilan yang dilihat user. Pada Laravel, view biasanya dibuat menggunakan Blade.
```

### Apa itu Route?

```text
Route adalah penghubung antara URL dengan controller atau fungsi tertentu. Contohnya URL /nilai diarahkan ke NilaiController.
```

### Apa itu Blade?

```text
Blade adalah template engine Laravel untuk membuat tampilan HTML yang bisa menerima data dari controller.
```

### Apa itu Middleware?

```text
Middleware adalah lapisan pemeriksa request, misalnya memastikan user sudah login atau memiliki role tertentu sebelum masuk ke halaman.
```

### Apa itu updateOrCreate?

```text
updateOrCreate adalah method Eloquent untuk mencari data berdasarkan kondisi tertentu. Jika data ada, maka diperbarui. Jika tidak ada, maka dibuat baru.
```

### Apa itu firstOrCreate?

```text
firstOrCreate adalah method Eloquent untuk mencari data pertama berdasarkan kondisi. Jika tidak ditemukan, maka data baru dibuat.
```

### Apa itu DB transaction?

```text
DB transaction adalah mekanisme untuk memastikan beberapa proses database berhasil semuanya atau gagal semuanya. Ini menjaga konsistensi data.
```

## Pertanyaan Metodologi Yang Mungkin Muncul

### Metode pengembangan apa yang digunakan?

Jika di skripsi memakai waterfall, jawab:

```text
Metode pengembangan yang digunakan adalah waterfall, karena prosesnya dilakukan secara bertahap mulai dari analisis kebutuhan, desain sistem, implementasi, pengujian, dan pemeliharaan.
```

Jika memakai prototype, jawab:

```text
Metode pengembangan yang digunakan adalah prototype, karena sistem dibuat berdasarkan kebutuhan pengguna dan dapat diperbaiki melalui umpan balik sampai sesuai dengan kebutuhan madrasah.
```

Catatan:

```text
Sesuaikan jawaban ini dengan metode yang benar-benar tertulis di skripsi.
```

### Pengujian apa yang dilakukan?

Jawaban aman:

```text
Pengujian dilakukan untuk memastikan setiap fitur berjalan sesuai kebutuhan, seperti login, manajemen data master, input nilai, rekap, cetak raport, dan kenaikan kelas. Pengujian juga dilakukan pada validasi input dan hak akses user.
```

Jika ditanya black box:

```text
Black box testing digunakan untuk menguji fungsi sistem berdasarkan input dan output tanpa melihat kode program. Contohnya, saat nilai lebih dari 100 dimasukkan, sistem harus menolak input tersebut.
```

## Contoh Jawaban Saat Tidak Tahu

Gunakan jawaban yang tenang:

```text
Untuk bagian tersebut saya belum mengimplementasikan secara penuh, tetapi konsep pengembangannya dapat dilakukan dengan menambahkan tabel atau modul baru dan menghubungkannya dengan data yang sudah ada.
```

Atau:

```text
Pada penelitian ini fitur tersebut belum menjadi ruang lingkup utama. Fokus penelitian saya adalah pengelolaan raport, mulai dari data master, input nilai, rekap, ranking, sampai cetak PDF.
```

Jangan menjawab:

```text
Tidak tahu.
```

Lebih baik jawab:

```text
Sejauh implementasi saya, bagian tersebut belum tersedia. Namun secara teknis dapat dikembangkan pada versi berikutnya.
```

## Bagian Yang Sering Dikritik Penguji

Siapkan jawaban untuk hal berikut:

- Kenapa sistem ini diperlukan?
- Apa bedanya dengan Excel?
- Apakah data aman?
- Bagaimana jika guru salah input nilai?
- Bagaimana jika periode berganti?
- Bagaimana jika wali kelas berubah?
- Bagaimana jika santri pindah kelas?
- Bagaimana jika tidak ada internet?
- Apakah sistem sudah diuji?
- Apakah bisa dipakai banyak madrasah?
- Apakah sistem mendukung backup?
- Apakah sistem bisa import Excel?
- Apa kekurangan sistem?

Jawaban untuk perbandingan dengan Excel:

```text
Excel memang bisa digunakan untuk menghitung nilai, tetapi aplikasi ini lebih terstruktur karena memiliki database, login, pembagian role, validasi, relasi data, histori periode, rekap otomatis, dan cetak raport dengan format yang seragam.
```

Jawaban jika ditanya salah input:

```text
Jika guru salah input, nilai dapat diperbaiki selama user memiliki hak akses pada mapel tersebut. Sistem menggunakan update data, bukan membuat data baru setiap kali nilai diperbaiki.
```

Jawaban jika ditanya backup:

```text
Pada sistem saat ini backup otomatis belum menjadi fitur utama, tetapi karena data tersimpan di MySQL, backup dapat dilakukan melalui fasilitas database. Untuk pengembangan berikutnya, dapat dibuat fitur backup dari dashboard admin.
```

Jawaban jika ditanya internet:

```text
Jika aplikasi dipasang di server lokal madrasah, sistem dapat digunakan melalui jaringan lokal. Jika dipasang di hosting online, maka membutuhkan koneksi internet.
```

## Kelebihan Sistem

- Menggunakan role pengguna.
- Data terpusat di database.
- Input nilai lebih terkontrol.
- Nilai akhir dan predikat otomatis.
- Rekap, rata-rata, dan ranking otomatis.
- Raport dapat dicetak PDF.
- Mendukung periode aktif.
- Mendukung riwayat kelas.
- Tampilan responsive.

## Kekurangan Sistem

- Belum mencakup pembayaran atau administrasi keuangan.
- Belum ada import data Excel.
- Belum ada notifikasi WhatsApp.
- Backup otomatis belum menjadi fitur utama.
- Belum ada portal lengkap untuk wali santri.
- Fitur laporan statistik nilai masih bisa dikembangkan.

## Rencana Pengembangan

Pengembangan lanjutan:

- Import dan export Excel.
- Notifikasi WhatsApp untuk wali santri.
- Portal wali santri.
- Grafik perkembangan nilai.
- Backup dan restore database dari dashboard.
- Log aktivitas pengguna.
- Tanda tangan digital kepala madrasah.
- Pengaturan template raport lebih fleksibel.

## Hafalan Cepat 1 Menit

```text
Aplikasi saya adalah E-Raport MADIN, yaitu sistem informasi raport Madrasah Diniyah berbasis Laravel. Sistem ini dibuat untuk membantu pengelolaan data santri, kelas, mapel, periode, input nilai, rekap, ranking, absensi, catatan wali kelas, kenaikan kelas, dan cetak raport PDF. Aplikasi memiliki role super_admin, guru, wali_kelas, dan kepsek. Guru hanya bisa menginput nilai mapel yang ditugaskan, wali kelas mengelola rekap kelasnya, dan admin mengelola data master. Nilai akhir dihitung dari nilai harian dan ujian berdasarkan bobot mapel, lalu sistem menentukan predikat, total, rata-rata, dan ranking secara otomatis.
```

## Hafalan Cepat 3 Menit

```text
E-Raport MADIN adalah aplikasi web berbasis Laravel yang saya bangun untuk membantu proses pengelolaan raport Madrasah Diniyah. Permasalahan yang diangkat adalah proses pengolahan nilai yang jika dilakukan manual dapat memakan waktu lama, rawan salah hitung, dan sulit direkap.

Sistem ini memiliki beberapa role, yaitu super_admin, guru, wali_kelas, dan kepsek. Super admin mengelola data master seperti user, santri, kelas, mapel, periode, wali kelas, dan pengaturan mapel. Guru menginput nilai berdasarkan mapel yang ditugaskan. Wali kelas mengelola rekap kelas, absensi, sikap, catatan, dan cetak raport. Kepala madrasah dapat melihat nilai dan rekap sebagai pengawasan.

Proses utama dimulai dari pengaturan periode aktif. Setelah itu admin mengatur data kelas, santri, mapel, guru pengampu, dan wali kelas. Guru kemudian menginput nilai harian dan nilai ujian. Sistem menghitung nilai akhir berdasarkan bobot mapel dan menentukan predikat secara otomatis. Rekap nilai menghitung total, rata-rata, dan ranking santri. Raport dapat dicetak dalam bentuk PDF, baik per santri maupun satu kelas.

Keamanan sistem diterapkan dengan login, middleware role, validasi input, dan pengecekan akses di controller. Data disimpan dalam database MySQL dengan relasi antar tabel, seperti santri, kelas, mapel, periode, nilai_mapel, rekap_nilai, absensi, dan riwayat_kelas.
```

## Checklist Sebelum Sidang

- Bisa login ke aplikasi.
- Database sudah berisi data contoh.
- Periode aktif sudah tersedia.
- Minimal ada satu kelas.
- Minimal ada beberapa santri aktif.
- Minimal ada beberapa mapel.
- Guru pengampu sudah diatur.
- Wali kelas sudah diatur.
- Nilai contoh sudah ada.
- Rekap sudah bisa dibuka.
- PDF raport bisa dicetak.
- File logo tersedia di `public/logo.jpg`.
- Siapkan jawaban kelebihan dan kekurangan.
- Siapkan jawaban pengembangan sistem.
- Siapkan demo tanpa terlalu lama.

## Tips Menjawab Sidang

- Jawab singkat dulu, baru jelaskan jika diminta.
- Jangan terlalu banyak istilah teknis tanpa menjelaskan.
- Hubungkan jawaban dengan kebutuhan madrasah.
- Jika ditanya kode, jelaskan alurnya.
- Jika ditanya database, jelaskan relasinya.
- Jika ditanya kekurangan, jawab jujur dan beri rencana pengembangan.
- Jangan panik jika dosen mengkritik, karena kritik biasanya untuk melihat pemahaman.

Kalimat penutup:

```text
Demikian presentasi dari saya. Aplikasi ini masih dapat dikembangkan lebih lanjut, tetapi fitur utama untuk pengelolaan raport Madrasah Diniyah sudah mencakup data master, input nilai, rekap, ranking, cetak PDF, dan kenaikan kelas. Terima kasih.
```
