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

### B.3. Menambahkan Route Transaksi

Pada tahap ini, saya menambahkan route transaksi pada file `routes.php` yang berada di dalam folder `application/config/`. Route ini digunakan untuk menghubungkan URL `transaksi/tambah` dengan method `tambah()` yang terdapat pada `Transaksi_controller`.

Route yang ditambahkan adalah:

`$route['transaksi/tambah'] = 'Transaksi_controller/tambah';`

Route tersebut berfungsi untuk mengarahkan permintaan dari formulir penambahan kendaraan ke Controller transaksi. Method tambah() akan menerima permintaan menggunakan metode POST untuk memproses data kendaraan yang akan dimasukkan ke keranjang.

Pada tahap ini, perubahan hanya dilakukan dengan menambahkan route transaksi tanpa menghapus route yang sudah tersedia. Formulir pada halaman katalog dan detail kendaraan belum diintegrasikan sehingga fitur tambah ke keranjang belum dapat diuji secara fungsional.

Dengan adanya route ini, aplikasi sudah memiliki jalur akses untuk menghubungkan formulir penambahan kendaraan dengan Controller transaksi.

### C.1. Mengubah Formulir pada application/views/katalog/detail.php

Pada tahap ini, saya mengubah formulir Add To Cart pada halaman detail kendaraan di file application/views/katalog/detail.php. Formulir yang sebelumnya menggunakan metode GET diganti menjadi metode POST dan diarahkan ke route transaksi/tambah.

Formulir tersebut mengirimkan nomor kendaraan melalui input no_plat dan menggunakan return_to dengan nilai detail agar sistem mengetahui bahwa penambahan kendaraan dilakukan dari halaman detail.

Tampilan tombol disesuaikan dengan kondisi kendaraan dan status login penyewa, yaitu:
1. Jika kendaraan tidak tersedia, sistem menampilkan pesan bahwa kendaraan tidak dapat ditambahkan ke keranjang.
2. Jika penyewa belum login, sistem menampilkan informasi untuk login atau registrasi, serta tombol Login Penyewa dan Registrasi.
3. Jika penyewa sudah login dan kendaraan tersedia, sistem menampilkan tombol Tambah ke Keranjang.

Setelah perubahan dilakukan, saya menguji halaman detail kendaraan melalui localhost untuk memastikan tampilan dan kondisi tombol sudah sesuai dengan ketentuan modul.

Pada tahap ini, pengujian difokuskan pada tampilan dan kondisi tombol. Proses pengiriman formulir dan penyimpanan transaksi akan diuji setelah Controller dan Model terintegrasi.

### C.2. Mengubah Tombol Tambah ke Keranjang pada Halaman Katalog

Pada tahap ini, saya melakukan perubahan pada file `application/views/katalog/index.php`, khususnya pada bagian tombol Tambah ke Keranjang di dalam perulangan `foreach ($daftarMobil as $mobil)`.

Perubahan dilakukan agar tombol menyesuaikan status kendaraan dan status login penyewa, dengan kondisi sebagai berikut:

1. Jika kendaraan tersedia dan penyewa sudah login, tombol Tambah ke Keranjang aktif dan dapat digunakan.
2. Jika kendaraan tersedia tetapi penyewa belum login, tombol Tambah ke Keranjang ditampilkan dalam keadaan nonaktif.
3. Jika kendaraan tidak tersedia, tombol diganti menjadi Tidak Dapat Disewa dan ditampilkan dalam keadaan nonaktif.

Formulir menggunakan metode POST dan diarahkan ke route `transaksi/tambah`. Data nomor kendaraan dikirim melalui input `no_plat`, sedangkan `return_to` menggunakan nilai `katalog` agar sistem mengetahui bahwa penambahan kendaraan dilakukan dari halaman katalog.

Pengujian dilakukan dengan membuka halaman katalog melalui localhost dan memeriksa kondisi tombol saat belum login, setelah login sebagai penyewa, serta ketika kendaraan berstatus tidak tersedia.

Pada tahap ini, pengujian hanya difokuskan pada tampilan dan kondisi tombol. Proses pengiriman formulir dan penyimpanan transaksi akan diuji setelah Controller dan Model terintegrasi.

### D.1. Mengubah application/views/katalog/navbar.php

Pada tahap ini, saya melakukan perubahan pada file `application/views/katalog/navbar.php` untuk menambahkan pesan notifikasi dan tautan menuju halaman keranjang pada navbar penyewa.

Perubahan yang dilakukan meliputi:

