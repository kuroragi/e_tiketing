# Catatan Pengembangan & Status Pekerjaan

> **Terakhir diperbarui:** 6 April 2026  
> **Proyek:** Sistem E-Ticketing Kominfo Kota Bukittinggi  
> **Framework:** Laravel 11 · PHP 8.x · Bootstrap 5.3 · MySQL/MariaDB

---

## Gambaran Umum Sistem

Sistem E-Ticketing ini dibangun untuk dua kelompok pengguna:

1. **Pengguna Internal** — SKPD, Petugas Kominfo, Pimpinan, Admin; mengakses sistem via login.
2. **Publik Umum** — Masyarakat yang ingin mengajukan pengaduan atau permintaan data tanpa perlu akun.

---

## ✅ Yang Sudah SELESAI

### Sesi Sebelumnya (Sistem Internal)

| Komponen | Status | Keterangan |
|----------|--------|------------|
| Skema database inti | ✅ | Tabel: users, tickets, ticket_comments, ticket_attachments, ticket_assignees, departments, categories, priorities, settings, audit_logs |
| Autentikasi | ✅ | Login, logout, rate limiting (5x/60s), cek status akun, session timeout 60 menit |
| Role & Permission | ✅ | Spatie laravel-permission: admin, petugas, pimpinan, skpd |
| Alur tiket internal | ✅ | Status: baru → diproses → menunggu\_verifikasi → selesai / ditolak / dibatalkan |
| Multi-assignee | ✅ | Tabel pivot `ticket_assignees`, sinkronisasi opsi manual/auto |
| Dashboard (semua role) | ✅ | Statistik, chart bulanan, workload petugas, rekap SKPD |
| Manajemen tiket SKPD | ✅ | Buat, lihat, batalkan tiket; tambah komentar & lampiran |
| Manajemen tiket Petugas | ✅ | Ambil, proses, progress, ubah status, kirim ke verifikasi |
| Manajemen tiket Admin | ✅ | Lihat semua, assign, tutup, tolak, hapus |
| Laporan & Ekspor CSV | ✅ | Filter periode, per-SKPD, per-kategori, ekspor CSV |
| Manajemen user | ✅ | CRUD user, assign role, aktif/nonaktif |
| Manajemen SKPD | ✅ | CRUD departemen, kode unik |
| Manajemen kategori | ✅ | CRUD jenis pekerjaan |
| Pengaturan sistem | ✅ | App info, SMTP, upload, kontak, media sosial, departemen kontak |
| Audit log | ✅ | Semua aksi tercatat: login, logout, CRUD, status change, assign |
| Upload lampiran | ✅ | Validasi magic bytes (SafeFile rule), maks 5 file × 10 MB |
| Keamanan | ✅ | CSRF, XSS headers, X-Frame-Options, Rate limiting, SecurityHeaders middleware |
| Notifikasi Telegram | ✅ | TelegramService siap pakai (perlu konfigurasi bot token) |
| Halaman statis | ✅ | Panduan, Tentang, Hubungi Kami, Kebijakan, Syarat & Ketentuan |
| Seeder data awal | ✅ | Departments (10), Categories (10), Priorities (4), Roles & Permissions, Settings |

---

### Sesi Ini (6 April 2026) — Fitur Baru

#### 1. Landing Page Publik

| File | Status | Keterangan |
|------|--------|------------|
| `resources/views/layouts/landing.blade.php` | ✅ | Layout baru khusus halaman publik. Navbar, footer dinamis dari Setting, smooth scroll |
| `resources/views/landing.blade.php` | ✅ | Halaman beranda publik: hero section + statistik real-time, layanan unggulan, cara kerja, tiket terbaru, CTA |
| `resources/views/public/submit-ticket.blade.php` | ✅ | Form pengaduan publik: data pelapor (nama, NIK, email, HP, alamat), kategori, judul, deskripsi, prioritas, lampiran, captcha sederhana |
| `resources/views/public/ticket-success.blade.php` | ✅ | Halaman sukses setelah submit: kode tracking, ringkasan tiket, tombol lacak |
| `resources/views/public/track-ticket.blade.php` | ✅ | Halaman lacak tiket: progress bar status, detail tiket, timeline aktivitas |

