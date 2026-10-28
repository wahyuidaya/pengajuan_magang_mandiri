# Sistem Informasi Pengajuan & Pengelolaan Magang Mandiri

Sistem informasi berbasis web untuk digitalisasi dan otomasi alur pendaftaran, verifikasi, pembimbingan, dan evaluasi program **Magang Mandiri Mahasiswa Program Studi**. Dibangun menggunakan **Laravel 12**, **PHP 8.2**, dan basis data relasional **MySQL** dengan arsitektur multi-role.

---

## 🎯 Peran Pengguna (Multi-Role)

Aplikasi ini mengakomodasi 4 jenis aktor dengan hak akses dan alur kerja terintegrasi:

1. **Mahasiswa**:
   - Pendaftaran akun dan pengisian profil akademik.
   - Pengajuan magang mandiri dengan mengunggah berkas KRS & dokumen pendukung.
   - Pengecekan status verifikasi secara real-time.
   - Pengisian silabus kegiatan dan pelaporan logbook mingguan/berkala.
2. **Staf Program Studi**:
   - Dashboard pengelolaan pengajuan magang mandiri.
   - Verifikasi dan validasi berkas persyaratan mahasiswa.
   - Penunjukan (ploting) Dosen Pembimbing untuk setiap mahasiswa yang disetujui.
   - Manajemen master data mitra industri/perusahaan dan arsip administrasi.
3. **Dosen Pembimbing**:
   - Monitoring daftar mahasiswa bimbingan aktif.
   - Review dan evaluasi logbook laporan kemajuan magang mahasiswa.
4. **Mitra Perusahaan (Industri)**:
   - Manajemen data mahasiswa yang magang di perusahaan terkait.
   - Pengesahan silabus kegiatan magang.
   - Pengisian form penilaian akhir kinerja mahasiswa magang.

---

## 🚀 Fitur Unggulan

- **Automated Document Parsing**: Membaca dan mengekstrak informasi dokumen akademik via `smalot/pdfparser`.
- **PDF Generation & Export**: Menghasilkan surat rekomendasi, berita acara, dan lembar penilaian resmi secara dinamis via `barryvdh/laravel-dompdf`.
- **Role-Based Access Control (RBAC)**: Pemisahan hak akses dashboard menggunakan middleware autentikasi Laravel.
- **Activity Logging & Master Data**: Audit trail aktivitas sistem untuk transparansi operasional program studi.
- **Responsive Interface**: Tata letak modern yang responsif berbasis Vite dan Tailwind CSS.

---

## 🛠️ Teknologi yang Digunakan

- **Backend Framework**: [Laravel 12](https://laravel.com/)
- **Programming Language**: PHP 8.2+
- **Database**: MySQL / MariaDB
- **Frontend & Asset Bundler**: Blade Templating, Tailwind CSS, Vite
- **Libraries**:
  - `barryvdh/laravel-dompdf`: Generator laporan dan surat PDF
  - `smalot/pdfparser`: Parser teks dokumen PDF
  - `doctrine/dbal`: Database schema management

---

## 💻 Panduan Instalasi Lokal

1. **Clone Repository**:
   ```bash
   git clone https://github.com/wahyuidaya/pengajuan_magang_mandiri.git
   cd pengajuan_magang_mandiri
   ```

2. **Instal Dependensi PHP & Node.js**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Sesuaikan pengaturan database di file `.env` (DB_DATABASE, DB_USERNAME, DB_PASSWORD).*

4. **Migrasi Database**:
   ```bash
   php artisan migrate --seed
   ```

5. **Storage Link**:
   ```bash
   php artisan storage:link
   ```

6. **Jalankan Aplikasi**:
   ```bash
   npm run build
   php artisan serve
   ```
   Akses aplikasi di browser melalui `http://127.0.0.1:8000`.

---

## 👨‍💻 Pengembang

Dikembangkan oleh **Wahyu Idaya** bersama tim pengembang dalam pelaksanaan program Magang Mandiri Program Studi Universitas Gunadarma.  
Portofolio: [wahyuidaya.github.io](https://wahyuidaya.github.io)
