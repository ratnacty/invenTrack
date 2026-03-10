# InvenTrack - Sistem Manajemen Inventaris

Aplikasi manajemen inventaris berbasis web yang dibangun dengan **Laravel 12**, **Vue 3**, dan **Inertia.js**. Memudahkan pengelolaan barang dan kategori dalam satu dashboard yang intuitif.

---

## Fitur Utama

- **Dashboard** — Statistik total barang, kategori, stok, dan nilai inventaris secara real-time
- **Manajemen Kategori** — CRUD kategori barang dengan pencarian
- **Manajemen Barang** — CRUD barang lengkap dengan filter kategori, pencarian, dan paginasi
- **Peringatan Stok Rendah** — Notifikasi otomatis untuk barang yang stoknya menipis
- **Autentikasi** — Login, registrasi, pengaturan profil, dan Two-Factor Authentication (2FA)

---

## Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | PHP 8.2+, Laravel 12 |
| Frontend | Vue 3, Inertia.js, TypeScript |
| Styling | Tailwind CSS 4 |
| Build Tool | Vite 7 |
| Database | MySQL / SQLite |
| Auth | Laravel Fortify (+ 2FA) |
| UI Components | Reka UI, Lucide Icons |

---

## Persyaratan Sistem

- PHP >= 8.2
- Composer
- Node.js >= 18 & NPM
- MySQL atau SQLite
- XAMPP / Laragon / Herd (opsional)

---

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/ratnacty/invenTrack.git
cd invenTrack
```

### 2. Install Dependensi

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env` sesuai konfigurasi database:

```env
APP_NAME=InvenTrack
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventrackDB
DB_USERNAME=root
DB_PASSWORD=
```

> Untuk menggunakan **SQLite**, cukup set `DB_CONNECTION=sqlite` dan hapus baris DB lainnya.

### 4. Migrasi & Seeder Database

```bash
php artisan migrate --seed
```

Perintah ini akan membuat tabel dan akun demo secara otomatis.

### 5. Build Asset Frontend

```bash
npm run build
```

### 6. Jalankan Server

```bash
php artisan serve
```

Akses aplikasi di: **http://localhost:8000**

---

## Akun Default (Demo)

Setelah menjalankan seeder, akun berikut tersedia:

| Field | Value |
|-------|-------|
| **Email** | `test@example.com` |
| **Password** | `password` |

> Segera ganti password setelah login pertama kali melalui menu **Settings > Password**.

---

## Pengembangan (Development Mode)

Jalankan server Laravel, queue worker, dan Vite secara bersamaan:

```bash
composer run dev
```

Atau jalankan secara terpisah:

```bash
# Terminal 1 - Laravel
php artisan serve

# Terminal 2 - Vite (hot reload)
npm run dev

# Terminal 3 - Queue worker (opsional)
php artisan queue:listen
```

---

## Struktur Direktori Penting

```
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php
│   │   ├── KategoriController.php
│   │   └── BarangController.php
│   └── Models/
│       ├── Barang.php
│       └── Kategori.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── js/
│       ├── pages/        # Halaman Vue
│       └── components/   # Komponen reusable
└── routes/
    └── web.php
```

---

## Screenshot

> *(Tambahkan screenshot aplikasi di sini)*

---

## Lisensi

Proyek ini menggunakan lisensi [MIT](LICENSE).

---

## Kontribusi

Pull request sangat diterima! Untuk perubahan besar, buka issue terlebih dahulu untuk mendiskusikan apa yang ingin diubah.

---

**Dibuat dengan Laravel + Vue 3**
