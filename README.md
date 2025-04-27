# WatAPad

NOTE : deployment via hostinger sudah dilakukan. bisa menghubungi saya apabila membutuhkan url app tersebut. saya buat confidential agar tidak ada akses berlebihan untuk menjaga vps.

**WatAPad** adalah aplikasi blog sederhana yang memungkinkan pengguna untuk membuat, mengedit, dan menghapus artikel.  
Pengguna juga dapat mengelompokkan artikel ke dalam kategori serta melakukan registrasi dan login untuk mengelola artikel mereka.

---

## ✨ Deskripsi Fitur

### Artikel
- Membuat artikel baru dengan judul, konten, dan kategori.
- Mengedit artikel yang sudah ada. (sesuai dengan ownership)
- Menghapus artikel. (sesuai dengan ownership)
- Menampilkan daftar artikel dengan judul, ringkasan, dan tanggal publikasi.  (bisa upload gambar juga)
- Menampilkan detail artikel saat diklik.

### Kategori Artikel
- Membuat kategori baru. (sesuai dengan ownership)
- Mengaitkan artikel dengan kategori. (sesuai dengan ownership)
- Menampilkan daftar kategori beserta artikel-artikel yang terkait.

### User
- Registrasi akun pengguna baru.
- Login dan logout.
- Hanya pengguna yang sudah login yang dapat membuat, mengedit, atau menghapus artikel.

---

## 🛠️ Teknologi yang Digunakan

### Backend
- Framework: Laravel
- Database: PostgreSQL (dideploy di VPS Hostinger)
- Storage: Cloudinary untuk penyimpanan gambar
- Package Manager: Composer

### Frontend
- Template Engine: Blade (Laravel)
- Styling: TailwindCSS (menggunakan Vite)
- JavaScript: Untuk modifikasi DOM (sebagian kecil)
- Hosting: VPS Hostinger

---

## 🚀 Cara Menjalankan Aplikasi di Lokal

1. Buat folder baru di komputer lokal dan buka menggunakan VSCode.
2. Clone repository dengan perintah git clone https://github.com/andiartori/WatAPad.git
3. Masuk ke dalam folder project menggunakan perintah cd WatAPad
4. Buat file .env di root project.
5. Masukkan data kredensial ke dalam file .env (data terdapat di attachment email).
6. Jalankan perintah berikut di terminal:
   - composer install
   - npm install
   - php artisan migrate
   - npm run dev
   - php artisan serve
7. Akses aplikasi melalui URL localhost yang ditampilkan di terminal.

---

## ⚡ Catatan Penting

- Perintah npm run dev digunakan untuk menjalankan TailwindCSS dan Vite di mode development.
- Perintah php artisan serve digunakan untuk menjalankan server Laravel secara lokal.
- Repository ini merupakan backup fullstack working version sebelum dilakukan npm run build, agar selalu memiliki cadangan project yang masih dalam mode development.

---


