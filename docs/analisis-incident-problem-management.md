# Analisis: Pemisahan Tiket Incident vs Problem

> Dokumen ini merupakan hasil brainstorming & analisis titik tengah untuk kebutuhan
> pemisahan tipe tiket berdasarkan permintaan stakeholder. Referensi: ITIL v4.

---

## 1. Konteks & Latar Belakang

Sistem e-ticketing saat ini memperlakukan semua tiket secara seragam — satu tabel,
satu alur status, satu form. Stakeholder meminta agar tiket dapat dibedakan antara
**Incident** dan **Problem**, yang dalam kerangka ITIL memiliki makna yang berbeda
secara fundamental.

---

## 2. Definisi (ITIL-based)

| Konsep | Definisi | Contoh dalam konteks Kominfo |
|--------|----------|------------------------------|
| **Incident** | Gangguan atau penurunan kualitas layanan yang tidak direncanakan. Fokusnya adalah **pemulihan secepat mungkin**. | WiFi publik mati, CCTV tidak dapat diakses, website down |
| **Problem** | **Akar penyebab** dari satu atau lebih incident. Fokusnya adalah **analisis mendalam & solusi permanen**. | Investigasi mengapa WiFi publik sering mati, pola gangguan CCTV berulang |

**Perbedaan kunci:** Incident → restore layanan. Problem → cegah berulang.

---

## 3. Perbedaan yang Dibutuhkan

### 3.1 Perbedaan Form Input

**Field tambahan untuk Incident:**
| Field | Keterangan |
|-------|------------|
| `impact_scope` | Skala dampak: individu / divisi / seluruh organisasi |
| `affected_service` | Layanan spesifik yang terganggu |
| `incident_start_at` | Waktu mulai terjadi gangguan |
| `workaround_available` | Apakah ada solusi sementara? (boolean) |
| `workaround_desc` | Deskripsi solusi sementara jika ada |

**Field tambahan untuk Problem:**
| Field | Keterangan |
|-------|------------|
| `related_incident_ids` | Tiket incident yang berkaitan (many-to-many) |
| `symptoms` | Gejala / pola yang diamati |
| `root_cause` | Akar penyebab (diisi saat investigasi selesai) |
| `fix_type` | Jenis perbaikan: workaround / permanen |
| `proposed_fix` | Rencana solusi permanen |

### 3.2 Perbedaan Alur Status (Workflow)

**Incident Workflow:**
```
baru → diproses → menunggu_verifikasi → selesai
              ↘ ditolak / dibatalkan
```

**Problem Workflow (lebih panjang karena melibatkan investigasi):**
```
baru → investigasi → dianalisis → [workaround_diterapkan] → perbaikan_permanen → selesai
              ↘ ditolak / dibatalkan
```

Status tambahan untuk Problem:
| Status | Arti |
|--------|------|
| `investigasi` | Sedang dalam proses identifikasi akar masalah |
| `dianalisis` | Root cause sudah ditemukan, sedang menyusun solusi |
| `workaround_diterapkan` | Solusi sementara sudah diterapkan, masalah belum tuntas |
| `perbaikan_permanen` | Solusi permanen sedang diimplementasikan |

---

## 4. Opsi Implementasi Teknis

### Opsi A — Melalui Kategori (Preferensi Awal)

Buat dua kategori besar: *"Incident"* dan *"Problem"*, lalu sub-kategori di bawahnya.

**Kelebihan:**
- Tidak perlu migrasi skema tabel besar
- Memanfaatkan sistem kategori yang sudah ada

**Kekurangan:**
- ❌ Tidak bisa memiliki status yang berbeda per tipe (status di `tickets` adalah satu kolom enum global)
- ❌ Form dinamis berdasarkan kategori sangat kompleks dan rapuh
- ❌ Field tambahan (root_cause, workaround, dll.) tidak ada tempatnya di skema saat ini
- ❌ Laporan/filter per tipe menjadi tidak andal (harus hardcode nama kategori)

> **Kesimpulan:** Opsi ini cukup jika perbedaannya hanya tampilan/label, bukan
> alur & field. Untuk kebutuhan yang lebih dalam, tidak direkomendasikan.

---

### Opsi B — Kolom `ticket_type` di Tabel Tickets ✅ Direkomendasikan

Tambah satu kolom `ticket_type` enum (`incident`, `problem`, `service_request`) ke
tabel `tickets` yang sudah ada.

