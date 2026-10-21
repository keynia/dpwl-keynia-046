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