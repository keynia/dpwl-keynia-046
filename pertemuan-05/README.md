1. Perubahan yang Dilakukan

Pada tahap A, saya telah menambahkan menu Manajemen Kendaraan pada sidebar AdminLTE dan mengatur route "admin/mobil" agar menu tersebut dapat diarahkan ke halaman manajemen kendaraan.

2. File yang Diubah

File yang mengalami perubahan pada tahap A adalah:

- "application/config/routes.php"
- "application/views/templates/sidebar.php"
- "pertemuan-05/README.md"

3. Hasil Pengujian

Pengujian dilakukan dengan menjalankan aplikasi dan masuk menggunakan akun admin. Setelah itu, saya memeriksa apakah menu Manajemen Kendaraan tampil pada sidebar dan mengklik menu tersebut.

- Menu Manajemen Kendaraan tampil pada sidebar.
- URL berubah menjadi "admin/mobil".
- Halaman kendaraan belum dapat ditampilkan karena file controller mobil belum dibuat.
- Pesan “Controller tidak ditemukan” muncul sesuai dengan kondisi yang diharapkan pada tahap A.

B.3. Memperbarui README.md dan Mencatat Perubahan Git

1. Perubahan yang Dilakukan

Pada tahap B, saya menambahkan tiga route baru untuk operasi Manajemen Kendaraan, yaitu route tambah, ubah, dan hapus kendaraan. Route tersebut digunakan untuk menghubungkan URL dengan method yang sesuai pada "Mobil_controller".

2. Berkas yang Terdampak

- "application/config/routes.php"
- "pertemuan-05/README.md"

3. Hasil Pengujian

Pengujian dilakukan dengan memeriksa konfigurasi route dan mengakses URL yang telah disiapkan. Menu Manajemen Kendaraan mengarah ke URL yang sesuai. Namun, halaman kendaraan belum dapat ditampilkan karena file "Mobil_controller.php" belum dibuat. Pesan "Controller tidak ditemukan" masih muncul dan merupakan kondisi yang diharapkan pada tahap ini.