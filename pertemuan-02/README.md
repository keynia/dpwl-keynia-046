## 1. Tujuan Praktikum 
jawaban:[Praktikum ini bertujuan untuk memahami dan mengimplementasikan arsitektur dasar MVC (Model-View-Controller) serta alur kerja Routing dan Front Controller pada pemrograman web berorientasi objek.]  
 
## 2. Struktur Direktori 
jawaban:
 dpwl-nim/
├── application/
│   ├── config/       # Konfigurasi aplikasi dan route
│   ├── controllers/  # Mengatur request dan memilih View
│   ├── helpers/      # Fungsi bantuan URL
│   └── views/        # Halaman yang ditampilkan
├── assets/           # File CSS dan aset lainnya
├── system/core/      # Router dan Base Controller
├── dokumentasi/      # Bukti tangkapan layar
├── index.php         # Front controller, titik masuk aplikasi
 
## 3. Front controller 
jawaban:[index.php berfungsi sebagai satu pintu masuk utama (front controller) dalam aplikasi P2.jadi, request dari browser untuk fitur atau route aplikasi akan masuk terlebih dahulu ke index.php.

Tugasnya:
menerima request -> memuat konfigurasi, Helper, dan class inti-> mengambil route -> menyerahkan request ke Router

contohnya,saat membuka

http://localhost/dpwl-nim/index.php/info/routing]

## 4. Routing dan Pemetaan URL 
  | URL/Route | Controller | Method | Parameter | View | |---|---|---|---|---| | / | Home | index | - | home/index.php | | home/index | Home | index | - | home/index.php | | home/info/mvc | Home | info | mvc | home/info.php | | info/routing | Home | info | routing | home/info.php |

  Tambahkan satu baris untuk route hasil Tahap Modifikasi ATM yang dibuat berdasarkan objek atau konteks aplikasi DPW, kemudian jelaskan pemetaan route → Controller → method → parameter → View.

  jawaban:
  | URL/Route         | Controller | Method   | Parameter  | View              |
| ----------------- | ---------- | -------- | ---------- | ----------------- |
| `/`               | Home       | index    | -          | home/index.php    |
| `home/index`      | Home       | index    | -          | home/index.php    |
| `home/info/mvc`   | Home       | info     | mvc        | home/info.php     |
| `info/routing`    | Home       | info     | routing    | home/info.php     |
| **`info/profil`** | **Home**   | **info** | **profil** | **home/info.php** |

Penjelasan:
Route info/profil akan diarahkan oleh Router ke Controller Home, kemudian menjalankan method info() dengan parameter profil. Setelah itu Controller memanggil View home/info.php untuk menampilkan hasilnya. Jadi alurnya adalah route → Controller → method → parameter → View.


## 5. Base URL dan Helper 
   Jelaskan fungsi base_url() dan site_url(), kemudian berikan contoh penggunaannya pada implementasi P2: 
    - base_url() untuk memanggil assets/css/app.css; 
    jawaban:
    [base_url() berfungsi untuk membuat URL dasar aplikasi, biasanya digunakan untuk memanggil file assets seperti CSS, JavaScript, atau gambar.]
    contoh:
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">

    - site_url() untuk membentuk URL navigasi/route aplikasi.
    jawaban:
    [site_url() berfungsi untuk membuat URL yang mengarah ke route atau halaman aplikasi.]
    contoh:
   <a href="<?= site_url('info/routing') ?>">Routing</a>

 ## 6. Alur Request-response 
    Jelaskan dua alur berikut: 
     1. Alur eksekusi aktual P2: 
     Browser → index.php → Router → Controller → View → Response.

     jawaban:[Alurnya dimulai saat Browser mengirim request ke aplikasi. Request masuk melalui index.php sebagai front controller, kemudian diteruskan ke Router untuk menentukan Controller dan method yang sesuai. Setelah itu Controller memproses request dan menyiapkan data untuk View. View kemudian menghasilkan HTML yang dikirim kembali ke Browser sebagai Response.]
     
     2. Posisi Model dalam arsitektur MVC lengkap: Browser → index.php → Router → Controller → Model → basis data/data → Model → Controller → View → Response. 

     jawaban:[Pada MVC lengkap, Model bertugas mengelola data dan berhubungan dengan basis data. Controller meminta atau mengirim data melalui Model, kemudian hasilnya dikembalikan ke Controller untuk diteruskan ke View.]

     Pada implementasi P2, Model belum digunakan karena akses dan pengelolaan basis data mulai diimplementasikan pada P3.

 ## 7. Hasil Pengujian dan Debugging
 Catat skenario pengujian valid dan tidak valid beserta hasilnya. Jika ditemukan kesalahan selama implementasi, dokumentasikan sekurang-kurangnya satu proses debugging yang memuat:  

 Gejala → Penyebab → Perbaikan → Hasil Uji Ulang

 Jika seluruh implementasi langsung berjalan sesuai hasil yang diharapkan, jelaskan hasil pemeriksaan sintaks dan pengujian yang telah dilakukan.

 jawaban:
 *Pengujian valid: membuka halaman utama dan route info/routing. Hasilnya, halaman berhasil tampil sesuai dengan View yang dibuat.
 *Pengujian tidak valid: memasukkan Controller atau method yang tidak tersedia. Hasilnya, sistem menampilkan 404 karena route atau tujuan tidak ditemukan.

Contoh proses debugging:

Gejala: halaman tidak muncul.
Penyebab: nama route atau method pada Controller tidak sesuai.
Perbaikan: mengecek kembali routes.php dan nama method pada Controller.
Hasil uji ulang: halaman berhasil tampil sesuai route.

Selain itu, dilakukan pemeriksaan sintaks PHP, Controller, Method, View, route, dan CSS/base_url untuk memastikan implementasi P2 berjalan dengan baik.


 ## 8. Bukti Tangkapan Layar 
 Sisipkan gambar yang relevan dari folder dokumentasi/ dengan perintah: 

 ### Gambar 1. Hasil Pengujian Halaman Utama  
![Gambar 1 - Halaman Utama](dokumentasi/gambar1. jpg)  

### Gambar 2. Hasil Pengujian Custom Route  
![Gambar 2 - Custom Route](dokumentasi/gambar2.jpg) 

 ## 9. Kesimpulan P2 
 Jelaskan apa yang sudah dapat dilakukan kerangka MVC dan apa yang baru akan ditambahkan pada P3.
 jawaban:
 Pada P2, kerangka MVC sudah dapat menjalankan index.php sebagai front controller, routing, Controller, View, Base URL, dan Helper. Request dari browser sudah bisa diarahkan melalui Router ke Controller dan menghasilkan tampilan dari View.

Pada P3, kerangka MVC akan dikembangkan dengan menambahkan Model dan koneksi basis data, termasuk pengelolaan data, MySQLi/prepared statement, autentikasi, session, dan kontrol akses.