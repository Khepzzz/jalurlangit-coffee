# ☕ Jalur Langit Coffee – Self Service System (Laravel 11)

Aplikasi layanan self-service untuk pelanggan **Jalur Langit Coffee**, dibangun dengan **Laravel 11**.  
Pelanggan dapat melakukan pemesanan, pembayaran, dan memberi ulasan tanpa login menggunakan **token unik**.

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

---

## ⚠️ Catatan Penting (HTTPS / Hosting)

> **⚠️ Catatan:**  
> Aplikasi **Jalur Langit Coffee – Self Service System** membutuhkan **koneksi HTTPS (Secure Connection)** agar seluruh fitur berjalan dengan baik, terutama:
> - Proses **scan QR Code** dan **validasi token pelanggan**.  
> - Fitur **pembayaran manual** (VA, DANA, QRIS).  
> - Komunikasi **AJAX** serta penggunaan **middleware token** pada halaman pelanggan.  
>
> Jika dijalankan secara **lokal** (menggunakan Laragon), pastikan URL sudah menggunakan **https://**.  
> Kamu dapat mengaktifkan HTTPS di Laragon dengan langkah berikut:
>
> 1. Buka **Laragon → Menu → Apache → SSL → Enable SSL**  
> 2. Setelah itu, akses aplikasi melalui URL seperti:
>    ```
>    https://jalurlangit-coffee.test
>    ```
>
> Jika aplikasi di-deploy ke **hosting**, pastikan server telah memiliki **sertifikat SSL aktif**, agar seluruh fitur dapat berjalan dengan aman, terutama pada proses scan QR dan transaksi pelanggan.

---

