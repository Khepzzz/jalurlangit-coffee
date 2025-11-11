# ☕ Jalur Langit Coffee – Self Service System (Laravel 11)

Aplikasi layanan self-service untuk pelanggan **Jalur Langit Coffee**, dibangun dengan **Laravel 11**.  
Pelanggan dapat melakukan pemesanan, pembayaran, dan memberi ulasan tanpa login menggunakan **token unik** dari QR Code.

---

## 🌟 Fitur Utama

### 👤 Pelanggan (Tanpa Login)
- ambil token → isi nama & nomor kursi  
- Lihat daftar produk, tambah ke keranjang, dan checkout  
- Upload bukti pembayaran (VA, DANA, QRIS)  
- Lihat status pesanan & beri ulasan setelah selesai  

### 👨‍💼 Pegawai / Admin
- Login dan kelola data produk  
- Verifikasi dan ubah status pembayaran  
- Ubah status pesanan (Pending → Diproses → Selesai)  
- Lihat dan moderasi ulasan pelanggan  

---

## ⚙️ Cara Instalasi (Laravel 11 + Laragon)

1. Clone repository  
   `git clone https://github.com/Khepzzz/jalurlangit-coffee.git`  
   `cd jalurlangit-coffee`

2. Install dependensi  
   Pastikan sudah terpasang **Composer** dan **Node.js**  
   `composer install`  
   `npm install`

3. Konfigurasi file `.env`  
   `cp .env.example .env`  
   Lalu ubah bagian database sesuai pengaturan Laragon:  
    DB_DATABASE=jalurlangit
    DB_USERNAME=root
    DB_PASSWORD=

4. Generate key aplikasi  
`php artisan key:generate`

5. Migrasi & seeder database  
`php artisan migrate --seed`

6. Jalankan server lokal  
`php artisan serve`  
Buka di browser: **http://localhost:8000**