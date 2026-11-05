### manajemen Data Penyewa
Fitur Manajemen Data Penyewa dibuat untuk memudahkan admin dalam mengelola data penyewa pada aplikasi rental kendaraan. Melalui fitur ini, admin dapat melihat, menambahkan, mengubah, dan menghapus data penyewa yang tersimpan di database.

Data penyewa yang dikelola meliputi informasi identitas penyewa sesuai kolom pada tabel penyewa, seperti nama, alamat, nomor telepon, email, password, serta foto KTP dan SIM A. Tampilan halaman dibuat mengikuti desain halaman Manajemen Kendaraan agar tampilan admin tetap konsisten.

Fitur ini menggunakan konsep MVC (Model, View, Controller), yaitu Model untuk mengelola data penyewa dan berkomunikasi dengan database, View untuk menampilkan halaman dan formulir, serta Controller untuk mengatur proses permintaan dari admin.

Selain itu, fitur ini dilengkapi dengan validasi input, penggunaan prepared statement untuk keamanan query database, penyimpanan password dalam bentuk hash, serta pengelolaan upload foto KTP dan SIM A. Akses halaman juga dibatasi agar hanya admin yang sudah login yang dapat mengelola data penyewa.
Tujuan Pembuatan
1. Mempermudah admin dalam mengelola data penyewa.
2. Memudahkan proses penambahan, pengubahan, dan penghapusan data penyewa.
3. Menjaga keamanan data penyewa melalui validasi dan pengamanan password.
4. Mengelola dokumen foto KTP dan SIM A sebagai data identitas penyewa.
5. Menerapkan konsep MVC dalam pengembangan fitur aplikasi rental kendaraan.

### Penyewa Tambahan – Manajemen Data Penyewa
Fitur penyewa tambahan dibuat untuk memudahkan admin dalam mengelola data penyewa pada aplikasi rental mobil. Fitur ini menampilkan daftar penyewa yang tersimpan di tabel t_penyewa pada database.

Admin dapat menambahkan data penyewa baru melalui form yang berisi username, password, NIK, nama lengkap, nomor HP, alamat lengkap, foto KTP, dan foto SIM A. Password yang dimasukkan akan dienkripsi menggunakan password_hash() sebelum disimpan ke database.

Selain menambahkan data, admin juga dapat melihat, mengubah, dan menghapus data penyewa. Pada proses perubahan data, admin dapat memperbarui informasi penyewa serta mengganti foto KTP dan SIM A jika diperlukan. Jika foto tidak diganti, sistem tetap menggunakan foto yang sebelumnya tersimpan.

Fitur ini dibuat menggunakan konsep MVC, yaitu:
- Model: Penyewa_model.php, digunakan untuk berinteraksi dengan database, seperti mengambil, menambahkan, mengubah, dan menghapus data penyewa.
- View: penyewa_view.php, penyewa_tambah.php, dan penyewa_ubah.php, digunakan untuk menampilkan daftar penyewa serta form tambah dan ubah data.
- Controller: Penyewa_controller.php, digunakan untuk mengatur proses pengelolaan data penyewa dan menghubungkan Model dengan View.
Dengan adanya fitur ini, admin dapat mengelola data penyewa dengan lebih mudah dan terstruktur melalui halaman dashboard admin.