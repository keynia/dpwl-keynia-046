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