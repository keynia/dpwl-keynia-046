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



# D - Implementasi INSERT Data Kendaraan

## 1. Deskripsi
Pada tahap P5 dilakukan pengembangan fitur INSERT untuk menambahkan data kendaraan baru ke aplikasi DPWL menggunakan konsep MVC.

## 2. Perubahan File

### a. Mobil_model.php
Menambahkan method `create()` untuk menyimpan data kendaraan ke tabel `t_mobil` menggunakan prepared statement.

### b. Mobil_controller.php
Menambahkan method `tambah()` untuk menampilkan formulir, memvalidasi input, memproses upload foto, dan menyimpan data kendaraan.

### c. mobil_tambah.php
Membuat formulir untuk memasukkan data kendaraan, seperti nomor plat, nama mobil, jumlah kursi, jenis mobil, transmisi, harga, status, dan foto kendaraan.

### d. mobil_view.php
Menampilkan daftar kendaraan yang tersimpan di database beserta informasi dan foto depan kendaraan.

## 3. Fitur yang Dikembangkan
- Menambahkan data kendaraan.
- Validasi input kendaraan.
- Upload foto depan, belakang, dan interior.
- Menyimpan data ke database.
- Menampilkan data kendaraan pada halaman Manajemen Kendaraan.

Foto disimpan pada folder `assets/uploads/mobil/`.

## 4. Hasil Pengujian
Seluruh pengujian berhasil dilakukan, meliputi:
1. Formulir tambah kendaraan berhasil ditampilkan.
2. Data kendaraan berhasil disimpan ke database.
3. Foto kendaraan berhasil diunggah.
4. Data berhasil ditampilkan pada halaman Manajemen Kendaraan.
5. Validasi input dan foto berjalan dengan baik.

## 5. Kesimpulan
Fitur INSERT data kendaraan berhasil diterapkan menggunakan konsep MVC. Admin dapat menambahkan data kendaraan beserta foto, menyimpannya ke database, dan melihat hasilnya pada halaman Manajemen Kendaraan.

## 6. Git dan Version Control
Perubahan proyek disimpan menggunakan Git dengan perintah:

```bash
git status
git add .
git commit -m "P5 implementasi INSERT data kendaraan"
git push
git log -1 --oneline
```

Commit digunakan untuk mencatat perubahan P5 dan push untuk mengirim perubahan ke repositori.


# E - Implementasi CRUD Data Kendaraan

## 1. Deskripsi
Pada pertemuan P5 dilakukan pengembangan aplikasi penyewaan kendaraan menggunakan PHP 8.4 dengan konsep MVC (Model, View, Controller).

Pengembangan meliputi fitur INSERT, READ, dan UPDATE untuk mengelola data kendaraan pada tabel `t_mobil`.

## 2. Implementasi UPDATE Data Kendaraan

Fitur UPDATE digunakan untuk mengubah informasi kendaraan yang sudah tersimpan di database.

Admin dapat mengubah:
- Nama mobil.
- Jumlah kursi dan bagasi.
- Jenis mobil dan transmisi.
- Tahun pembuatan.
- Harga sewa per hari.
- Catatan dan status kendaraan.
- Foto depan, belakang, dan interior.

Nomor plat digunakan sebagai identitas kendaraan dan tidak dapat diubah melalui formulir.

Jika foto tidak diganti, aplikasi tetap menggunakan foto lama.

## 3. Perubahan Berkas

### a. Mobil_model.php
Menambahkan method `findByNoPlat()` untuk mengambil data kendaraan berdasarkan nomor plat dan method `update()` untuk memperbarui data kendaraan menggunakan prepared statement.

### b. Mobil_controller.php
Menambahkan method `ubah()` untuk mengambil data lama, memvalidasi input, memproses upload foto, dan menyimpan perubahan ke database.

### c. mobil_ubah.php
Membuat halaman formulir untuk mengubah data kendaraan dengan tampilan AdminLTE. Data kendaraan lama ditampilkan secara otomatis pada formulir.

### d. routes.php
Menambahkan route untuk mengakses halaman ubah kendaraan melalui URL `admin/mobil/ubah/{noPlat}`.

## 4. Hasil Pengujian

