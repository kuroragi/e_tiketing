# Sistem E-Ticketing Kominfo — Kota Bukittinggi

Sistem manajemen tiket layanan IT berbasis web untuk **Dinas Komunikasi dan Informatika Kota Bukittinggi**. Dibangun dengan Laravel 12, sistem ini melayani dua kelompok pengguna: staf internal (SKPD, Petugas, Pimpinan, Admin) dan masyarakat umum melalui landing page publik serta REST API.

---

## Daftar Isi

- [Gambaran Umum](#gambaran-umum)
- [Tech Stack](#tech-stack)
- [Arsitektur Sistem](#arsitektur-sistem)
- [Fitur Utama](#fitur-utama)
- [Peran & Izin Pengguna](#peran--izin-pengguna)
- [Alur Status Tiket](#alur-status-tiket)
- [Struktur Database](#struktur-database)
- [Struktur Direktori](#struktur-direktori)
- [REST API](#rest-api)
- [Keamanan](#keamanan)
- [Instalasi & Setup](#instalasi--setup)
- [Konfigurasi .env Penting](#konfigurasi-env-penting)
- [Artisan Commands](#artisan-commands)
- [Seeder & Data Awal](#seeder--data-awal)

---

## Gambaran Umum

Sistem ini memungkinkan SKPD (Satuan Kerja Perangkat Daerah) di lingkungan Pemkot Bukittinggi untuk mengajukan tiket IT ke Kominfo, yang kemudian ditangani oleh Petugas IT. Masyarakat umum juga dapat mengajukan pengaduan melalui halaman publik tanpa perlu membuat akun, dengan sistem pelacakan tiket via kode unik.

```
Masyarakat/SKPD → Buat Tiket → Admin/Petugas Assign → Petugas Proses → Selesai/Ditolak
```

---

## Tech Stack

| Komponen | Teknologi |
|---|---|
| Framework Backend | Laravel 12 (PHP ≥ 8.2) |
| Frontend | Blade Templates · Bootstrap 5.3 · Tailwind CSS 4 · Vite 7 |
| Database | MySQL / MariaDB |
| Auth & API Token | Laravel Sanctum 4 |
| Role & Permission | Spatie Laravel Permission 7 |
| Notifikasi | Telegram Bot API |
| File Upload | Laravel Storage (local disk) |
| Build Tool | Vite + laravel-vite-plugin |
| Testing | PHPUnit 11 |

---

## Arsitektur Sistem

```
┌─────────────────────────────────────────────────────┐
│                   WEB ROUTES (/*)                    │
│  Landing Page (publik)  │  App (login required)      │
└────────────┬────────────┴──────────┬─────────────────┘
             │                       │
    LandingController           KominfoController
    PageController              AdminPageController
                                TicketManagementController
                                RoleController / PermissionController

┌─────────────────────────────────────────────────────┐
│                  API ROUTES (/api)                   │
│  /api/mobile/*  (Sanctum)  │  /api/v1/*  (API Key)  │
└────────────┬───────────────┴──────────┬─────────────┘
             │                          │
   MobileAuthController          PublicTicketController
   MobileTicketController
   MobileDashboardController
   MobileReportController
   MobileUserController
```

---

## Fitur Utama

### Manajemen Tiket
- Pembuatan tiket oleh SKPD (internal) atau masyarakat (publik via API/landing page)
- Nomor tiket otomatis dengan format `YYYY-MM-XXXX`
- Multi-assignee: satu tiket dapat ditangani lebih dari satu petugas (tabel pivot `ticket_assignees`)
- Komentar & progress update pada tiket
- Upload lampiran (PDF, JPG, PNG) dengan validasi **magic bytes** (bukan hanya ekstensi)
- Ekspor laporan ke CSV dengan filter periode, SKPD, dan kategori

### Dashboard Role-Aware
- Statistik tiket real-time (total, baru, diproses, selesai)
- Rata-rata waktu penyelesaian
- Chart bulanan & workload petugas
- Quick actions berbeda per peran

### Manajemen Admin
- CRUD Pengguna (assign role, aktif/nonaktif)
- CRUD SKPD/Departemen (10 departemen default)
- CRUD Kategori layanan IT (10 kategori default)
- CRUD Prioritas (Urgent, Tinggi, Sedang, Rendah)
- Manajemen Role & Permission (Spatie)
- Pengaturan sistem (nama app, SMTP, upload limit, API key)
- Kustomisasi Landing Page (hero text, warna, toggle fitur)
- Audit Log lengkap semua aktivitas

### Landing Page Publik
- Hero section dengan statistik tiket real-time
- Informasi layanan unggulan
- Form pengaduan masyarakat (nama, NIK, email, HP, alamat, kategori, deskripsi, lampiran)
- Lacak status tiket via kode tracking (UUID)
- Konten dinamis dari tabel `settings`

### REST API
- **Public API** (`/api/v1/`): submit & lacak pengaduan publik, dilindungi `X-API-Key`
- **Mobile API** (`/api/mobile/`): full CRUD tiket untuk aplikasi mobile, auth via Sanctum token

### Notifikasi
- Telegram Bot (`TelegramService`) — perlu konfigurasi `TELEGRAM_BOT_TOKEN` & `TELEGRAM_CHAT_ID`

---

## Peran & Izin Pengguna

| Peran | Deskripsi | Izin Utama |
|---|---|---|
| **Admin** | Akses penuh ke seluruh sistem | Semua permission |
| **Petugas** | Staf IT Kominfo yang menangani tiket | lihat-tiket, kelola-tiket, assign-tiket, tutup-tiket, lihat-laporan |
| **Pimpinan** | Pejabat Kominfo, read-only + laporan | lihat-tiket, lihat-laporan, export-laporan |
| **SKPD** | Staf dari departemen lain, pembuat tiket | buat-tiket, lihat-tiket (departemen sendiri) |

> Permission dikelola via Spatie Laravel Permission. Admin memiliki bypass penuh di `TicketPolicy::before()`.

---

## Alur Status Tiket

```
[baru] ──assign──► [diproses] ──► [menunggu_verifikasi] ──► [selesai]
  │                    │
  └──► [dibatalkan]    └──► [ditolak]
```

| Status | Arti |
|---|---|
| `baru` | Tiket baru dibuat, belum ada petugas |
| `diproses` | Petugas sedang menangani |
| `menunggu_verifikasi` | Petugas selesai, menunggu konfirmasi SKPD/Admin |
| `selesai` | Tiket ditutup, masalah terselesaikan |
| `ditolak` | Tiket tidak dapat diproses |
| `dibatalkan` | Dibatalkan oleh pemohon |

---

## Struktur Database

### Tabel Utama

| Tabel | Deskripsi |
|---|---|
| `users` | Pengguna sistem (Admin, Petugas, Pimpinan, SKPD) |
| `departments` | SKPD — departemen pemerintahan |
| `categories` | Jenis pekerjaan/layanan IT |
| `priorities` | Tingkat prioritas tiket (weight 1–4) |
| `tickets` | Tiket utama dengan semua field |
| `ticket_comments` | Komentar & progress update per tiket |
| `ticket_attachments` | File lampiran tiket |
| `ticket_assignees` | Pivot: petugas yang ditugaskan (many-to-many) |
| `audit_logs` | Log semua aktivitas sistem |
| `settings` | Konfigurasi aplikasi berbasis key-value |

### Kolom Penting `tickets`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `number` | string | Format `YYYY-MM-XXXX`, unik per bulan |
| `status` | enum | baru, diproses, selesai, ditolak, dibatalkan |
| `source` | enum | `internal` (SKPD login) / `public` (masyarakat) |
| `tracking_code` | uuid | Kode lacak untuk tiket publik |
| `public_name/email/phone/nik/address` | string | Data pelapor untuk tiket publik |
| `assignee_id` | FK | Petugas utama (legacy, masih dipakai) |
| `target_date` | date | Batas waktu penyelesaian |
| `assigned_at / started_at / closed_at` | timestamp | Timeline status |

---

## Struktur Direktori

```
app/
├── Console/Commands/
│   ├── SeedDummyData.php       # Buat data dummy untuk testing
│   └── ClearDummyData.php      # Hapus data dummy
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php              # Login/logout + rate limiting
│   │   ├── KominfoController.php           # Dashboard & tiket (semua role)
│   │   ├── AdminPageController.php         # CRUD admin (user, SKPD, kategori, setting)
│   │   ├── TicketManagementController.php  # Manajemen & assignment tiket
│   │   ├── LandingController.php           # Landing page publik
│   │   ├── PageController.php              # Halaman statis
│   │   ├── RoleController.php              # CRUD role
│   │   ├── PermissionController.php        # CRUD permission
│   │   └── Api/
│   │       ├── MobileAuthController.php    # Auth mobile (Sanctum)
│   │       ├── MobileTicketController.php  # CRUD tiket mobile
│   │       ├── MobileDashboardController.php
│   │       ├── MobileReportController.php
│   │       ├── MobileUserController.php
│   │       └── PublicTicketController.php  # API publik (X-API-Key)
│   └── Middleware/
│       ├── CheckUserActive.php    # Cek status akun aktif
│       ├── SessionTimeout.php     # Timeout idle 60 menit
│       ├── SecurityHeaders.php    # X-Frame-Options, XSS, dll.
│       ├── ValidateApiKey.php     # Validasi X-API-Key (timing-safe)
│       └── RoleMiddleware.php
├── Models/
│   ├── User.php          # HasRoles (Spatie), HasApiTokens (Sanctum)
│   ├── Ticket.php        # Auto-numbering, relasi lengkap
│   ├── Department.php    # scope aktif()
│   ├── Category.php
│   ├── Priority.php
│   ├── TicketComment.php
│   ├── TicketAttachment.php
│   ├── AuditLog.php      # actionLabel() helper
│   └── Setting.php       # get()/set() static helpers
├── Policies/
│   └── TicketPolicy.php  # Otorisasi berbasis peran
├── Rules/
│   └── SafeFile.php      # Validasi magic bytes file upload
└── Services/
    └── TelegramService.php  # Kirim notifikasi Telegram

resources/views/
├── landing.blade.php              # Beranda publik
├── layouts/
│   ├── app.blade.php              # Layout utama (Bootstrap)
│   ├── landing.blade.php          # Layout halaman publik
│   └── e-ticket.blade.php         # Layout sidebar app
├── auth/login.blade.php
├── kominfo/                       # View tiket (SKPD/Petugas)
│   ├── dashboard.blade.php
│   ├── tiket-daftar.blade.php
│   ├── tiket-detail.blade.php
│   ├── tiket-pengajuan.blade.php
│   └── laporan.blade.php
└── pages/admin/                   # View admin panel
    ├── dashboard.blade.php
    ├── pengguna.blade.php
    ├── skpd.blade.php
    ├── jenis-pekerjaan.blade.php
    ├── pengaturan.blade.php
    ├── landing.blade.php          # Kustomisasi landing + API settings
    ├── log-aktivitas.blade.php
    └── manajemen-tiket/
        ├── index.blade.php        # Tiket pending
        ├── manual-assignment.blade.php
        ├── auto-assignment.blade.php
        └── history.blade.php
```

---

## REST API

### Public API — `GET/POST /api/v1/`
Dilindungi header `X-API-Key`. Key dikonfigurasi di `/admin/pengaturan`.

| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/v1/categories` | Daftar kategori layanan |
| GET | `/api/v1/priorities` | Daftar prioritas |
| POST | `/api/v1/tickets` | Buat pengaduan baru |
| GET | `/api/v1/tickets/{trackingCode}` | Lacak status pengaduan |

### Mobile API — `/api/mobile/`
Auth menggunakan Bearer Token (Sanctum). Login via `POST /api/mobile/login`.

| Method | Endpoint | Deskripsi |
|---|---|---|
| POST | `/api/mobile/login` | Login, mendapat token |
| POST | `/api/mobile/logout` | Logout, revoke token |
| GET | `/api/mobile/dashboard` | Statistik dashboard |
| GET/POST | `/api/mobile/tickets` | List / buat tiket |
| GET | `/api/mobile/tickets/{id}` | Detail tiket |
| PUT | `/api/mobile/tickets/{id}/status` | Ubah status tiket |
| POST | `/api/mobile/tickets/{id}/assign` | Assign petugas |
| POST | `/api/mobile/tickets/{id}/comments` | Tambah komentar |
| POST | `/api/mobile/tickets/{id}/attachments` | Upload lampiran |
| GET | `/api/mobile/categories` | Referensi kategori |
| GET | `/api/mobile/priorities` | Referensi prioritas |
| GET | `/api/mobile/departments` | Referensi departemen |
| GET | `/api/mobile/users` | Daftar pengguna (admin) |
| PATCH | `/api/mobile/users/{id}/status` | Toggle status user |
| GET | `/api/mobile/reports` | Data laporan |

---

## Keamanan

| Mekanisme | Implementasi |
|---|---|
| CSRF Protection | Laravel built-in, aktif semua route web |
| Rate Limiting Login | 5 percobaan / 60 detik per email+IP (`RateLimiter`) |
| Session Timeout | Idle 60 menit (`SessionTimeout` middleware) |
| Cek Akun Aktif | `CheckUserActive` middleware — auto-logout jika nonaktif |
| Security Headers | `SecurityHeaders` middleware: X-Frame-Options, X-Content-Type-Options, X-XSS-Protection, dll. |
| File Upload Safety | `SafeFile` rule — validasi magic bytes, tolak PHP shell, EXE, ELF, ZIP |
| API Key | `hash_equals()` untuk timing-safe comparison, bisa di-disable dari settings |
| Policy-based Auth | `TicketPolicy` — setiap aksi tiket dicek izinnya |
| Password Strength | `Password::min(8)->letters()->numbers()->mixedCase()` |
| Audit Trail | Semua login, logout, CRUD, dan perubahan status dicatat di `audit_logs` |

---

## Instalasi & Setup

### Prasyarat
- PHP ≥ 8.2
- Composer
- Node.js + npm
- MySQL / MariaDB

### Langkah Instalasi

```bash
# 1. Clone repositori
git clone <repo-url> e_ticketing
cd e_ticketing

# 2. Install dependensi PHP & Node
composer install
npm install

# 3. Konfigurasi environment
cp .env.example .env
php artisan key:generate

# 4. Edit .env — isi koneksi database, mail, telegram
# (lihat bagian Konfigurasi .env di bawah)

# 5. Jalankan migrasi & seeder
php artisan migrate
php artisan db:seed

# 6. Build assets frontend
npm run build

# 7. (Opsional) Buat data dummy untuk testing
php artisan app:seed-dummy --tickets=60 --months=3
```

### Jalankan Development Server

```bash
# Menjalankan semua sekaligus: web server, queue, log, dan Vite
composer run dev
```

---

## Konfigurasi .env Penting

```dotenv
APP_NAME="Sistem Ticketing Kominfo"
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=e_ticketing
DB_USERNAME=root
DB_PASSWORD=

# Session
SESSION_LIFETIME=60   # Timeout idle (menit)

# Mail (untuk notifikasi email)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS="noreply@kominfo.bukittinggi.go.id"
MAIL_FROM_NAME="Kominfo Bukittinggi"

# Telegram Notifikasi
TELEGRAM_BOT_TOKEN=
TELEGRAM_CHAT_ID=
```

> **API Key** dikonfigurasi langsung dari panel admin (`/admin/landing` → tab API), bukan dari `.env`.

---

## Artisan Commands

| Command | Deskripsi |
|---|---|
| `php artisan migrate` | Jalankan semua migrasi database |
| `php artisan db:seed` | Jalankan semua seeder (roles, users, departments, categories, priorities, settings) |
| `php artisan db:seed --class=SettingSeeder` | Seed ulang pengaturan saja |
| `php artisan app:seed-dummy` | Buat data dummy tiket & pengguna untuk testing |
| `php artisan app:seed-dummy --tickets=100 --months=6` | Dummy 100 tiket tersebar 6 bulan |
| `php artisan app:clear-dummy` | Hapus semua data dummy |
| `php artisan queue:listen --tries=1` | Jalankan queue worker |
| `php artisan pail` | Tail log real-time di terminal |

---

## Seeder & Data Awal

Menjalankan `php artisan db:seed` akan membuat:

### Akun Default
| Email | Password | Role |
|---|---|---|
| `admin@kominfo.bukittinggi.go.id` | `@Zaq123Qwerty` | Admin |

> **Ganti password segera setelah instalasi!**

### Departemen (10 SKPD)
KOMINFO, DIKBUD, DINKES, DINKEU, BKD, DINPU, DINSOS, DISPERIND, SETDA, BAPPEDA

### Kategori Layanan (10)
PIC Presensi, Perbaikan Portal, Troubleshooting, Maintenance Server, Instalasi Software, Keamanan Jaringan, Migrasi Data, Pelatihan TI, Pengembangan Aplikasi, Lainnya

### Prioritas (4)
| Nama | Weight | Warna | SLA |
|---|---|---|---|
| Urgent | 4 | Merah | Segera |
| Tinggi | 3 | Oranye | 1 hari kerja |
| Sedang | 2 | Biru | 3 hari kerja |
| Rendah | 1 | Hijau | 7 hari kerja |

### Roles & Permissions
- **admin**: semua permission
- **pimpinan**: lihat-tiket, lihat-laporan, export-laporan
- **petugas**: lihat-tiket, kelola-tiket, assign-tiket, tutup-tiket, lihat-laporan
- **skpd**: buat-tiket, lihat-tiket

---

## Lisensi

MIT License — lihat file `LICENSE` untuk detail.
