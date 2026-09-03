# Manual Book Penggunaan User

## Aplikasi E-Raport MADIN

Manual book ini digunakan sebagai panduan penggunaan aplikasi E-Raport MADIN Assyafi'iyah. Aplikasi ini membantu pengelolaan data akademik madrasah diniyah, mulai dari data santri, kelas, mata pelajaran, periode, input nilai, rekap raport, cetak raport, sampai kenaikan kelas dan kelulusan.

## 1. Hak Akses Pengguna

Aplikasi memiliki 3 jenis role pengguna.

| Role | Hak Akses Utama |
| --- | --- |
| `super_admin` | Mengelola seluruh data master, user, periode, kelas, mapel, santri, input nilai, rekap raport, cetak raport, serta kenaikan kelas dan kelulusan. |
| `guru` | Melihat dashboard dan menginput nilai mata pelajaran yang ditugaskan. |
| `wali_kelas` | Mengakses kelas yang menjadi tanggung jawabnya, mengelola rekap raport, absensi, sikap, catatan wali kelas, ranking, dan cetak raport. |

Catatan: guru juga dapat menjadi wali kelas apabila ditugaskan sebagai wali kelas pada periode aktif.

## 2. Login ke Aplikasi

1. Buka aplikasi melalui browser.
2. Masuk ke halaman `/login`.
3. Isi email dan password sesuai akun yang diberikan admin.
4. Klik tombol login.
5. Setelah berhasil login, user akan diarahkan ke halaman Dashboard.

Akun awal super admin dari seeder:

| Email | Password | Role |
| --- | --- | --- |
| `admin@madin.com` | `password` | `super_admin` |

Segera ubah password setelah login pertama.

## 3. Dashboard

Dashboard menampilkan ringkasan data aplikasi, seperti jumlah santri aktif, kelas, mata pelajaran, dan periode aktif.

Pada bagian sidebar, user dapat melihat periode aktif. Jika belum ada periode aktif, beberapa fitur akademik seperti input nilai, rekap raport, dan kenaikan kelas tidak dapat digunakan.

## 4. Menu untuk Super Admin

### 4.1 Manajemen User

Menu: **Manajemen User**

Fungsi menu ini adalah membuat dan mengelola akun pengguna.

Langkah menambah user:

1. Buka menu **Manajemen User**.
2. Klik tombol tambah user.
3. Isi nama, email, password, konfirmasi password, dan role.
4. Pilih role: `guru`, `wali_kelas`, atau `super_admin`.
5. Simpan data.

Ketentuan:

- Email harus unik.
- Password minimal 8 karakter.
- Admin tidak dapat menghapus akun yang sedang digunakan sendiri.
- User yang sudah terhubung dengan data lain, seperti pengajar, wali kelas, atau nilai, tidak dapat dihapus sebelum penugasan terkait dilepas.

### 4.2 Tahun Ajar / Periode

Menu: **Tahun Ajar / Periode**

Periode menjadi dasar input nilai, rekap raport, wali kelas, pengaturan mapel, dan kenaikan kelas.

Langkah membuat periode:

1. Buka menu **Tahun Ajar / Periode**.
2. Klik tambah periode.
3. Isi nama periode, semester, nama kepala madrasah, tanggal mulai, dan tanggal selesai.
4. Jika periode tersebut akan digunakan sekarang, aktifkan status periode.
5. Simpan data.

Ketentuan:

- Semester hanya dapat dipilih `ganjil` atau `genap`.
- Tanggal selesai harus setelah tanggal mulai.
- Hanya satu periode yang dapat aktif dalam satu waktu.
- Periode pertama otomatis menjadi periode aktif apabila belum ada periode lain.
- Menghapus periode aktif akan menonaktifkan periode, bukan menghapus data periode.

### 4.3 Mata Pelajaran

Menu: **Mata Pelajaran**

Fungsi menu ini adalah mengelola daftar mata pelajaran yang akan digunakan di kelas.

Data yang diisi:

| Kolom | Keterangan |
| --- | --- |
| Nama Mapel | Nama mata pelajaran. |
| Kategori | `umum`, `khusus`, atau `cabang`. |
| Tingkatan | `ula`, `wustho`, atau `all`. |
| Bobot Harian | Persentase nilai harian. |
| Bobot Ujian | Persentase nilai ujian. |

Ketentuan:

- Total bobot harian dan bobot ujian harus 100%.
- Mapel dengan tingkatan `all` dapat dipakai di semua tingkatan.
- Mapel dengan tingkatan `ula` hanya tersedia untuk kelas Ula.
- Mapel dengan tingkatan `wustho` hanya tersedia untuk kelas Wustho.

### 4.4 Data Kelas

Menu: **Data Kelas**

Fungsi menu ini adalah mengelola kelas.

Langkah menambah kelas:

