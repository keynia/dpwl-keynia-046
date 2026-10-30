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

## E.4. Pengujian Login Berhasil, Login Gagal, Sesi, Logout, dan Pembatasan Akses

Pada tahap ini dilakukan pengujian fitur login dan logout penyewa untuk memastikan proses autentikasi berjalan dengan baik.

Pengujian dilakukan dengan membuka alamat:
http://localhost/dpwl-2522500046/index.php/auth/login-penyewa

### Hasil Pengujian

- **Login berhasil:** Berhasil. Penyewa dapat masuk menggunakan username dan password yang telah didaftarkan.
- **Login gagal:** Sistem menolak proses login apabila username atau password yang dimasukkan salah.
- **Sesi login:** Berhasil. Sistem dapat mempertahankan status login penyewa selama sesi berlangsung.
- **Logout:** Berhasil. Penyewa dapat keluar dari akun dan mengakhiri sesi login.
- **Pembatasan akses:** Sistem membatasi akses halaman tertentu agar hanya dapat digunakan oleh penyewa yang sudah login.

### Kesimpulan

Fitur login, sesi, dan logout penyewa telah berhasil diuji. Sistem dapat memproses login dengan benar, menolak data login yang salah, mengelola sesi pengguna, serta membatasi akses berdasarkan status login penyewa.

## F.5. Pengujian Pembaruan Profil dan Penanganan Input Tidak Valid

Pada tahap ini dilakukan pengujian terhadap fitur profil penyewa untuk memastikan data profil dapat ditampilkan dan diperbarui dengan benar.

Pengujian dilakukan dengan login menggunakan akun penyewa, kemudian membuka halaman profil melalui alamat:

http://localhost/dpwl-2522500046/index.php/penyewa/profil

### Hasil Pengujian

- **Menampilkan profil:** Berhasil. Sistem dapat menampilkan informasi profil penyewa sesuai akun yang sedang login.
- **Pembaruan profil:** Berhasil. Penyewa dapat mengubah data profil dan menyimpan perubahan.
- **Input tidak valid:** Sistem melakukan validasi apabila data yang dimasukkan tidak sesuai dengan ketentuan.
- **Kolom wajib:** Sistem menolak penyimpanan apabila terdapat data wajib yang belum diisi.
- **Unggah foto SIM:** Sistem menerima foto dengan format JPEG, PNG, atau WEBP dan ukuran maksimal 5 MB.
- **Akses tanpa login:** Sistem mengarahkan pengguna ke halaman login apabila mencoba membuka halaman profil tanpa melakukan login.

### Kesimpulan

Fitur profil penyewa berhasil diuji. Sistem dapat menampilkan dan memperbarui data profil, melakukan validasi terhadap input yang tidak valid, serta membatasi akses halaman profil hanya untuk penyewa yang sudah login.

## G.5. Pengujian Penghilangan index.php pada URL Aplikasi

Pada tahap ini dilakukan pengujian untuk memastikan aplikasi dapat diakses tanpa menuliskan `index.php` pada URL. Pengujian juga dilakukan untuk memastikan navigasi dan fitur aplikasi tetap berjalan setelah perubahan route `default_controller`.

Pengujian dilakukan dengan membuka alamat:

http://localhost/dpwl-2522500046

### Hasil Pengujian

- **Akses halaman utama:** Berhasil. Aplikasi dapat dibuka melalui URL tanpa menuliskan `index.php`.
- **Navigasi menu:** Berhasil. Menu-menu yang tersedia dapat dibuka dan URL tidak lagi menampilkan `index.php`.
- **Halaman katalog:** Berhasil. Halaman katalog kendaraan dapat diakses melalui halaman utama aplikasi.
- **Fitur aplikasi:** Fitur yang telah dibuat sebelumnya tetap dapat dijalankan setelah perubahan konfigurasi URL.
- **Route default_controller:** Berhasil. Halaman katalog menjadi halaman utama aplikasi setelah `default_controller` diarahkan ke `Katalog_controller`.

### Kesimpulan

Penghilangan `index.php` pada URL aplikasi berhasil diterapkan. Aplikasi dapat diakses menggunakan URL yang lebih sederhana, navigasi tetap berjalan, dan fitur-fitur sebelumnya tetap dapat digunakan tanpa galat.