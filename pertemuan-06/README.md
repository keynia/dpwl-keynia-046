## 1. Tahap Pengembangan yang Dikerjakan

Pada praktikum P6, saya melakukan pengembangan aplikasi rental mobil menggunakan konsep MVC (Model, View, Controller). Tahapan pengembangan meliputi pembuatan tampilan antarmuka, katalog kendaraan, halaman detail kendaraan, pendaftaran penyewa, login dan logout, pengelolaan profil penyewa, serta pengaturan URL agar tidak menampilkan index.php.

## 2. Berkas atau Komponen yang Ditambahkan, Diubah, atau Dihapus

Berkas atau komponen yang dikerjakan meliputi:
- Model, untuk mengelola data dan berhubungan dengan database.
- Controller, untuk mengatur proses aplikasi dan menghubungkan Model dengan View.
- View, untuk menampilkan halaman aplikasi kepada pengguna.
- Template tampilan, untuk membuat tampilan aplikasi lebih terstruktur.
- Komponen pendukung lainnya, seperti session, routing, dan konfigurasi URL.

## 3. Perubahan dan Fitur yang Diimplementasikan

Pengembangan aplikasi rental mobil pada praktikum P6 mencakup beberapa fitur berikut:
- Tampilan antarmuka aplikasi menggunakan template.
- Katalog kendaraan untuk menampilkan daftar mobil.
- Halaman detail untuk melihat informasi kendaraan.
- Formulir pendaftaran penyewa.
- Fitur login dan logout menggunakan session.
- Halaman profil penyewa dan fitur mengubah profil.
- Pengaturan URL agar dapat diakses tanpa menuliskan index.php.

## 4. Langkah Pengujian yang Dilakukan

Pengujian dilakukan dengan menjalankan aplikasi melalui browser. Langkah-langkah pengujian meliputi:
1. Menjalankan server lokal dan database.
2. Membuka aplikasi melalui browser.
3. Memeriksa tampilan halaman aplikasi.
4. Mencoba fitur katalog dan detail kendaraan.
5. Mencoba proses pendaftaran dan login penyewa.
6. Menguji fitur logout dan pengelolaan profil.
7. Memeriksa akses URL tanpa index.php.


# README – A5

## Pemeriksaan Aset TemplateMo

Pada tahap ini dilakukan pemeriksaan aset TemplateMo yang digunakan untuk pengembangan tampilan aplikasi rental kendaraan.

Pemeriksaan meliputi:

1. Folder `assets/zay/css/` untuk memastikan file CSS tersedia.
2. Folder `assets/zay/img/` untuk memastikan gambar tersedia.
3. Folder `assets/zay/js/` untuk memastikan file JavaScript tersedia.
4. Folder `assets/zay/webfonts/` untuk memastikan file font tersedia.
5. Memeriksa kesesuaian jalur aset antara file HTML TemplateMo dengan lokasi aset pada proyek aplikasi.

## Hasil Pemeriksaan

- **File CSS:** Tersedia di dalam folder `assets/zay/css/`.
- **File gambar:** Tersedia di dalam folder `assets/zay/img/`.
- **File JavaScript:** Tersedia di dalam folder `assets/zay/js/`.
- **File webfonts:** Tersedia di dalam folder `assets/zay/webfonts/`.
- **Jalur aset:** Perlu disesuaikan karena lokasi aset pada aplikasi berada di dalam folder `assets/zay/`, sedangkan file HTML TemplateMo menggunakan jalur relatif bawaan.
- **File yang tidak ditemukan:** Tidak ditemukan berdasarkan pemeriksaan folder aset yang tersedia.

## Kesimpulan

Aset TemplateMo telah disalin ke dalam folder `assets/zay/` pada workspace aplikasi. Struktur folder CSS, gambar, JavaScript, dan webfonts telah disiapkan. Jalur aset perlu disesuaikan ketika proses integrasi ke dalam View aplikasi MVC agar seluruh aset dapat dipanggil dengan benar.

## B.4. Pengujian Integrasi Model, Controller, View, dan Navigasi Katalog

Pada tahap ini dilakukan pengujian terhadap fitur katalog kendaraan menggunakan konsep MVC (Model, View, Controller).

Pengujian dilakukan dengan membuka alamat:
http://localhost/dpwl-2522500046/index.php/katalog

### Hasil Pengujian

Berdasarkan pengujian yang telah dilakukan, diperoleh hasil sebagai berikut:

- **Model:** Berhasil mengambil data kendaraan dari database.
- **Controller:** Berhasil memanggil Model dan mengirimkan data ke View.
- **View katalog:** Berhasil menampilkan data kendaraan.
- **Navigasi katalog:** Berhasil membuka halaman katalog melalui URL yang ditentukan.
- **Foto kendaraan:** Belum tersedia karena foto kendaraan belum diunggah melalui fitur Manajemen Kendaraan.

### Kesimpulan

Integrasi Model, Controller, View, dan navigasi katalog kendaraan telah berhasil dilakukan. Data kendaraan berhasil ditampilkan pada halaman katalog. Namun, foto kendaraan belum tersedia sehingga perlu diunggah melalui fitur Manajemen Kendaraan.

## C.4. Pengujian Navigasi Katalog ke Detail dan Penanganan Galat

Pada tahap ini dilakukan pengujian untuk memastikan halaman katalog dapat terhubung ke halaman detail kendaraan.

Pengujian dilakukan dengan membuka alamat:
http://localhost/dpwl-2522500046/index.php/katalog

Kemudian memilih salah satu foto atau nama kendaraan untuk membuka halaman detail kendaraan.

### Hasil Pengujian

- **Navigasi katalog ke detail:** Berhasil. Ketika foto atau nama kendaraan diklik, halaman detail kendaraan dapat dibuka.
- **URL detail kendaraan:** Berhasil menampilkan alamat detail berdasarkan nomor plat kendaraan.
- **Informasi kendaraan:** Berhasil menampilkan informasi kendaraan sesuai data yang dipilih.
- **Penanganan galat:** Dilakukan dengan memeriksa kondisi kendaraan yang tidak ditemukan. Sistem menampilkan pesan "Kendaraan tidak ditemukan."

### Kesimpulan

Pengujian navigasi katalog ke detail kendaraan berhasil dilakukan. Halaman detail dapat menampilkan informasi kendaraan berdasarkan nomor plat yang dipilih. Penanganan galat juga disiapkan untuk kondisi ketika data kendaraan tidak ditemukan.

## D.5. Pengujian Registrasi Berhasil, Input Tidak Valid, dan Akun Duplikat

Pada tahap ini dilakukan pengujian fitur registrasi akun penyewa untuk memastikan pengguna dapat membuat akun baru dengan mengisi formulir registrasi.

Pengujian dilakukan dengan membuka alamat:
http://localhost/dpwl-2522500046/index.php/auth/register

### Hasil Pengujian

- **Registrasi berhasil:** Berhasil. Pengguna dapat mengisi formulir dan membuat akun penyewa baru.
- **Input tidak valid:** Sistem melakukan validasi apabila data yang dimasukkan tidak sesuai dengan ketentuan atau terdapat kolom wajib yang belum diisi.
- **Akun duplikat:** Sistem memeriksa username yang sudah terdaftar agar tidak dapat digunakan kembali oleh akun lain.
- **Penyimpanan data:** Data akun penyewa berhasil disimpan ke dalam database.

### Kesimpulan

Fitur registrasi akun penyewa telah berhasil dibuat dan diuji. Sistem dapat menerima pendaftaran akun baru, melakukan validasi input, serta mencegah penggunaan username yang sudah terdaftar.