1. Buka menu **Data Kelas**.
2. Klik tambah kelas.
3. Isi nama kelas dan tingkat.
4. Pilih wali kelas jika tersedia.
5. Simpan data.

Ketentuan tingkatan:

| Tingkat | Tingkatan |
| --- | --- |
| 1 sampai 3 | `ula` |
| 4 sampai 6 | `wustho` |

Catatan:

- Penambahan atau perubahan kelas membutuhkan periode aktif.
- Tahun ajar kelas mengikuti nama periode aktif.

### 4.5 Atur Wali Kelas

Menu: **Atur Wali Kelas**

Fungsi menu ini adalah menetapkan wali kelas untuk setiap kelas pada periode aktif.

Langkah mengatur wali kelas:

1. Buka menu **Atur Wali Kelas**.
2. Pilih kelas yang ingin diatur.
3. Pilih guru atau wali kelas yang akan ditugaskan.
4. Simpan perubahan.

Ketentuan:

- Satu user hanya dapat menjadi wali kelas pada satu kelas dalam periode yang sama.
- Jika ingin mengganti wali kelas, ubah penugasan pada kelas tersebut.
- Jika tidak ada periode aktif, penugasan wali kelas tidak dapat dilakukan.

### 4.6 Atur Mapel dan Guru Pengampu per Kelas

Menu: **Data Kelas**, lalu pilih pengaturan mapel pada kelas.

Fungsi menu ini adalah menentukan mapel yang berlaku pada kelas, guru pengampu, dan KKM.

Langkah mengatur mapel kelas:

1. Buka menu **Data Kelas**.
2. Pilih kelas.
3. Masuk ke pengaturan mapel kelas.
4. Centang mata pelajaran yang digunakan.
5. Pilih guru pengampu untuk setiap mapel.
6. Isi KKM setiap mapel.
7. Simpan pengaturan.

Ketentuan:

- Guru pengampu wajib dipilih untuk setiap mapel yang dicentang.
- KKM wajib diisi dengan angka 0 sampai 100.
- Mapel yang tampil menyesuaikan tingkatan kelas.
- Jika mapel tidak dicentang lagi, penugasan mapel pada periode aktif akan dilepas.

### 4.7 Data Santri

Menu: **Data Santri**

Fungsi menu ini adalah mengelola data santri dan biodata orang tua.

Data yang diisi:

| Data | Keterangan |
| --- | --- |
| NIS | Nomor induk santri, harus unik. |
| Nama lengkap | Nama santri sesuai data resmi. |
| Kelas | Kelas santri saat ini. |
| Tempat dan tanggal lahir | Identitas kelahiran santri. |
| Alamat | Alamat lengkap santri. |
| Nama ayah dan ibu | Data orang tua. |
| Pekerjaan ayah dan ibu | Opsional. |
| Nomor HP orang tua | Opsional. |
| Status | `aktif`, `lulus`, atau `pindah`. |

Langkah menambah santri:

1. Buka menu **Data Santri**.
2. Klik tambah santri.
3. Lengkapi seluruh data wajib.
4. Simpan data.

Catatan:

- Santri baru otomatis berstatus `aktif`.
- Data santri dapat difilter berdasarkan kelas.
- Hanya santri aktif yang diproses pada input nilai, rekap, dan kenaikan kelas.

### 4.8 Kenaikan Kelas dan Kelulusan

Menu: **Kenaikan & Kelulusan**

Menu ini hanya tersedia untuk `super_admin` dan hanya muncul saat periode aktif memiliki semester `genap`.

Langkah memproses kenaikan kelas:

1. Pastikan periode aktif adalah semester genap.
2. Buka menu **Kenaikan & Kelulusan**.
3. Pilih kelas asal.
4. Centang santri yang akan diproses.
5. Pilih aksi:
   - Naik kelas.
   - Lulus.
   - Lulus Ula dan lanjut Wustho.
   - Tinggal kelas.
6. Jika naik kelas atau lanjut Wustho, pilih kelas tujuan.
7. Simpan proses.

Ketentuan:

- Kenaikan kelas tidak dapat dilakukan pada semester ganjil.
- Kelas 3 Ula menggunakan aksi lulus Ula dan dapat dilanjutkan ke kelas Wustho.
- Kelulusan hanya berlaku untuk kelas akhir Ula atau Wustho.
- Santri yang lulus akan berubah status menjadi `lulus`.
- Riwayat kelas akan tersimpan saat proses dilakukan.

## 5. Menu Akademik

### 5.1 Input Nilai

Menu: **Input Nilai**

Fungsi menu ini adalah mengisi nilai harian dan nilai ujian santri.

Untuk guru:

1. Login sebagai guru.
2. Buka menu **Input Nilai**.
3. Pilih kelas dan mata pelajaran yang ditugaskan.
4. Isi nilai harian dan nilai ujian untuk setiap santri.
5. Klik simpan.

