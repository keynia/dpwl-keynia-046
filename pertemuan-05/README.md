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



# P5 - Implementasi INSERT Data Kendaraan

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