1. Menambahkan pengecekan status login penyewa untuk mengetahui apakah penyewa sudah login.
2. Menambahkan pengambilan pesan notifikasi dari session agar pesan dapat ditampilkan setelah penyewa melakukan suatu tindakan.
3. Menambahkan variabel `$jumlahKeranjang` untuk menyiapkan jumlah kendaraan yang ada di dalam keranjang.
4. Menambahkan notifikasi transaksi yang dapat ditampilkan dan ditutup oleh pengguna.
5. Mengubah tautan ikon keranjang agar mengarah ke halaman `transaksi/keranjang`.
6. Mengubah angka pada ikon keranjang agar menampilkan nilai dari variabel `$jumlahKeranjang`, bukan angka tetap.

Setelah perubahan selesai, saya melakukan pengujian melalui localhost dengan membuka halaman katalog, memeriksa tampilan navbar, memastikan pesan notifikasi dapat ditampilkan dan ditutup, serta memastikan ikon keranjang mengarah ke halaman keranjang.

Pada tahap ini, pengujian jumlah kendaraan dalam keranjang belum dilakukan karena masih membutuhkan integrasi Model dan Controller pada tahap D.2.

Dengan perubahan ini, navbar sudah memiliki fitur notifikasi dan tautan menuju halaman keranjang yang dapat digunakan oleh penyewa.

### D.2.2. Memperbarui application/controllers/Katalog_controller.php

Pada tahap ini, saya melakukan perubahan pada file `application/controllers/Katalog_controller.php` untuk menghubungkan Model transaksi dengan Controller katalog. Perubahan ini bertujuan agar jumlah kendaraan yang ada di dalam keranjang dapat dikirim ke halaman katalog dan halaman detail kendaraan, kemudian ditampilkan pada navbar.

Perubahan yang dilakukan meliputi:

1. Menambahkan pemanggilan file `Transaksi_model.php` agar Controller katalog dapat menggunakan Model transaksi.
2. Menambahkan properti `$transaksiModel` di dalam class `Katalog_controller`.
3. Menambahkan inisialisasi objek `Transaksi_model` pada method `__construct()`.
4. Memperbarui method `index()` agar mengambil username penyewa dari session dan mengirimkan data `jumlahKeranjang` ke halaman katalog.
5. Memperbarui method `detail()` agar jumlah kendaraan dalam keranjang juga dikirim ke halaman detail kendaraan.

Jumlah keranjang dihitung berdasarkan akun penyewa yang sedang login. Jika penyewa belum login, jumlah keranjang akan bernilai 0. Dengan demikian, jumlah keranjang yang ditampilkan tidak mengambil data dari akun penyewa lain.

Setelah perubahan selesai, pengujian dilakukan melalui browser dengan membuka halaman katalog dan detail kendaraan. Pengujian juga dilakukan sebelum dan sesudah login sebagai penyewa untuk memastikan navbar menampilkan jumlah keranjang sesuai akun yang digunakan.

Dengan perubahan ini, halaman katalog dan detail kendaraan sudah dapat menerima data jumlah keranjang dari Model transaksi melalui Controller katalog sehingga jumlah tersebut dapat ditampilkan pada navbar.

### D.2.3. Memperbarui application/controllers/Penyewa_controller.php

Pada tahap ini, saya melakukan perubahan pada file `application/controllers/Penyewa_controller.php` agar jumlah kendaraan yang ada di dalam keranjang dapat ditampilkan pada navbar ketika penyewa membuka halaman profil.

Perubahan yang dilakukan meliputi:

1. Menambahkan pemanggilan file `Transaksi_model.php` agar Controller penyewa dapat menggunakan Model transaksi.
2. Menambahkan properti `$transaksiModel` di dalam class `Penyewa_controller`.
3. Menambahkan inisialisasi objek `Transaksi_model` pada method `__construct()`.
4. Memperbarui method `profil()` agar menghitung jumlah kendaraan dalam keranjang dan mengirimkan data `jumlahKeranjang` ke View profil.
5. Menambahkan data `jumlahKeranjang` pada tampilan profil ketika validasi formulir gagal, sehingga jumlah keranjang tetap ditampilkan meskipun terdapat pesan kesalahan.
6. Memperbarui method `renderProfilError()` agar tetap mengirimkan jumlah keranjang ketika halaman profil menampilkan pesan error.

Jumlah kendaraan dalam keranjang dihitung berdasarkan akun penyewa yang sedang login. Dengan demikian, jumlah keranjang yang ditampilkan pada halaman profil sesuai dengan keranjang milik akun tersebut.

Setelah perubahan selesai, pengujian dilakukan melalui browser dengan login sebagai penyewa, kemudian membuka halaman profil. Pengujian juga dilakukan dengan mengubah data profil menggunakan data valid dan tidak valid untuk memastikan jumlah keranjang tetap tampil meskipun terjadi kesalahan validasi.

Dengan perubahan ini, navbar pada halaman profil penyewa sudah dapat menampilkan jumlah kendaraan dalam keranjang, sehingga jumlah keranjang dapat ditampilkan secara konsisten pada halaman katalog, detail kendaraan, dan profil penyewa.

