# E-Raport MADIN

E-Raport MADIN adalah aplikasi web berbasis Laravel untuk mengelola raport Madrasah Diniyah. Aplikasi ini membantu admin, guru, dan wali kelas dalam mengatur data santri, kelas, mata pelajaran, periode pembelajaran, input nilai, rekap raport, ranking, absensi, catatan wali kelas, kenaikan kelas, serta cetak raport dalam format PDF.

## Ringkasan Fitur

- Login dan manajemen pengguna dengan role `super_admin`, `guru`, dan `wali_kelas`.
- Dashboard statistik jumlah santri aktif, kelas, mapel, dan periode aktif.
- Manajemen periode semester/tahun ajaran, termasuk aktivasi satu periode yang sedang berjalan.
- Manajemen data santri lengkap dengan status aktif/nonaktif dan data biodata yang sudah digabung ke tabel santri.
- Manajemen kelas, wali kelas per periode, dan riwayat kelas santri.
- Manajemen mata pelajaran dengan kategori, tingkatan, serta bobot nilai harian dan ujian.
- Pengaturan mapel per kelas dan guru pengampu per periode.
- Input nilai per mapel oleh guru yang ditugaskan.
- Rekap nilai per kelas, termasuk nilai mapel, sikap, absensi, catatan wali kelas, total nilai, rata-rata, dan ranking.
- Cetak raport per santri atau seluruh santri dalam satu kelas ke PDF.
- Fitur kenaikan kelas dan kelulusan untuk periode aktif.
- Tampilan responsif untuk desktop, tablet, dan mobile.

## Teknologi

- PHP `^8.2`
- Laravel `^12.0`
- MySQL sebagai database utama
- Vite
- Tailwind CSS
- DomPDF dan renderer PDF berbasis Chrome untuk cetak raport
- PHPUnit untuk testing

## Struktur Folder Penting

```text
app/Http/Controllers   Controller utama aplikasi
app/Models             Model data seperti Santri, Kelas, Mapel, Periode, NilaiMapel, RekapNilai
app/Support            Helper cetak PDF dan format raport Arab
database/migrations    Struktur tabel database
database/seeders       Data awal aplikasi
resources/views        Tampilan Blade
resources/css          Styling aplikasi
resources/js           Entry JavaScript aplikasi
routes/web.php         Daftar route web
public/logo.jpg        Logo yang dipakai pada raport
```

## Instalasi

1. Install dependency PHP:

```bash
composer install
```

2. Install dependency frontend:

```bash
npm install
```

3. Salin file environment:

```bash
cp .env.example .env
```

Pada Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

4. Generate application key:

```bash
php artisan key:generate
```

5. Siapkan database MySQL.

Buat database baru di MySQL, misalnya:

```sql
CREATE DATABASE e_raport_madin;
```

Lalu sesuaikan konfigurasi database pada file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=e_raport_madin
DB_USERNAME=root
DB_PASSWORD=
```

6. Jalankan migrasi dan seeder:

```bash
php artisan migrate --seed
```

7. Build asset frontend:

```bash
npm run build
```

## Menjalankan Aplikasi

Untuk development, jalankan server Laravel:

```bash
php artisan serve
```

Jika ingin menjalankan Vite untuk asset development:

```bash
npm run dev
```

Aplikasi biasanya dapat dibuka di:

```text
http://127.0.0.1:8000
```

## Akun Awal

Seeder membuat akun super admin berikut:

```text
Email    : admin@madin.com
Password : password
Role     : super_admin
```

Segera ganti password setelah login pertama.

## Cara Penggunaan

### 1. Login

Buka halaman `/login`, lalu masuk menggunakan akun yang tersedia. Halaman utama akan diarahkan ke dashboard.

### 2. Atur Periode

Masuk sebagai `super_admin`, buka menu periode, lalu buat atau aktifkan periode semester/tahun ajaran. Fitur input nilai, rekap, dan kenaikan kelas membutuhkan periode aktif.

### 3. Kelola Data Master

Sebagai `super_admin`, lengkapi data berikut:

- User guru, wali kelas, dan admin.
- Kelas beserta tingkatannya.
- Mata pelajaran, kategori, tingkatan, dan bobot nilai.
- Data santri beserta kelas dan statusnya.

### 4. Atur Wali Kelas dan Mapel

Pada menu kelas, atur:

- Wali kelas untuk tiap kelas pada periode aktif.
- Mata pelajaran yang berlaku di kelas tersebut.
- Guru pengampu untuk setiap mapel.

### 5. Input Nilai

Guru membuka menu nilai, memilih kelas dan mapel yang menjadi tugasnya, lalu mengisi:

- Nilai harian
- Nilai ujian

Nilai akhir dihitung otomatis berdasarkan bobot pada mapel. Predikat otomatis mengikuti nilai akhir:

```text
A >= 85
B >= 75
C >= 60
D < 60
```

### 6. Rekap Raport

Wali kelas atau `super_admin` dapat membuka rekap kelas. Pada halaman ini pengguna dapat:

- Melihat seluruh nilai santri per mapel.
- Mengisi absensi sakit, izin, dan alpha.
- Mengisi sikap dan catatan wali kelas.
- Menghitung ulang ranking.
- Mencetak raport per santri.
- Mencetak seluruh raport dalam satu kelas.

### 7. Kenaikan Kelas

Menu kenaikan kelas tersedia untuk `super_admin` pada periode aktif. Gunakan fitur ini setelah data nilai dan rekap selesai diperiksa.


## Hak Akses Role

| Role | Akses Utama |
| --- | --- |
| `super_admin` | Mengelola seluruh data master, user, periode, kelas, mapel, santri, nilai, rekap, cetak raport, dan kenaikan kelas |
| `guru` | Melihat dashboard dan menginput nilai mapel yang ditugaskan |
| `wali_kelas` | Mengakses rekap kelas yang menjadi tanggung jawabnya, mengisi absensi/sikap/catatan, dan mencetak raport |

## Cetak PDF Raport

Aplikasi memakai helper `App\Support\ChromePdfRenderer` untuk menghasilkan PDF. Jika Chrome tidak terdeteksi otomatis, isi konfigurasi berikut pada `.env`:

```env
CHROME_PDF_BINARY="C:\Program Files\Google\Chrome\Application\chrome.exe"
```

Pastikan file logo tersedia di:

```text
public/logo.jpg
```

## Perintah Berguna

```bash
php artisan migrate
php artisan migrate:fresh --seed
php artisan test
npm run dev
npm run build
```

Project ini juga memiliki script Composer:

```bash
composer run dev
composer run test
```

## Catatan Pengembangan

- Aplikasi menggunakan periode aktif sebagai dasar input nilai, rekap, dan kenaikan kelas.
- Input nilai sudah dilengkapi sinkronisasi tampilan desktop dan mobile agar nilai tetap konsisten.
- Halaman utama sudah dibuat responsif dengan pola tabel untuk desktop dan card untuk mobile.
- Jangan menghapus periode aktif jika masih dipakai untuk nilai, rekap, wali kelas, atau riwayat kelas.
