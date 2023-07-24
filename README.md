# Aplikasi CRUD Sederhana - Laporan Jumlah Penduduk

### Deskripsi
Saya, Rizky Rahmadianto, telah menyelesaikan tugas atau tes teknis programmer di JMC Indonesia. Tugas ini melibatkan pembuatan aplikasi CRUD sederhana yang memiliki kemampuan untuk menyajikan laporan jumlah penduduk berdasarkan provinsi dan kabupaten.

### Teknologi yang Digunakan
- Framework PHP: Laravel 8
- Templating: Sneat Admin Template (free)
- Database: MySQL
- Laporan PDF: barryvdh/laravel-dompdf
- Datatables: Yajra

### Panduan Instalasi
1. Pastikan Anda sudah memiliki PHP, Composer, dan MySQL terinstal di komputer Anda.
2. Salin repository ini atau clone repository melalui Git.
3. Buat database baru untuk aplikasi ini di MySQL.
4. Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database dengan database yang telah Anda buat.
5. Jalankan perintah berikut di terminal untuk menginstal dependensi:
6. Jalankan perintah berikut untuk membuat tabel yang sudah disiapkan melalui migration: `php artisan migrate`
8. Setelah itu, gunakan perintah berikut untuk membersihkan cache dan optimasi: `composer dump-autoload` dan `php artisan optimize`
9. Terakhir, jalankan server lokal untuk mengakses aplikasi: `php artisan serve`

### Fungsi Aplikasi
Aplikasi ini memiliki fitur CRUD sederhana untuk mengelola data penduduk berdasarkan provinsi dan kabupaten. Berikut adalah ringkasan fungsi yang dapat Anda lakukan dengan aplikasi ini:

1. Menambahkan data provinsi dan kabupaten.
2. Mengedit data provinsi dan kabupaten yang sudah ada.
3. Menghapus data provinsi dan kabupaten.
4. Melihat daftar provinsi dan kabupaten.
5. Membuat laporan jumlah penduduk berdasarkan provinsi dan kabupaten dalam format PDF.

### Catatan
- Pastikan Anda telah melakukan `php artisan migrate` untuk membuat tabel yang dibutuhkan.
- Setelah mendapatkan project baru, lakukan `composer dump-autoload`, `php artisan optimize`, dan `php artisan serve` untuk membersihkan cache dan memastikan performa maksimal.

Semoga informasi di atas membantu Anda dalam menggunakan aplikasi CRUD sederhana ini. Jika Anda memiliki pertanyaan lebih lanjut atau membutuhkan bantuan, jangan ragu untuk bertanya. Terima kasih!