#### 2. Controller

| File | Status | Keterangan |
|------|--------|------------|
| `app/Http/Controllers/LandingController.php` | ✅ | `index()`, `createTicket()`, `storeTicket()`, `ticketSuccess()`, `trackTicket()` |
| `app/Http/Controllers/Api/PublicTicketController.php` | ✅ | `categories()`, `priorities()`, `store()`, `show()` |

#### 3. Middleware

| File | Status | Keterangan |
|------|--------|------------|
| `app/Http/Middleware/ValidateApiKey.php` | ✅ | Validasi `X-API-Key` header, cek setting `api_enabled`, `hash_equals` untuk timing-safe comparison |

#### 4. Database

| File | Status | Keterangan |
|------|--------|------------|
| `database/migrations/2026_04_06_000001_add_public_ticket_fields.php` | ✅ | Kolom baru: `public_name`, `public_email`, `public_phone`, `public_nik`, `public_address`, `source` (enum), `tracking_code` (UUID); `requester_id` & `user_id` di ticket_attachments jadi nullable |

#### 5. Model

| File | Status | Keterangan |
|------|--------|------------|
| `app/Models/Ticket.php` | ✅ | Ditambahkan field public ke `$fillable`: `public_name`, `public_email`, `public_phone`, `public_nik`, `public_address`, `source`, `tracking_code` |

#### 6. Routes

| File | Status | Keterangan |
|------|--------|------------|
| `routes/web.php` | ✅ | Rute baru: `GET /` (landing), `GET/POST /pengaduan`, `GET /pengaduan/sukses/{trackingCode}`, `GET /lacak`, admin landing settings |
| `routes/api.php` | ✅ | File baru: `GET /api/v1/categories`, `GET /api/v1/priorities`, `POST /api/v1/tickets`, `GET /api/v1/tickets/{code}` — semua dilindungi middleware `api.key` |
| `bootstrap/app.php` | ✅ | Registrasi file `routes/api.php` dan alias middleware `api.key` |

#### 7. Admin Panel

| File | Status | Keterangan |
|------|--------|------------|
| `resources/views/pages/admin/landing.blade.php` | ✅ | Halaman pengaturan landing page: hero teks, warna (color picker), layanan unggulan (CRUD tabel), toggle fitur publik, pengaturan API (enable/disable, rate limit, generate API key) |
| `app/Http/Controllers/AdminPageController.php` | ✅ | Method `landing()` dan `saveLanding()` ditambahkan |
| `resources/views/layouts/e-ticket.blade.php` | ✅ | Link "Landing Page" ditambahkan di sidebar admin |

#### 8. Seeder

| File | Status | Keterangan |
|------|--------|------------|
| `database/seeders/SettingSeeder.php` | ✅ | Setting baru: `landing_hero_title`, `landing_hero_subtitle`, `landing_services_title/subtitle`, `landing_primary_color/dark`, `landing_enable_public_ticket`, `landing_show_stats`, `landing_show_recent`, `api_enabled`, `api_rate_limit`, `api_key` |

---

## ❌ Yang Belum Selesai / Perlu Dilanjutkan

### 🔴 Prioritas Tinggi (Wajib Diselesaikan)

| # | Item | Alasan Belum Selesai |
|---|------|----------------------|
| 1 | **Jalankan migrasi database** (`php artisan migrate`) | Vendor/autoload.php belum ada; perlu `composer install` di environment yang berjalan |
| 2 | **Jalankan seeder setting baru** (`php artisan db:seed --class=SettingSeeder`) | Bergantung pada migrasi selesai |
| 3 | **API rate limit throttle** | Perlu konfigurasi named throttle `api` di `bootstrap/app.php` atau `RouteServiceProvider` |
| 4 | **Generate API key awal** | Admin harus membuka `/admin/landing` → tab API → klik "Generate Baru" → simpan |