Untuk super admin:

1. Login sebagai super admin.
2. Buka menu **Input Nilai**.
3. Pilih kelas dan mata pelajaran.
4. Isi atau perbarui nilai.
5. Simpan data.

Ketentuan nilai:

- Nilai harian berada pada rentang 0 sampai 100.
- Nilai ujian wajib diisi dan berada pada rentang 0 sampai 100.
- Nilai akhir dihitung otomatis berdasarkan bobot mapel.
- Format angka Indonesia seperti `85,5` dapat digunakan.
- Guru hanya dapat menginput nilai mapel yang ditugaskan kepadanya.
- Mapel yang bukan bagian dari periode aktif tidak dapat diinput.

Predikat nilai:

| Nilai Akhir | Predikat |
| --- | --- |
| 85 sampai 100 | A |
| 75 sampai 84,99 | B |
| 60 sampai 74,99 | C |
| Di bawah 60 | D |

### 5.2 Laporan Rapot / Rekap Kelas

Menu untuk wali kelas: **Laporan Rapot**  
Menu untuk super admin: melalui **Data Kelas** lalu masuk ke rekap kelas.

Fungsi menu ini adalah melihat rekap nilai santri dalam satu kelas, mengisi absensi, sikap, catatan wali kelas, menghitung ranking, dan mencetak raport.

Langkah mengelola rekap:

1. Buka kelas yang menjadi tanggung jawab.
2. Masuk ke halaman rekap.
3. Periksa nilai setiap mapel.
4. Isi absensi:
   - Sakit.
   - Izin.
   - Alpha.
5. Isi nilai sikap:
   - Akhlaq.
   - Kerajinan.
   - Kedisiplinan.
   - Kerapihan.
6. Isi catatan wali kelas.
7. Simpan perubahan.
8. Klik hitung ulang ranking jika diperlukan.

Ketentuan:

- Wali kelas hanya dapat mengakses kelas yang ditugaskan pada periode aktif.
- Super admin dapat mengakses seluruh rekap kelas.
- Wali kelas dan super admin dapat mengisi absensi, sikap, dan catatan.
- Nilai mapel dapat diperbarui dari rekap sesuai hak akses pengampu mapel.
- Ranking dihitung berdasarkan total nilai akhir mapel.

### 5.3 Cetak Raport

Cetak raport tersedia pada halaman rekap kelas.

Jenis cetak:

| Jenis | Fungsi |
| --- | --- |
| Cetak per santri | Mencetak raport satu santri. |
| Cetak semua | Mencetak raport seluruh santri aktif dalam satu kelas. |

Langkah cetak per santri:

1. Buka halaman rekap kelas.
2. Pilih santri.
3. Klik tombol cetak raport.
4. Pilih jumlah salinan jika tersedia.
5. Sistem akan membuka file PDF.

Langkah cetak semua raport:

1. Buka halaman rekap kelas.
2. Klik tombol cetak semua raport.
3. Pilih jumlah salinan jika tersedia.
4. Sistem akan membuat PDF berisi seluruh raport santri aktif pada kelas tersebut.

Ketentuan penting:

- Raport tidak dapat dicetak jika masih ada nilai mapel yang belum lengkap.
- Jika cetak semua ditolak, sistem akan menampilkan nama santri yang nilainya belum lengkap.
- Jika mapel kelas belum diatur, raport tidak dapat dicetak.
- File logo raport menggunakan `public/logo.jpg`.

## 6. Edit Profil

Menu: **Edit Profil**

Fungsi menu ini adalah memperbarui data profil user yang sedang login.

Langkah penggunaan:

1. Buka menu **Edit Profil**.
2. Perbarui data yang diperlukan.
3. Simpan perubahan.

Gunakan menu ini untuk menjaga data akun tetap sesuai.

## 7. Logout

Untuk keluar dari aplikasi:

1. Klik menu **Logout** pada sidebar.
2. Sistem akan mengakhiri sesi login.
3. User akan diarahkan keluar dari area aplikasi.

Selalu logout setelah selesai menggunakan aplikasi, terutama pada komputer bersama.

## 8. Cek Nilai Publik

Halaman: `/cek-nilai`

Fitur ini digunakan oleh santri atau orang tua untuk melihat nilai tanpa login.

Langkah penggunaan:

1. Buka halaman `/cek-nilai`.
2. Masukkan NIS.
3. Masukkan nama lengkap santri.
4. Klik cek nilai.
5. Sistem akan menampilkan data raport sesuai periode aktif.

Ketentuan:

- NIS dan nama lengkap harus sesuai dengan data santri.
- Jika periode aktif belum ditetapkan, nilai tidak dapat ditampilkan.
- Jika data tidak ditemukan, periksa kembali ejaan nama lengkap dan NIS.
- Halaman ini dibatasi percobaannya untuk mencegah pencarian data secara berlebihan.