Pengujian yang telah dilakukan:
1. Halaman ubah kendaraan berhasil ditampilkan.
2. Data kendaraan lama berhasil ditampilkan pada formulir.
3. Formulir dapat digunakan untuk mengubah data kendaraan.
4. Tampilan halaman ubah berhasil disesuaikan dengan AdminLTE.

Fitur INSERT dan READ yang telah dibuat sebelumnya tetap dipertahankan.

## 5. Kesimpulan

Fitur UPDATE data kendaraan berhasil diimplementasikan menggunakan konsep MVC. Admin dapat mengubah informasi kendaraan melalui formulir dan menyimpan perubahan ke database.

## 6. Git dan Version Control

Perubahan proyek dicatat menggunakan Git dengan perintah:

```bash
git status
git add .
git commit -m "P5 implementasi UPDATE data kendaraan"
git push
git log -1 --oneline
```

Commit digunakan untuk mencatat perubahan fitur UPDATE, sedangkan push digunakan untuk mengirim perubahan ke repositori GitHub.


# F4 - Implementasi DELETE Data Kendaraan

## 1. Deskripsi
Pada tahap ini dilakukan pengembangan fitur DELETE untuk menghapus data kendaraan dari aplikasi DPWL menggunakan konsep MVC (Model, View, Controller).

Fitur ini digunakan oleh admin untuk menghapus data kendaraan berdasarkan nomor plat dari tabel `t_mobil`.

## 2. Implementasi DELETE Data Kendaraan

Fitur DELETE memungkinkan admin menghapus kendaraan melalui halaman Manajemen Kendaraan.

Proses penghapusan meliputi:
- Mengambil data kendaraan berdasarkan nomor plat.
- Memeriksa apakah data kendaraan tersedia.
- Menghapus data kendaraan dari database.
- Menghapus foto depan, belakang, dan interior yang terkait.
- Menampilkan pesan berhasil atau gagal.
- Mengarahkan kembali ke halaman Manajemen Kendaraan.

Proses penghapusan menggunakan metode POST agar penghapusan tidak dilakukan hanya dengan membuka URL.

## 3. Perubahan Berkas

### a. Mobil_model.php
Menambahkan method `delete()` untuk menghapus data kendaraan dari tabel `t_mobil` berdasarkan nomor plat menggunakan prepared statement.

### b. Mobil_controller.php
Menambahkan method `hapus()` untuk memproses permintaan penghapusan, memeriksa data kendaraan, memanggil method delete() pada Model, dan menghapus foto kendaraan setelah data berhasil dihapus.

### c. mobil_view.php
Menambahkan atau menyesuaikan tombol Hapus pada daftar kendaraan agar admin dapat melakukan penghapusan data kendaraan.

### d. routes.php
Menambahkan route untuk memproses penghapusan kendaraan berdasarkan nomor plat.

## 4. Hasil Pengujian

Pengujian fitur DELETE meliputi:
1. Menghapus kendaraan melalui tombol Hapus.
2. Memastikan data kendaraan terhapus dari halaman Manajemen Kendaraan.
3. Memastikan data kendaraan terhapus dari tabel `t_mobil`.
4. Memeriksa penghapusan foto kendaraan pada folder `assets/uploads/mobil/`.
5. Memastikan aplikasi memberikan pesan jika data tidak ditemukan atau penghapusan gagal.
6. Memastikan fitur INSERT, READ, dan UPDATE tetap berfungsi.

Catatan: Hasil setiap pengujian dicatat sesuai kondisi aktual aplikasi.

## 5. Kesimpulan

Fitur DELETE dikembangkan untuk melengkapi pengelolaan data kendaraan pada aplikasi DPWL. Dengan fitur ini, admin dapat menghapus data kendaraan beserta foto yang terkait melalui penerapan konsep MVC.

## 6. Git dan Version Control

Perubahan proyek dicatat menggunakan Git dengan perintah:

```bash
git status
git add .
git commit -m "P5 implementasi DELETE data kendaraan"
git push
git log -1 --oneline
```

Commit digunakan untuk mencatat perubahan fitur DELETE, sedangkan push digunakan untuk mengirim perubahan ke repositori GitHub.