### E.1. Menambahkan Method tambahKeKeranjang()

Pada tahap ini, saya menambahkan method `tambahKeKeranjang()` pada file `application/models/Transaksi_model.php`. Method ini berfungsi untuk memproses penambahan kendaraan ke dalam keranjang penyewa dan menyimpan data transaksi ke dalam database.

Proses penambahan kendaraan dilakukan menggunakan pola Header-Detail, yaitu:
1. Tabel `t_sewa` digunakan untuk menyimpan data utama transaksi atau keranjang penyewaan.
2. Tabel `t_pilih` digunakan untuk menyimpan detail kendaraan yang dipilih oleh penyewa.

Selain itu, saya menambahkan beberapa method pendukung, yaitu:
1. `namaKunciKeranjang()` untuk membuat identitas kunci keranjang berdasarkan akun penyewa.
2. `kunciKendaraan()` untuk mengunci dan memeriksa data kendaraan sebelum dimasukkan ke keranjang.
3. `hitungHariSewa()` untuk menghitung lama waktu penyewaan berdasarkan tanggal mulai dan tanggal selesai.
4. `normalisasiWaktu()` untuk memeriksa dan menyesuaikan format waktu pengambilan kendaraan.
5. `tambahKeKeranjang()` untuk mencari keranjang aktif, membuat keranjang baru jika belum tersedia, dan menambahkan kendaraan yang dipilih.

Method ini juga melakukan pemeriksaan agar kendaraan yang dimasukkan memiliki status tersedia dan tidak dimasukkan dua kali ke dalam keranjang yang sama. Proses penyimpanan menggunakan transaksi database agar data dapat dibatalkan kembali apabila terjadi kesalahan.

Setelah perubahan selesai, pengujian dilakukan melalui localhost dengan langkah-langkah berikut:
1. Login sebagai penyewa.
2. Membuka halaman katalog dan memilih kendaraan yang berstatus tersedia.
3. Menekan tombol Tambah ke Keranjang dan memastikan muncul pesan hasil proses.
4. Memeriksa tabel `t_sewa` dan `t_pilih` melalui phpMyAdmin untuk memastikan data transaksi tersimpan dengan kode sewa yang sama.
5. Menambahkan kendaraan lain dan memastikan kendaraan tersebut masuk ke keranjang aktif yang sama.
6. Mencoba menambahkan kendaraan yang sama kembali untuk memastikan sistem menolak data duplikat.

Dengan tahap ini, fitur penambahan kendaraan ke keranjang sudah terintegrasi dengan Model transaksi dan database, sehingga kendaraan yang dipilih penyewa dapat disimpan sebagai bagian dari transaksi penyewaan.

### F.1. Membuat Halaman Isi Keranjang

Pada tahap ini, saya membuat file `application/views/transaksi/keranjang.php` untuk menampilkan halaman keranjang penyewaan kendaraan. Halaman ini digunakan oleh penyewa untuk memeriksa kendaraan yang sudah dipilih sebelum mengajukan transaksi kepada admin.

Halaman keranjang menampilkan beberapa informasi, yaitu:

1. Kode transaksi dan status penyewaan.
2. Tanggal mulai dan tanggal selesai penyewaan.
3. Waktu pengambilan kendaraan.
4. Daftar kendaraan yang sudah dimasukkan ke keranjang.
5. Nomor polisi dan harga sewa setiap kendaraan.
6. Durasi penyewaan dan subtotal setiap kendaraan.
7. Total biaya penyewaan.

Selain menampilkan informasi transaksi, halaman ini juga menyediakan beberapa fitur, yaitu:

1. Tombol Update Keranjang untuk memperbarui tanggal dan waktu penyewaan.
2. Tombol Hapus untuk mengeluarkan kendaraan dari keranjang.
3. Tombol Lanjut Pilih Kendaraan untuk kembali ke halaman katalog.
4. Tombol Checkout / Lihat Konfirmasi untuk melanjutkan proses pengajuan transaksi.

Halaman ini juga dilengkapi dengan pesan notifikasi apabila terdapat proses yang berhasil atau mengalami kesalahan. Jika keranjang masih kosong, sistem akan menampilkan pesan bahwa belum ada kendaraan yang dipilih.

Untuk pengaturan periode sewa, sistem memberikan batas waktu pengambilan minimal dua jam dari waktu sekarang dan tanggal selesai harus setelah tanggal mulai. Data yang ditampilkan juga diamankan menggunakan `htmlspecialchars()` agar lebih aman ketika ditampilkan pada halaman.

Dengan dibuatnya halaman ini, penyewa dapat melihat isi keranjang, memeriksa biaya sewa, memperbarui periode penyewaan, menghapus kendaraan, dan melanjutkan ke proses checkout.