### 🟡 Prioritas Sedang (Disarankan)

| # | Item | Deskripsi |
|---|------|-----------|
| 5 | **Notifikasi email ke pelapor publik** | Kirim email konfirmasi dengan tracking code setelah submit tiket publik |
| 6 | **Notifikasi email ke admin** | Pemberitahuan saat ada tiket publik/API baru masuk |
| 7 | **Integrasi Telegram** | `TelegramService` sudah ada, tinggal hook ke event `ticket.created` (khususnya tiket publik) |
| 8 | **CAPTCHA yang lebih kuat** | Saat ini captcha hanya penjumlahan angka sederhana; pertimbangkan Google reCAPTCHA v3 untuk produksi |
| 9 | **Filter tiket publik di admin** | Tambahkan filter `source = public/api` di halaman daftar tiket agar admin mudah membedakan |
| 10 | **Tampilan tiket publik di dashboard** | Tambahkan card/widget tiket publik yang belum ditangani di dashboard admin |
| 11 | **Penomoran tiket publik** | Pertimbangkan prefix berbeda, misal `PUB-YYYY-MM-XXXX` vs `YYYY-MM-XXXX` untuk internal |

### 🟢 Pengembangan Lanjutan (Opsional / Fase 2)

| # | Item | Deskripsi |
|---|------|-----------|
| 12 | **WebSocket / real-time update** | Status tiket diupdate live tanpa reload (Laravel Reverb / Pusher) |
| 13 | **Mobile app** | Aplikasi Android/iOS untuk pelaporan publik menggunakan API yang sudah tersedia |
| 14 | **Otentikasi API via OAuth / Sanctum** | Untuk aplikasi pihak ketiga yang butuh akses lebih dalam (bukan hanya tiket publik) |
| 15 | **Ekspor PDF laporan** | Laporan periodik dalam format PDF bergambar grafik |
| 16 | **SSO / integrasi SIMPEG** | Login terintegrasi dengan sistem kepegawaian Pemkot Bukittinggi |
| 17 | **SLA otomatis & eskalasi** | Peringatan otomatis jika tiket melewati batas waktu penyelesaian sesuai prioritas |
| 18 | **Rating kepuasan** | SKPD/pelapor bisa memberi nilai setelah tiket selesai |
| 19 | **API dokumentasi interaktif** | Swagger/OpenAPI untuk mempermudah pengembang pihak ketiga |
| 20 | **Multi-bahasa (i18n)** | Dukungan Bahasa Inggris selain Bahasa Indonesia |

---

## Struktur File Baru (Sesi Ini)

```
app/
  Http/
    Controllers/
      Api/
        PublicTicketController.php      ← BARU
      LandingController.php              ← BARU
      AdminPageController.php            ← DIUBAH (tambah landing/saveLanding)
    Middleware/
      ValidateApiKey.php                 ← BARU
  Models/
    Ticket.php                           ← DIUBAH (fillable baru)

database/
  migrations/
    2026_04_06_000001_add_public_ticket_fields.php  ← BARU
  seeders/
    SettingSeeder.php                    ← DIUBAH (setting baru)

resources/views/
  layouts/
    landing.blade.php                    ← BARU
    e-ticket.blade.php                   ← DIUBAH (link Landing Page di sidebar)
  landing.blade.php                      ← BARU
  public/
    submit-ticket.blade.php              ← BARU
    ticket-success.blade.php             ← BARU
    track-ticket.blade.php               ← BARU
  pages/admin/
    landing.blade.php                    ← BARU

routes/
  web.php                                ← DIUBAH (rute landing & publik)
  api.php                                ← BARU

bootstrap/
  app.php                                ← DIUBAH (registrasi api routes & middleware)
```

---

## Rute Publik Baru