**Kelebihan:**
- ✅ Skema tetap ramping, hanya 1 kolom baru
- ✅ Status bisa difilter & divalidasi per tipe di level aplikasi (tidak perlu tabel baru)
- ✅ Form frontend bisa menyesuaikan field secara dinamis berdasarkan `ticket_type`
- ✅ Laporan, filter, dan statistik per tipe sangat mudah (`WHERE ticket_type = 'incident'`)
- ✅ Relasi incident-problem bisa ditangani dengan kolom `parent_ticket_id` nullable

**Kekurangan:**
- Perlu migrasi untuk menambah kolom (ringan, non-breaking — nilai default `incident`)
- Field tambahan (root_cause, workaround, dll.) perlu ditampung — pilih antara:
  - Kolom tambahan di tabel `tickets` (simpel, tapi tabel makin gemuk)
  - Tabel `ticket_meta` key-value (fleksibel, tapi query lebih kompleks)

---

### Opsi C — Tabel Terpisah (Overkill untuk Skala Ini)

Buat tabel `incidents` dan `problems` terpisah.

> **Tidak direkomendasikan** untuk skala sistem ini. Kompleksitas tinggi, duplikasi
> logika bisnis, dan overhead JOIN yang tidak perlu.

---

## 5. Rekomendasi: Opsi B dengan Pendekatan Bertahap

### Fase 1 — Minimum Viable (tanpa breaking change)

1. Tambah kolom `ticket_type` enum `('incident','problem','service_request')` default `'incident'`
2. Tampilkan badge tipe di halaman detail tiket dan daftar tiket
3. Tambah filter tipe di halaman admin
4. Form internal: tambah dropdown "Tipe Tiket" saat buat tiket baru

**Estimasi dampak kode:**
- 1 migrasi baru
- Update `Ticket` model (fillable, casts, helper method `typeLabel()`)
- Update form create tiket internal
- Update tampilan list & detail tiket

### Fase 2 — Extended Fields

1. Tambah kolom `affected_service`, `impact_scope`, `incident_start_at`, `workaround_available`, `workaround_desc` (untuk incident)
2. Tambah tabel `ticket_related` untuk relasi incident ↔ problem (many-to-many)
3. Tambah kolom `root_cause`, `proposed_fix`, `fix_type` (untuk problem)
4. Validasi form dinamis: field wajib berbeda per tipe

### Fase 3 — Workflow Terpisah

1. Status khusus Problem (`investigasi`, `dianalisis`, `workaround_diterapkan`, `perbaikan_permanen`) ditambah ke enum status
2. Controller `KominfoController@updateStatus` diberi guard per tipe: status tertentu hanya valid untuk tipe tertentu
3. Dashboard menampilkan metrik terpisah: rata-rata resolusi incident vs problem

---

## 6. Titik Tengah yang Disarankan

Mengingat preferensi implementasi via kategori namun kebutuhan teknis yang lebih dalam,
**titik tengah yang realistis** adalah:

> **Gunakan Opsi B (kolom `ticket_type`), namun integrasikan dengan sistem kategori
> yang ada:**
> Setiap kategori dapat diberi atribut `default_type` (incident/problem), sehingga
> saat pengguna memilih kategori, tipe tiket otomatis ter-preselect — namun tetap
> bisa dioverride manual. Ini memberikan kemudahan UX sekaligus fleksibilitas teknis.

---

## 7. Pertanyaan Terbuka (Perlu Dikonfirmasi ke Stakeholder)

- [ ] Apakah **Problem** selalu lahir dari Incident, atau bisa dibuat mandiri?
- [ ] Siapa yang berwenang mengubah status ke `investigasi` / `dianalisis`? (hanya supervisor/admin?)
- [ ] Apakah SLA (target_date) untuk Incident dan Problem berbeda secara sistematis?
- [ ] Apakah perlu notifikasi otomatis (Telegram/email) yang berbeda per tipe tiket?
- [ ] Apakah laporan/rekap statistik perlu memisahkan Incident vs Problem?

---

## 8. Dampak ke File yang Sudah Ada

| File | Dampak |
|------|--------|
| `database/migrations/` | Migrasi baru: tambah `ticket_type` ke tabel tickets |
| `app/Models/Ticket.php` | Tambah `ticket_type` ke fillable, tambah `typeLabel()`, `typeBadgeClass()` |
| `app/Http/Controllers/KominfoController.php` | Filter & validasi status per tipe |
| `resources/views/kominfo/tickets/` | Tampilan badge tipe, form create/edit, filter |
| `resources/views/public/submit-ticket.blade.php` | Tidak terdampak (scope: internal only) |
| `app/Models/Category.php` | Opsional: tambah `default_type` field di kategori |

---

*Dokumen ini bersifat analisis awal. Implementasi dimulai setelah konfirmasi dari stakeholder.*
