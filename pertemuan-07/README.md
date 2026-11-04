### A.2. Menyimpan Hasil Praktikum dan Memperbarui Git

Pada tahap ini, saya melakukan penyimpanan hasil praktikum P6 sebagai dasar untuk melanjutkan pengembangan aplikasi DPWL pada P7. Sebelum melanjutkan ke tahap berikutnya, saya memastikan aplikasi dapat dijalankan dan fitur-fitur yang telah dibuat pada P6 dapat digunakan dengan baik.

Setelah melakukan pemeriksaan, seluruh isi folder workspace `dpwl-nim` disalin ke folder `dpwl-nim-nama/pertemuan-07` sebagai arsip hasil praktikum P7. Selanjutnya, perubahan disimpan ke repositori GitHub menggunakan perintah Git.

Dengan tahap ini, hasil praktikum P6 sudah disiapkan sebagai dasar pengembangan fitur transaksi pada P7.

### B.1. Membuat Berkas Transaksi_model.php

Pada tahap ini, saya membuat file `Transaksi_model.php` di dalam folder `application/models/`. File ini digunakan sebagai Model untuk mengelola data transaksi penyewaan kendaraan pada aplikasi DPWL.

Di dalam file tersebut dibuat class `Transaksi_model` dan properti `$db` untuk menyimpan koneksi ke database MySQLi. Koneksi database diambil menggunakan variabel global `$db` melalui method constructor.

Selanjutnya, saya menambahkan method `ambilKunci()` dan `lepasKunci()` yang digunakan untuk mengatur akses proses transaksi agar tidak terjadi penggunaan kode transaksi secara bersamaan. Dengan adanya pengaturan kunci ini, proses pembuatan transaksi dapat berjalan lebih aman.

Hasil dari tahap ini adalah tersedianya Model transaksi sebagai dasar untuk mengembangkan fitur keranjang dan checkout pada tahap berikutnya.

### B.2. Membuat Berkas Transaksi_controller.php

Pada tahap ini, saya membuat file `Transaksi_controller.php` di dalam folder `application/controllers/`. File ini berfungsi sebagai Controller yang menangani proses transaksi penyewaan kendaraan pada aplikasi DPWL.

Di dalam Controller dibuat properti `$transaksiModel` untuk menghubungkan Controller dengan `Transaksi_model.php`. Method `__construct()` digunakan untuk membuat objek Model transaksi.

Selanjutnya, dibuat method `tambah()` untuk menangani permintaan penambahan kendaraan ke keranjang. Method ini memeriksa apakah penyewa sudah login dan memastikan permintaan menggunakan metode POST. Data nomor plat kendaraan dan asal halaman diambil dari formulir.

Jika kendaraan berhasil diproses oleh Model, sistem menampilkan pesan berhasil. Jika terjadi kesalahan, sistem akan menampilkan pesan gagal sesuai kondisi yang ditemukan.

Selain itu, terdapat beberapa method pendukung, yaitu:

1. `wajibLoginPenyewa()` untuk memastikan pengguna sudah login sebagai penyewa.
2. `wajibMetode()` untuk memastikan permintaan menggunakan metode yang sesuai.
3. `redirectTambahDenganPesan()` untuk mengarahkan kembali pengguna ke halaman katalog atau detail kendaraan dengan membawa notifikasi.
4. `pesanBisnisAman()` untuk mengatur pesan kesalahan agar informasi yang ditampilkan kepada pengguna tetap aman.

Dengan adanya Controller ini, proses permintaan penambahan kendaraan ke keranjang dapat ditangani dan dihubungkan dengan Model transaksi.