| Method | URL | Nama | Keterangan |
|--------|-----|------|------------|
| GET | `/` | `landing` | Halaman beranda publik |
| GET | `/home` | `home` | Alias landing (kompatibilitas) |
| GET | `/pengaduan` | `public.ticket.create` | Form pengaduan publik |
| POST | `/pengaduan` | `public.ticket.store` | Proses simpan pengaduan |
| GET | `/pengaduan/sukses/{code}` | `public.ticket.success` | Halaman sukses + kode tracking |
| GET | `/lacak` | `public.ticket.track` | Lacak status tiket |
| GET | `/admin/landing` | `admin.landing` | Pengaturan landing page (admin only) |
| POST | `/admin/landing` | `admin.landing.save` | Simpan pengaturan landing |

## REST API

| Method | Endpoint | Auth | Keterangan |
|--------|----------|------|------------|
| GET | `/api/v1/categories` | `X-API-Key` | Daftar kategori aktif |
| GET | `/api/v1/priorities` | `X-API-Key` | Daftar prioritas |
| POST | `/api/v1/tickets` | `X-API-Key` | Buat pengaduan baru |
| GET | `/api/v1/tickets/{code}` | `X-API-Key` | Detail & status tiket |

### Contoh Request API

```http
POST /api/v1/tickets
X-API-Key: etk_xxxxxxxxxxxxx
Content-Type: application/json

{
  "name": "Budi Santoso",
  "email": "budi@example.com",
  "phone": "08123456789",
  "nik": "1371011234567890",
  "title": "Permintaan Rekaman CCTV",
  "description": "Saya ingin meminta rekaman CCTV pada tanggal 5 April 2026 pukul 14:00-16:00 di Jl. Ahmad Yani terkait kejadian pencurian...",
  "category_id": 1,
  "priority_id": 2
}
```

```json
// Response 201 Created
{
  "success": true,
  "message": "Pengaduan berhasil dibuat.",
  "data": {
    "ticket_number": "2026-04-0001",
    "tracking_code": "550e8400-e29b-41d4-a716-446655440000",
    "title": "Permintaan Rekaman CCTV",
    "status": "baru",
    "created_at": "2026-04-06T10:00:00+07:00"
  }
}
```

---

## Langkah Deployment Selanjutnya

```bash
# 1. Install dependencies (jika belum)
composer install

# 2. Copy & edit .env
cp .env.example .env
php artisan key:generate

# 3. Jalankan migrasi
php artisan migrate

# 4. Seed data awal
php artisan db:seed

# 5. (Jika seeder sudah jalan sebelumnya, seed hanya setting baru)
php artisan db:seed --class=SettingSeeder

# 6. Buat storage symlink
php artisan storage:link

# 7. Buka /admin/landing → tab API → Generate API Key → Simpan

# 8. Buka / untuk lihat landing page publik
```

---

## Catatan Teknis Penting

### Keamanan
- **API Key** disimpan di tabel `settings` dengan key `api_key`. Validasi menggunakan `hash_equals()` (timing-safe) mencegah timing attack.
- **Captcha** di form publik menggunakan HMAC-SHA256 untuk mencegah manipulasi client-side. Disarankan mengganti dengan Google reCAPTCHA v3 di produksi.
- **File upload publik** tetap divalidasi dengan `SafeFile` rule (magic bytes), bukan hanya ekstensi.
- **Rate limiting API** menggunakan named throttle `api` (30 req/menit default, dapat dikonfigurasi via admin).

### Kompatibilitas
- Semua rute `/dashboard` (redirect lama dari `/`) tetap berfungsi karena `Route::redirect` dihapus dan diganti dengan `/` → landing page.
- Tiket internal yang sudah ada tidak terpengaruh karena kolom baru semua `nullable` dengan default yang aman.
- `requester_id` menjadi nullable — kompatibel dengan validasi bestehende policy karena `TicketPolicy::create()` masih memeriksa `$user->isSkpd()`.
