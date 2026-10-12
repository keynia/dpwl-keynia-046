### Penerapan Post-Redirect-Get (PRG)
Pada tahap ini dilakukan penelusuran dan pengujian pola
Post-Redirect-Get (PRG) pada proses autentikasi.
Penerapan PRG:
- Login berhasil menggunakan POST untuk proses autentikasi,
kemudian redirect ke /admin dan dilanjutkan dengan GET.
- Logout menggunakan POST untuk mengakhiri session,
kemudian redirect ke /auth/login dan dilanjutkan dengan GET.
Login gagal tidak menggunakan redirect karena Controller
mengembalikan View login beserta pesan kesalahan pada
request tersebut.
Pengujian aktual dilakukan terhadap:
| No. | Skenario Pengujian | Hasil Aktual | Status |
| --: | ----------------------------------- | ---------------- | ----------------------- |
| 1 | Login dengan kredensial valid | `[Pengguna berhasil login dan diarahkan ke halaman /admin. Halaman administrasi menampilkan informasi bahwa pengguna telah login sebagai Keynia.l]` | `[Sesuai]` |
| 2 | Login dengan kredensial tidak valid | `[Sistem menampilkan kembali halaman login disertai pesan kesalahan karena data login tidak valid.]` | `[Sesuai]` |
| 3 | Logout melalui halaman administrasi | `[Pengguna menekan tombol Logout, kemudian diarahkan kembali ke halaman /auth/login dan sesi login diakhir]` | `[Sesuai]` |
| 4 | Akses halaman admin setelah logout | `[Setelah logout, pengguna mencoba mengakses halaman /admin. Sistem seharusnya mengarahkan pengguna kembali ke halaman login karena sesi sudah berakhir.]` | `[Sesuai]` |
Catat hasil berdasarkan eksekusi yang benar-benar dilakukan. Jika ditemukan masalah, dokumentasikan
perbaikan yang dilakukan dan ulangi pengujian yang terdampak.
### Perbaikan
| Masalah yang Ditemukan | Perbaikan yang Dilakukan | Hasil Setelah Perbaikan |
| ---------------------- | ------------------------ | ----------------------- |
| `[Tidak ditemukan masalah pada saat pengujian M.3.]` | `[Tidak dilakukan perbaikan karena proses login, logout, dan pengalihan halaman berjalan sesuai dengan alur PRG yang direncanakan.]` | `[Proses autentikasi berjalan sesuai dengan alur yang diharapkan.]` |
Jika tidak ditemukan masalah, tuliskan:
> Tidak ditemukan masalah pada saat pengujian M.3.