## 9. Alur Kerja yang Disarankan

### Awal Tahun Ajaran atau Semester

1. Super admin membuat atau mengaktifkan periode.
2. Super admin memastikan data user guru dan wali kelas sudah tersedia.
3. Super admin membuat atau memperbarui data kelas.
4. Super admin mengatur wali kelas.
5. Super admin membuat atau memperbarui mata pelajaran.
6. Super admin mengatur mapel, guru pengampu, dan KKM per kelas.
7. Super admin memperbarui data santri dan kelas santri.

### Selama Semester

1. Guru membuka menu input nilai.
2. Guru mengisi nilai harian dan ujian.
3. Wali kelas memantau kelengkapan nilai melalui rekap.
4. Wali kelas melengkapi absensi, sikap, dan catatan.

### Akhir Semester

1. Pastikan seluruh nilai mapel sudah lengkap.
2. Wali kelas memeriksa rekap kelas.
3. Wali kelas atau super admin menghitung ulang ranking.
4. Cetak raport per santri atau seluruh kelas.
5. Jika semester genap, super admin memproses kenaikan kelas atau kelulusan.

## 10. Pesan Error yang Sering Muncul

| Pesan / Kondisi | Penyebab | Solusi |
| --- | --- | --- |
| Tidak ada periode aktif | Belum ada periode yang diaktifkan. | Buka menu Tahun Ajar / Periode, lalu aktifkan salah satu periode. |
| Akses ditolak, Anda bukan pengajar mapel ini | User guru membuka mapel yang bukan tugasnya. | Pastikan guru pengampu sudah diatur pada mapel kelas. |
| Akses ditolak, Anda bukan wali kelas | User membuka rekap kelas yang bukan tanggung jawabnya. | Atur wali kelas pada periode aktif. |
| Nilai ujian wajib diisi | Kolom nilai ujian kosong. | Isi nilai ujian dengan angka 0 sampai 100. |
| Bobot harian dan ujian harus 100% | Total bobot mapel tidak valid. | Ubah bobot harian dan ujian sampai totalnya 100%. |
| Guru sudah menjadi wali kelas di kelas lain | User sudah ditugaskan sebagai wali kelas pada periode yang sama. | Pilih user lain atau hapus penugasan sebelumnya. |
| Raport belum bisa dicetak karena nilai belum lengkap | Masih ada mapel yang belum memiliki nilai ujian atau nilai akhir. | Lengkapi nilai mapel yang disebutkan sistem. |
| Kenaikan kelas hanya dapat dilakukan pada semester Genap | Periode aktif masih semester ganjil. | Aktifkan periode semester genap jika proses kenaikan memang sudah waktunya. |

## 11. Tips Penggunaan

- Pastikan periode aktif benar sebelum mulai input nilai.
- Buat data master secara berurutan: periode, user, kelas, mapel, santri, lalu penugasan mapel dan wali kelas.
- Jangan mengganti periode aktif saat guru masih menginput nilai untuk periode berjalan.
- Periksa kelengkapan mapel per kelas sebelum cetak raport.
- Gunakan nama lengkap santri secara konsisten agar fitur cek nilai publik mudah digunakan.
- Lakukan pengecekan rekap sebelum mencetak raport massal.

## 12. Ringkasan Menu

| Menu | Role yang Menggunakan | Fungsi |
| --- | --- | --- |
| Dashboard | Semua user login | Melihat ringkasan aplikasi. |
| Mata Pelajaran | Super admin | Mengelola data mapel dan bobot nilai. |
| Data Kelas | Super admin, wali kelas tertentu | Mengelola kelas dan akses laporan kelas. |
| Atur Wali Kelas | Super admin | Menetapkan wali kelas per periode. |
| Data Santri | Super admin | Mengelola data santri. |
| Kenaikan & Kelulusan | Super admin | Memproses naik kelas, lulus, atau tinggal kelas. |
| Tahun Ajar / Periode | Super admin | Mengelola periode aktif. |
| Input Nilai | Super admin, guru | Menginput nilai harian dan ujian. |
| Laporan Rapot | Wali kelas | Mengelola rekap raport kelas. |
| Manajemen User | Super admin | Mengelola akun pengguna. |
| Edit Profil | Semua user login | Mengubah profil user. |
| Logout | Semua user login | Keluar dari aplikasi. |
| Cek Nilai Publik | Santri/orang tua | Melihat nilai tanpa login. |

## 13. Penutup

Manual book ini dapat digunakan sebagai pedoman operasional harian untuk admin, guru, wali kelas, santri, dan orang tua. Gunakan urutan kerja yang disarankan agar data nilai, rekap, raport, dan kenaikan kelas tetap konsisten pada setiap periode.
