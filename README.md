<p align="center">
  <img src="public/favicon.ico" alt="Tsaniacraft Logo" width="100">
</p>

# Tsaniacraft - E-Commerce Kerajinan Tangan

**Tsaniacraft** adalah platform e-commerce yang dirancang khusus untuk mempermudah transaksi jual beli berbagai produk kerajinan tangan (*handicraft*). Platform ini menyediakan katalog produk yang indah, fitur keranjang belanja, serta sistem checkout terintegrasi yang memudahkan pembeli.

Tsaniacraft menonjol dengan integrasi layanan pengiriman lokal terpercaya, **Komerce.id**, untuk menyediakan estimasi ongkos kirim (ongkir) secara real-time langsung pada halaman checkout. Selain itu, sistem ini mendukung gerbang pembayaran menggunakan **Midtrans** untuk transaksi yang cepat dan aman.

Tsaniacraft dibangun dengan fokus keamanan dan pengalaman pengguna, menerapkan kebijakan autentikasi yang kuat dan fitur proteksi seperti *brute-force protection* pada halaman login, memastikan lingkungan transaksi yang aman bagi pembeli maupun administrator.

## ✨ Fitur Utama

- **Katalog Produk Dinamis**: Penampilan produk kerajinan yang menarik dengan dukungan multiple gambar dan deskripsi.
- **Sistem Keranjang & Checkout**: Manajemen keranjang belanja dengan sesi pengguna yang mulus.
- **Integrasi Ongkos Kirim (Komerce.id)**: Kalkulasi biaya ongkos kirim secara instan ke berbagai wilayah di Indonesia.
- **Pembayaran Otomatis (Midtrans)**: Mendukung berbagai metode pembayaran (GoPay, Transfer Bank, QRIS, dll) dengan pembaruan status pesanan secara otomatis.
- **Sistem Kupon & Diskon**: Manjemen promosi dengan potongan harga berjenis nominal (*fixed*) atau persentase, dilengkapi syarat minimal belanja dan batas pemakaian.
- **Ulasan & Rating Produk**: Pembeli yang telah menerima barang dapat memberikan penilaian bintang (1-5) dan ulasan pengalaman mereka.
- **Panel Admin Komprehensif**: Manajemen produk, kategori, pengguna, ulasan, pesanan, dan laporan log transaksi, dilengkapi dengan *pagination* agar ringan memuat data besar.
- **Keamanan yang Kuat**: Proteksi manipulasi *cart*, validasi keranjang saat pembayaran, serta sistem anti *brute-force* pada halaman otentikasi.

## 🛠️ Teknologi yang Digunakan

- **Backend**: [Laravel 10+](https://laravel.com) (PHP)
- **Frontend / Styling**: Blade Templating, [Tailwind CSS](https://tailwindcss.com), dan [Alpine.js](https://alpinejs.dev)
- **Database**: MySQL
- **Integrasi Pihak Ketiga**:
  - [Midtrans](https://midtrans.com/) (Payment Gateway)
  - [Komerce](https://komerce.id/) (Shipping API)

## 🚀 Panduan Instalasi (Development)

Untuk menjalankan proyek ini di lingkungan lokal Anda, ikuti langkah-langkah berikut:

1. **Kloning repositori ini:**
   ```bash
   git clone https://github.com/EkaDimas12/ecommerce.git
   cd ecommerce
   ```

2. **Instal dependensi Composer:**
   ```bash
   composer install
   ```

3. **Instal dependensi NPM & kompilasi aset:**
   ```bash
   npm install
   npm run build
   ```

4. **Konfigurasi Environment:**
   Salin file konfigurasi `.env.example` menjadi `.env` lalu sesuaikan kredensial database dan API Keys Anda.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Pastikan untuk mengisi kredensial API Midtrans dan Komerce di dalam `.env`.*

5. **Migrasi Database & Seeding (opsional):**
   ```bash
   php artisan migrate
   ```

6. **Jalankan Server Lokal:**
   ```bash
   php artisan serve
   ```
   Aplikasi Anda kini berjalan di `http://localhost:8000`.

## 📜 Lisensi

Aplikasi ini bersifat *open-source* dan dilisensikan di bawah [MIT License](https://opensource.org/licenses/MIT).

