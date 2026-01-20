# BAB I

# PENDAHULUAN

## 1.1 Latar Belakang

Perkembangan teknologi informasi di lingkungan pemerintahan menuntut sistem kerja yang terstruktur, transparan, dan terdokumentasi. Dinas Komunikasi dan Informatika (Kominfo) Kota Bukittinggi kerap menerima permintaan bantuan dari berbagai Satuan Kerja Perangkat Daerah (SKPD) terkait infrastruktur TIK, aplikasi, jaringan, website, dan dukungan teknis.

Saat ini proses permintaan dan pelaporan masih bersifat konvensional (pesan singkat, komunikasi lisan, dokumen tidak terpusat), sehingga sulit melacak status pekerjaan, histori, dan beban kerja secara menyeluruh. Untuk mengatasi hal tersebut diperlukan Sistem Ticketing Pekerjaan sebagai media resmi pencatatan dan pelaporan, menampilkan status real-time, mendokumentasikan proses penanganan, dan menghasilkan laporan yang akurat.

Dengan sistem ini, koordinasi antar SKPD dan Kominfo menjadi lebih efektif, transparan, dan terukur, sekaligus mendukung peningkatan kualitas layanan pemerintahan berbasis digital.

## 1.2 Tujuan Proyek

- Membangun sistem terpusat untuk pengelolaan permintaan pekerjaan dari SKPD ke Kominfo.
- Memudahkan pemantauan status dan progres pekerjaan secara transparan dan real-time.
- Menyediakan laporan pekerjaan sebagai bahan evaluasi dan pengambilan keputusan.
- Meningkatkan akuntabilitas dan efisiensi proses layanan teknis Kominfo.

## 1.3 Ruang Lingkup

- Pengelolaan tiket: pembuatan, penugasan, prioritas, status (baru, diproses, selesai), dan penutupan.
- Manajemen pengguna: peran `Admin`, `Petugas Kominfo`, dan `SKPD` dengan hak akses terpisah.
- Notifikasi dan SLA sederhana: pengingat status, tenggat, dan eskalasi manual.
- Pelaporan: dashboard ringkas, rekap periodik, ekspor CSV/PDF.
- Integrasi dasar: email notifikasi; opsi integrasi WhatsApp/SMS pada fase lanjutan.

Di luar lingkup awal: otomasi penuh eskalasi lintas sistem, integrasi SSO pemda, dan mobile app native. Item-item ini dapat menjadi pengembangan berikutnya.

## 1.4 Target Pengguna

- SKPD pengusul tiket (pemohon layanan teknis).
- Petugas Kominfo (penangan tiket, teknisi, analis).
- Admin Kominfo (pengelola sistem, konfigurasi, pelaporan).
- Pimpinan Kominfo/Pemda (monitoring ringkas, laporan kinerja).

## 1.5 Sumber Daya yang Dibutuhkan

- Personel: 1 Project Manager, 1 System Analyst, 1 UI/UX Designer, 2 Backend Developer (Laravel), 1 Frontend Developer (Blade/Vite/Tailwind opsional), 1 QA/Tester, 1 DevOps (deployment & monitoring) paruh waktu, 1 Trainer.
- Infrastruktur: Server aplikasi (Linux/Windows), DB (MySQL/MariaDB), SMTP/email layanan, storage untuk lampiran tiket, domain/subdomain internal.
- Perangkat lunak: Laravel, PHP 8.x, Composer, Node.js + Vite, Git; alat QA (PHPUnit), monitoring (opsional), PDF generator.
- Dokumentasi & pelatihan: panduan pengguna, SOP tiket, modul pelatihan.

## 1.6 Estimasi Waktu Pengerjaan

Total durasi: 13–15 minggu.

Rincian:
- Analisis kebutuhan: 2 minggu
- Perancangan proses bisnis & alur: 2 minggu
- Desain aplikasi (UI/UX & arsitektur): 2 minggu
- Pengembangan & implementasi: 4–6 minggu
- Pengujian & penyempurnaan: 2 minggu
- Implementasi awal & pelatihan pengguna: 1 minggu

## 1.7 Timeline Pengerjaan

Minggu 1–2: Analisis kebutuhan (stakeholder interview, user stories, prioritas fitur)

Minggu 3–4: Perancangan proses bisnis & alur sistem (workflow tiket, peran & hak akses, SLA)

Minggu 5–6: Desain aplikasi (wireframe, prototipe, arsitektur, skema DB)

Minggu 7–10/12: Pengembangan & implementasi modul inti (tiket, pengguna, notifikasi, pelaporan dasar)

Minggu 11–12/14: Pengujian fungsional, UAT, perbaikan, hardening

Minggu 13–15: Implementasi awal, pelatihan, dokumentasi, go-live terbatas

## 1.8 Detail Phase per Timeline

1. Analisis kebutuhan
	- Aktivitas: wawancara, observasi proses, inventarisasi kebutuhan fungsional/non-fungsional.
	- Output: daftar kebutuhan, user stories, prioritas MVP.

2. Perancangan proses bisnis & alur
	- Aktivitas: pemetaan workflow tiket end-to-end, definisi peran & hak akses, aturan status & eskalasi.
	- Output: BPMN sederhana, SOP tiket, matriks RACI per aktivitas.

3. Desain aplikasi
	- Aktivitas: wireframe & prototipe tampilan, desain informasi, skema database, arsitektur modul.
	- Output: prototipe UI, ERD, diagram modul & integrasi.

4. Pengembangan & implementasi
	- Aktivitas: setup proyek, coding modul inti, integrasi email, pembuatan laporan dasar, keamanan dasar.
	- Output: aplikasi MVP siap uji, seed data, skrip deploy.

5. Pengujian & penyempurnaan
	- Aktivitas: test case, functional & regression test, UAT, perbaikan bug, optimasi kinerja.
	- Output: build stabil, laporan uji, daftar perbaikan.

6. Implementasi awal & pelatihan
	- Aktivitas: deployment terbatas, migrasi data awal (opsional), pelatihan pengguna, dokumentasi.
	- Output: sistem aktif untuk pilot, panduan pengguna & admin.

## 1.9 Milestone Timeline

- M1 (Akhir Minggu 2): Dokumen kebutuhan & prioritas MVP disetujui.
- M2 (Akhir Minggu 4): SOP & workflow tiket final.
- M3 (Akhir Minggu 6): Desain UI & arsitektur disetujui; ERD final.
- M4 (Minggu 10/12): Modul tiket, pengguna, notifikasi, pelaporan dasar selesai (MVP).
- M5 (Minggu 12/14): Laporan uji & perbaikan kritis ditutup.
- M6 (Minggu 13–15): Go-live terbatas & pelatihan selesai; rencana roll-out.

## 1.10 Resource Allocation Chart (Perkiraan)

- Project Manager: 30% sepanjang proyek.
- System Analyst: 100% Minggu 1–4, 50% Minggu 5–6, 25% Minggu 7–15.
- UI/UX Designer: 50% Minggu 3–6, 20% Minggu 7–10 (dukungan desain lanjutan).
- Backend Developer (2 orang): 80–100% Minggu 7–12, 50% Minggu 13–15.
- Frontend Developer: 70–90% Minggu 7–12, 40% Minggu 13–15.
- QA/Tester: 20% Minggu 7–10, 80–100% Minggu 11–14, 30% Minggu 15.
- DevOps (paruh waktu): 20% Minggu 5–6, 30% Minggu 11–15.
- Trainer: 50–70% Minggu 13–15.

## 1.11 Dependency Risk

- Ketergantungan pada ketersediaan data & proses existing SKPD untuk validasi alur.
- Ketergantungan pada infrastruktur email/SMTP untuk notifikasi.
- Ketergantungan persetujuan desain & SOP dari pimpinan untuk memulai pengembangan.
- Risiko perubahan kebijakan internal yang mempengaruhi workflow.

Mitigasi:
- Sediakan mock data & simulasi proses bila data belum siap.
- Siapkan fallback notifikasi (log aktivitas dalam aplikasi, dashboard pengingat) jika email terganggu.
- Tetapkan komite kecil untuk persetujuan cepat; gunakan timebox.
- Dokumentasikan perubahan kebijakan dan lakukan penyesuaian terkontrol (change management).

## 1.12 Budget Allocation Timeline (Persentase Per Fase)

- Analisis kebutuhan: 10%
- Perancangan proses & alur: 10%
- Desain aplikasi: 15%
- Pengembangan & implementasi: 40%
- Pengujian & penyempurnaan: 15%
- Implementasi awal & pelatihan: 10%

Catatan: Persentase dapat disesuaikan mengikuti kebijakan penganggaran dan kondisi riil.

## 1.13 Risiko & Mitigasi

- Keterlambatan persetujuan stakeholder: lakukan review berkala mingguan, gunakan notulen & keputusan tertulis.
- Ketidaksesuaian kebutuhan saat UAT: awali dengan MVP jelas, backlog perbaikan terprioritas, lakukan sprint pendek.
- Keterbatasan infrastruktur server: mulai dengan spesifikasi minimum, lakukan load test sederhana, rencana scaling bertahap.
- Keamanan data & akses: terapkan role-based access, audit log, enkripsi transport (HTTPS), backup berkala.
- Ketergantungan personel kunci: dokumentasi menyeluruh, pair programming, knowledge transfer.

## 1.14 Sistematika Penulisan

- Bab I Pendahuluan: latar belakang, tujuan, ruang lingkup, target pengguna, sumber daya, estimasi waktu, timeline, phase detail, milestone, resource allocation, dependency risk, budget, risiko & mitigasi.
- Bab II Analisis Kebutuhan Sistem: kebutuhan fungsional & non-fungsional.
- Bab III Proses Bisnis Sistem: alur kerja & interaksi antar pihak.
- Bab IV Desain Aplikasi: tampilan & struktur sistem.

---

# BAB II

# ANALISIS KEBUTUHAN SISTEM

## 2.1 Pendahuluan

Sistem Ticketing Layanan Kominfo Kota Bukittinggi adalah aplikasi web internal untuk mencatat, mengelola, dan melaporkan permintaan bantuan teknis dari SKPD. Tujuannya adalah menyediakan proses yang terstandar, transparan, dan terukur dari pengajuan hingga penyelesaian pekerjaan, sekaligus menyajikan data beban kerja bagi pimpinan.

Ruang lingkup pada tahap awal (MVP) mencakup pengelolaan tiket end-to-end, peran pengguna (`SKPD`, `Petugas Kominfo`, `Admin`, `Pimpinan`), notifikasi email dasar, serta pelaporan ringkas. Integrasi lanjutan (SSO, WA/SMS, otomasi eskalasi penuh) direncanakan di fase berikutnya.

<!-- Catatan Duplikasi: Ruang lingkup & peran pengguna telah dibahas pada Bab I 1.3 dan 1.4; notifikasi email & pelaporan ringkas juga disebut di Bab I 1.3. -->

## 2.2 Kebutuhan Fungsional (Detail)

- Autentikasi & Otorisasi
	- Login berbasis akun internal; reset kata sandi via email.
	- Role-based access control: `SKPD`, `Petugas`, `Admin`, `Pimpinan`.
	- Manajemen sesi dan logout aman.

- Manajemen Pengguna & SKPD (Admin)
	- CRUD pengguna: buat, ubah, nonaktifkan, reset kata sandi.
	- Penetapan peran dan asosiasi ke SKPD (untuk pengguna SKPD).
	- CRUD data master SKPD dan struktur unit kerja.

- Manajemen Data Master (Admin)
	- CRUD jenis pekerjaan/kategori, prioritas, dan label/tag.
	- Pengaturan status tiket yang diizinkan dan transisinya (workflow sederhana).
	- Pengaturan SLA dasar per kategori/prioritas (opsional di MVP).

- Tiket Pekerjaan (SKPD, Petugas)
	- Pembuatan tiket oleh SKPD dengan field minimal:
		- Judul, deskripsi rinci, kategori/jenis pekerjaan, prioritas, SKPD pemohon (auto), lampiran (opsional), kontak/pic.
	- Penomoran tiket otomatis dan unik (format: tahun-bulan-sekuens).
	- Validasi isian wajib dan ukuran/jenis berkas lampiran.
	- Penugasan tiket ke petugas/tipe tim oleh Petugas/Admin.
	- Perubahan status: `Baru` → `Diproses` → `Selesai` (dengan opsi `Ditolak/Dibatalkan`).
	- Pencatatan progres: komentar, catatan teknis, dan riwayat aktivitas.
	- Penutupan tiket dengan ringkasan hasil/solusi dan waktu selesai.

- Notifikasi
	- Email notifikasi saat: tiket dibuat, diassign, status berubah, dan ditutup.
	- Preferensi notifikasi per pengguna (opsional di MVP).

- Pencarian, Filter, dan Sortir
	- Pencarian teks pada judul/nomor tiket.
	- Filter berdasarkan status, kategori, prioritas, tanggal, SKPD, petugas.
	- Sortir berdasarkan tanggal dibuat, prioritas, tenggat, status.

- Dashboard & Pelaporan
	- Dashboard ringkas per peran: total tiket, status, tren periode.
	- Laporan berdasarkan periode, SKPD, kategori, status.
	- Ekspor laporan ke CSV/PDF.

- Audit & Keamanan Data
	- Audit log: siapa melakukan apa dan kapan pada tiket dan master data.
	- Pembatasan akses data sesuai peran (SKPD hanya melihat tiketnya, Petugas/Admin melihat semua).

- Admin Sistem
	- Pengaturan umum: email/SMTP, logo/nama instansi, preferensi tampilan.
	- Backup/restore basis data (prosedural, dapat berupa SOP di luar aplikasi).

Catatan workflow tiket (state & aturan transisi):
- State utama: `Baru`, `Diproses`, `Selesai`; state sampingan: `Ditolak`, `Dibatalkan`.
- Aturan contoh: hanya Petugas/Admin yang dapat mengubah `Baru` → `Diproses`; tiket `Selesai` hanya dapat dibuka ulang oleh Admin (opsional) dengan alasan.

<!-- Catatan Duplikasi: Alur status & pelaporan CSV/PDF konsisten dengan Bab I 1.3 dan diulang pada Bab III 3.3 serta Bab IV 4.9. -->

## 2.3 Kebutuhan Non-Fungsional (Detail)

- Keamanan
	- HTTPS wajib; penyimpanan kata sandi dengan hashing yang kuat (bcrypt/argon2).
	- Validasi input dan proteksi CSRF/XSS/SQL Injection.
	- Kontrol akses berbasis peran pada backend dan UI.

- Kinerja
	- Waktu muat halaman daftar tiket ≤ 3 detik pada 2000 tiket terindeks.
	- Pencarian/filter merespon ≤ 2 detik untuk 10.000 entri dengan indeks yang tepat.

- Ketersediaan & Reliabilitas
	- Target ketersediaan jam kerja; pemulihan insiden ≤ 4 jam kerja.
	- Backup harian dan retensi minimal 30 hari.

- Skalabilitas
	- Mampu menampung pertumbuhan tiket dan pengguna 3–5× tanpa perubahan arsitektur mayor.

- Kemudahan Penggunaan & Aksesibilitas
	- Navigasi konsisten, form dengan validasi jelas, pesan error informatif.
	- Kontras warna dan ukuran font mengikuti pedoman aksesibilitas dasar.

- Maintainability & Observability
	- Struktur kode modular (Laravel), logging terstandar, konfigurasi via env.
	- Dokumentasi admin & SOP operasional tersedia.

- Kompatibilitas
	- Browser modern (Chrome, Edge, Firefox) versi 2 tahun terakhir.
	- Integrasi SMTP standar; DB MySQL/MariaDB.

## 2.4 Use Case Umum Sistem (Detail)

UC-01 Login
- Aktor: Semua pengguna
- Tujuan: Mengakses sistem sesuai peran
- Prasyarat: Akun aktif
- Alur Utama: Buka halaman login → masukkan kredensial → autentikasi → masuk dashboard peran
- Alternatif: Lupa kata sandi → permintaan reset via email
- Pascakondisi: Sesi aktif tercipta

UC-02 Pengajuan Tiket (SKPD)
- Aktor: Pengguna SKPD
- Tujuan: Membuat tiket baru
- Prasyarat: Login, SKPD terasosiasi
- Alur Utama: Buka formulir → isi judul, deskripsi, kategori, prioritas, lampiran → kirim → tiket tercatat (status Baru) → notifikasi terkirim
- Alternatif: Validasi gagal → tampilkan pesan; unggahan melampaui batas → tolak unggahan
- Pascakondisi: Tiket baru dengan nomor unik

UC-03 Penugasan Tiket
- Aktor: Petugas/Admin
- Tujuan: Menetapkan penanggung jawab
- Prasyarat: Tiket status Baru
- Alur Utama: Buka detail tiket → pilih petugas → simpan → notifikasi ke petugas
- Pascakondisi: Field penanggung jawab terisi

UC-04 Proses Tiket
- Aktor: Petugas
- Tujuan: Memulai/menandai pengerjaan
- Prasyarat: Tiket ter-assign
- Alur Utama: Ubah status ke Diproses → tambahkan catatan/progres → unggah bukti jika perlu
- Pascakondisi: Riwayat progres tercatat

UC-05 Selesaikan Tiket
- Aktor: Petugas
- Tujuan: Menutup tiket
- Prasyarat: Pekerjaan selesai
- Alur Utama: Isi ringkasan hasil → ubah status ke Selesai → notifikasi ke pemohon
- Alternatif: Batalkan/ Tolak dengan alasan → status Dibatalkan/Ditolak
- Pascakondisi: Tiket tertutup, waktu selesai tercatat

UC-06 Lihat & Pantau Tiket
- Aktor: SKPD, Petugas, Pimpinan
- Tujuan: Melihat daftar dan detail tiket
- Prasyarat: Login
- Alur Utama: Buka daftar → gunakan filter/sortir → buka detail → lihat riwayat
- Pascakondisi: Tidak ada perubahan data

UC-07 Komentar & Lampiran
- Aktor: SKPD, Petugas
- Tujuan: Komunikasi dan bukti kerja
- Prasyarat: Akses ke tiket
- Alur Utama: Tambah komentar/unggah lampiran → tersimpan di timeline
- Pascakondisi: Aktivitas tercatat di audit log

UC-08 Laporan & Ekspor
- Aktor: Pimpinan/Admin
- Tujuan: Mendapatkan rekap beban kerja
- Prasyarat: Data tersedia
- Alur Utama: Pilih periode/SKPD/kategori → lihat ringkasan → ekspor CSV/PDF
- Pascakondisi: Berkas laporan tersedia untuk unduh

UC-09 Kelola Pengguna & Master Data
- Aktor: Admin
- Tujuan: Menjaga data dan konfigurasi
- Alur Utama: CRUD pengguna, SKPD, kategori, prioritas; atur SMTP dan preferensi
- Pascakondisi: Konfigurasi tersimpan, berpengaruh ke sistem

UC-10 Reset Kata Sandi
- Aktor: Semua pengguna
- Tujuan: Memulihkan akses
- Alur Utama: Minta reset → email tautan reset → set kata sandi baru
- Pascakondisi: Kredensial baru aktif

### 2.4.1 Deskripsi Gambar Use Case per UC

UC-01 Login — Deskripsi Diagram
- Aktor: `Pengguna` (mewakili semua peran)
- Boundary: Sistem Ticketing
- Use case: Login; relasi asosiasi dari aktor ke use case
- Relasi tambahan: include opsional ke `Reset Kata Sandi`

```plantuml
@startuml
left to right direction
actor "Pengguna" as User
rectangle "Sistem Ticketing" {
	usecase "Login" as UC_Login
	usecase "Reset Kata Sandi" as UC_Reset
}
User --> UC_Login
User --> UC_Reset
UC_Login .> UC_Reset : <<include>> (opsional)
@enduml
```

UC-02 Pengajuan Tiket — Deskripsi Diagram
- Aktor: `Pengguna SKPD`
- Boundary: Sistem Ticketing
- Use case: Pengajuan Tiket (membuat tiket baru)
- Relasi: asosiasi aktor SKPD ke use case

```plantuml
@startuml
left to right direction
actor "Pengguna SKPD" as SKPD
rectangle "Sistem Ticketing" {
	usecase "Pengajuan Tiket" as UC_Pengajuan
}
SKPD --> UC_Pengajuan
@enduml
```

UC-03 Penugasan Tiket — Deskripsi Diagram
- Aktor: `Petugas`, `Admin`
- Boundary: Sistem Ticketing
- Use case: Penugasan Tiket (menetapkan penanggung jawab)
- Relasi: asosiasi Petugas/Admin ke use case

```plantuml
@startuml
left to right direction
actor "Petugas" as Petugas
actor "Admin" as Admin
rectangle "Sistem Ticketing" {
	usecase "Penugasan Tiket" as UC_Assign
}
Petugas --> UC_Assign
Admin --> UC_Assign
@enduml
```

UC-04 Proses Tiket — Deskripsi Diagram
- Aktor: `Petugas`
- Boundary: Sistem Ticketing
- Use case: Proses Tiket (ubah status ke Diproses, tambah progres)
- Relasi: asosiasi Petugas ke use case

```plantuml
@startuml
left to right direction
actor "Petugas" as Petugas
rectangle "Sistem Ticketing" {
	usecase "Proses Tiket" as UC_Proses
}
Petugas --> UC_Proses
@enduml
```

UC-05 Selesaikan Tiket — Deskripsi Diagram
- Aktor: `Petugas`
- Boundary: Sistem Ticketing
- Use case: Selesaikan Tiket (tutup tiket, ringkas hasil)
- Relasi: asosiasi Petugas ke use case
- Catatan: extend ke `Batalkan/Ditolak` bila diperlukan

```plantuml
@startuml
left to right direction
actor "Petugas" as Petugas
rectangle "Sistem Ticketing" {
	usecase "Selesaikan Tiket" as UC_Close
	usecase "Batalkan/Ditolak" as UC_Cancel
}
Petugas --> UC_Close
UC_Cancel ..> UC_Close : <<extend>> (opsional)
@enduml
```

UC-06 Lihat & Pantau Tiket — Deskripsi Diagram
- Aktor: `SKPD`, `Petugas`, `Pimpinan`
- Boundary: Sistem Ticketing
- Use case: Lihat & Pantau Tiket (daftar/detail, filter/sortir)
- Relasi: asosiasi masing-masing aktor ke use case

```plantuml
@startuml
left to right direction
actor "Pengguna SKPD" as SKPD
actor "Petugas" as Petugas
actor "Pimpinan" as Pimpinan
rectangle "Sistem Ticketing" {
	usecase "Lihat & Pantau Tiket" as UC_View
}
SKPD --> UC_View
Petugas --> UC_View
Pimpinan --> UC_View
@enduml
```

UC-07 Komentar & Lampiran — Deskripsi Diagram
- Aktor: `SKPD`, `Petugas`
- Boundary: Sistem Ticketing
- Use case: Komentar & Lampiran (komunikasi pada tiket)
- Relasi: asosiasi aktor ke use case

```plantuml
@startuml
left to right direction
actor "Pengguna SKPD" as SKPD
actor "Petugas" as Petugas
rectangle "Sistem Ticketing" {
	usecase "Komentar & Lampiran" as UC_Comment
}
SKPD --> UC_Comment
Petugas --> UC_Comment
@enduml
```

UC-08 Laporan & Ekspor — Deskripsi Diagram
- Aktor: `Pimpinan`, `Admin`
- Boundary: Sistem Ticketing
- Use case: Laporan & Ekspor (lihat rekap, ekspor CSV/PDF)
- Relasi: asosiasi aktor ke use case

```plantuml
@startuml
left to right direction
actor "Pimpinan" as Pimpinan
actor "Admin" as Admin
rectangle "Sistem Ticketing" {
	usecase "Laporan & Ekspor" as UC_Report
}
Pimpinan --> UC_Report
Admin --> UC_Report
@enduml
```

UC-09 Kelola Pengguna & Master Data — Deskripsi Diagram
- Aktor: `Admin`
- Boundary: Sistem Ticketing
- Use case: Kelola Pengguna & Master Data
- Relasi: asosiasi Admin ke use case

```plantuml
@startuml
left to right direction
actor "Admin" as Admin
rectangle "Sistem Ticketing" {
	usecase "Kelola Pengguna & Master Data" as UC_Admin
}
Admin --> UC_Admin
@enduml
```

UC-10 Reset Kata Sandi — Deskripsi Diagram
- Aktor: `Pengguna`
- Boundary: Sistem Ticketing
- Use case: Reset Kata Sandi
- Relasi: asosiasi Pengguna ke use case
- Catatan: dapat di-include oleh use case Login

```plantuml
@startuml
left to right direction
actor "Pengguna" as User
rectangle "Sistem Ticketing" {
	usecase "Reset Kata Sandi" as UC_Reset
	usecase "Login" as UC_Login
}
User --> UC_Reset
User --> UC_Login
UC_Login .> UC_Reset : <<include>>
@enduml
```

## 2.5 Spesifikasi UI (Detail)

- Prinsip Umum
	- Desain sederhana, konsisten, dan mobile-friendly (responsive dasar).
	- Navigasi utama: menu sisi/kepala dengan item: Dashboard, Tiket, Pengajuan, Laporan, Manajemen (khusus Admin).

- Halaman Login
	- Field: Email/Username, Kata Sandi; tombol Masuk; tautan Lupa Kata Sandi.
	- Validasi: wajib isi, format email benar, pesan kesalahan jelas.

- Dashboard
	- Komponen: ringkasan angka (tiket baru/diproses/selesai), grafik tren bulanan, daftar tugas saya (untuk Petugas), notifikasi terbaru.
	- Filter cepat: periode (minggu/bulan), SKPD (untuk Admin/Pimpinan).

- Pengajuan Tiket (Form)
	- Field wajib: Judul, Deskripsi, Kategori/Jenis, Prioritas, Kontak/PIC.
	- Field opsional: Lampiran (pdf, jpg, png; ≤ 5–10 MB per berkas), Tanggal target (opsional).
	- Kontrol: tombol Simpan/Kirim, Batalkan, indikator progres unggah.
	- Validasi: batas panjang judul (≤ 150), deskripsi minimum (≥ 20 karakter).

- Daftar Tiket
	- Kolom: Nomor, Judul, SKPD, Kategori, Prioritas (warna label), Status (badge warna), Petugas, Tgl Dibuat, Tgl Update.
	- Fitur: pencarian, filter (status/kategori/prioritas/SKPD/petugas/periode), sortir, pagination, aksi baris (lihat, ubah status), aksi massal (opsional).
	- Empty state: pesan informatif dan ajakan membuat tiket.

- Detail Tiket
	- Header: nomor tiket, status (badge), prioritas, petugas penanggung jawab, tombol aksi (Ubah Status, Assign, Selesai/Batalkan).
	- Tab/Bagian: Informasi Utama (judul, deskripsi, kategori, SKPD), Timeline Aktivitas (komentar, perubahan status), Lampiran, Riwayat.
	- Komentar: editor teks sederhana, unggah lampiran; tampil secara kronologis.

- Manajemen Data (Admin)
	- Pengguna: tabel (Nama, Email, Peran, SKPD, Status), form tambah/ubah, reset kata sandi.
	- SKPD: tabel dan form CRUD.
	- Jenis Pekerjaan/Prioritas: tabel dan form CRUD; warna label prioritas.
	- Pengaturan: SMTP, identitas instansi, logo, batas unggahan.

- Laporan
	- Filter: periode tanggal, SKPD, kategori, status.
	- Tampilan: ringkasan angka, diagram batang/garis sederhana, tabel rekap.
	- Aksi: ekspor CSV/PDF, cetak.

- Notifikasi
	- Email template dasar untuk: tiket dibuat, diassign, status berubah, tiket selesai.
	- Placeholder dinamis: nomor tiket, judul, status, nama petugas, tautan detail.

- Pola UI & Aksesibilitas
	- Warna status: Baru (abu/baru), Diproses (biru), Selesai (hijau), Ditolak/Dibatalkan (merah/kuning).
	- Focus state jelas, ukuran target klik memadai, teks alternatif untuk ikon.
	- Pesan sukses/gagal konsisten, loading spinner pada aksi berat, konfirmasi untuk tindakan destruktif.

Catatan: Desain visual detail (komponen, spacing, warna) akan diturunkan dalam panduan desain/Style Guide dan prototipe UI (wireframe → high-fidelity) pada fase desain.

<!-- Catatan Duplikasi: Spesifikasi UI/antarmuka selaras dengan Bab IV 4.4; detail komponen dan layar di Bab IV mengulang konsep yang sama. -->

---

# BAB III

# PROSES BISNIS SISTEM

## 3.1 Gambaran Umum Proses Bisnis

Proses bisnis dalam Sistem Ticketing Layanan Kominfo Kota Bukittinggi dirancang untuk menggambarkan alur kerja permintaan bantuan pekerjaan dari SKPD hingga menjadi laporan yang dapat dipantau oleh pimpinan. Sistem ini menempatkan Kominfo sebagai pelaksana utama layanan TIK, sementara SKPD berperan sebagai pemohon layanan.

Fokus utama proses bisnis ini adalah memastikan setiap permintaan pekerjaan tercatat secara resmi, dapat dipantau progresnya, serta menghasilkan data jumlah dan beban kerja yang terukur.

## 3.2 Aktor yang Terlibat dalam Proses Bisnis

Aktor yang terlibat dalam proses bisnis sistem ini terdiri dari:

1. **SKPD**: Mengajukan permintaan pekerjaan melalui sistem ticketing.
2. **Petugas Kominfo**: Menerima, memproses, dan menyelesaikan tiket pekerjaan.
3. **Administrator**: Mengelola data master dan pengguna sistem.
4. **Pimpinan**: Memantau laporan dan rekap beban kerja.

<!-- Catatan Duplikasi: Aktor/peran pengguna telah disebut di Bab I 1.4 dan dirinci lagi di Bab IV 4.2. -->

## 3.3 Alur Proses Bisnis Pengajuan dan Penanganan Tiket

Alur proses bisnis sistem ticketing secara umum adalah sebagai berikut:

1. SKPD melakukan pengajuan permintaan pekerjaan melalui sistem dengan mengisi informasi yang dibutuhkan.
2. Sistem mencatat tiket dengan status **Baru**.
3. Petugas Kominfo menerima tiket dan melakukan verifikasi awal.
4. Tiket yang valid diproses dan status berubah menjadi **Diproses**.
5. Petugas Kominfo melakukan pekerjaan sesuai permintaan.
6. Setelah pekerjaan selesai, petugas memperbarui status tiket menjadi **Selesai** dan mengisi keterangan hasil pekerjaan.
7. Data tiket yang telah selesai akan masuk ke dalam rekap laporan.
8. Pimpinan dapat melihat laporan jumlah pekerjaan dan beban kerja Kominfo.

<!-- Catatan Duplikasi: Alur status Baru→Diproses→Selesai telah dijelaskan di Bab I 1.3 dan Bab II 2.2/2.4. -->

## 3.4 Diagram Alur Proses Bisnis

Berikut adalah diagram alur proses bisnis Sistem Ticketing Layanan Kominfo Kota Bukittinggi secara naratif:

```
[SKPD]
   |
   | 1. Mengajukan Permintaan Pekerjaan
   v
[Sistem Ticketing]
   |
   | 2. Mencatat Tiket (Status: Baru)
   v
[Petugas Kominfo]
   |
   | 3. Verifikasi & Penanganan
   v
[Sistem Ticketing]
   |
   | 4. Update Status (Diproses)
   v
[Petugas Kominfo]
   |
   | 5. Menyelesaikan Pekerjaan
   v
[Sistem Ticketing]
   |
   | 6. Update Status (Selesai)
   v
[Laporan & Rekapitulasi]
   |
   | 7. Monitoring
   v
[Pimpinan]
```

Diagram tersebut menunjukkan bahwa sistem berperan sebagai pusat pencatatan dan pengendalian proses kerja, sehingga seluruh aktivitas dapat dimonitor secara terpusat.

## 3.5 Proses Monitoring dan Pelaporan

Proses monitoring dan pelaporan dilakukan berdasarkan data tiket yang tercatat di dalam sistem. Informasi yang disajikan meliputi:

1. Jumlah tiket berdasarkan SKPD.
2. Jumlah tiket berdasarkan status pekerjaan.
3. Rekap pekerjaan yang telah diselesaikan.
4. Gambaran beban kerja Kominfo dalam periode tertentu.

Laporan ini digunakan oleh pimpinan sebagai bahan evaluasi kinerja dan dasar pengambilan keputusan terkait peningkatan layanan.

<!-- Catatan Duplikasi: Pelaporan & monitoring telah dibahas di Bab I 1.3 dan Bab II 2.2/UC-08; Bab IV 4.9 juga menguraikan laporan. -->

## 3.6 Spesifikasi Aktor Sistem

Aktor-aktor berikut berinteraksi dalam proses bisnis beserta tanggung jawab, hak akses, dan aksi utamanya:

1. SKPD (Pemohon)
    - Deskripsi: Unit kerja pemda yang mengajukan permintaan bantuan pekerjaan TIK.
    - Tanggung jawab utama:
       - Mengisi dan mengirim tiket dengan informasi yang lengkap dan akurat.
       - Menyediakan lampiran pendukung bila diperlukan.
       - Merespons pertanyaan/progres dari petugas.
    - Hak akses data:
       - Melihat seluruh tiket yang diajukan oleh SKPD-nya.
       - Melihat status, riwayat aktivitas, dan hasil pekerjaan tiket miliknya.
    - Aksi utama di sistem:
       - Buat tiket, lihat/ikuti tiket, beri komentar, unggah lampiran, unduh laporan terkait tiket sendiri.
    - Indikator kinerja (informal): kelengkapan pengajuan, kecepatan respons saat klarifikasi.

2. Petugas Kominfo (Pelaksana)
    - Deskripsi: Personel Kominfo yang menangani dan menyelesaikan tiket.
    - Tanggung jawab utama:
       - Verifikasi awal, mengubah status, mengerjakan tugas, dan mencatat progres.
       - Berkomunikasi dengan pemohon untuk klarifikasi.
       - Menutup tiket dengan ringkasan hasil yang jelas.
    - Hak akses data:
       - Melihat semua tiket; mengubah tiket yang ditugaskan atau sesuai kewenangan.
       - Akses ke seluruh riwayat aktivitas dan lampiran tiket.
    - Aksi utama di sistem:
       - Tinjau/ambil tiket, assign (jika diberi kewenangan), ubah status, tambah progres/komentar, unggah bukti, tutup tiket.
    - Indikator kinerja: waktu tanggap awal, waktu penyelesaian, jumlah tiket selesai.

3. Administrator (Admin Sistem)
    - Deskripsi: Pengelola konfigurasi, data master, dan akun pengguna.
    - Tanggung jawab utama:
       - Mengelola pengguna, peran, SKPD, jenis pekerjaan, prioritas, dan pengaturan sistem (SMTP, identitas).
       - Menjaga integritas data dan membatasi akses sesuai kebijakan.
    - Hak akses data:
       - Akses penuh ke semua data master dan konfigurasi; akses baca ke seluruh tiket.
    - Aksi utama di sistem:
       - CRUD master data, kelola akun/peran, pengaturan sistem, bantuan operasional.
    - Indikator kinerja: konsistensi data master, ketersediaan sistem, kepatuhan konfigurasi.

4. Pimpinan (Pengambil Keputusan)
    - Deskripsi: Pejabat yang memonitor beban kerja dan kinerja layanan.
    - Tanggung jawab utama:
       - Meninjau rekapitulasi pekerjaan dan tren kinerja.
       - Memberi arahan untuk prioritas atau kebijakan layanan.
    - Hak akses data:
       - Akses baca ke laporan agregat, dashboard ringkasan, dan detail tiket bila diperlukan (read-only).
    - Aksi utama di sistem:
       - Melihat dashboard, menjalankan laporan, mengunduh rekap.
    - Indikator kinerja: pemanfaatan laporan untuk evaluasi, ketepatan arahan prioritas.

Catatan: Rincian kewenangan granular (mis. siapa yang boleh re-assign, reopen, delete lampiran) ditetapkan di kebijakan peran dan SOP operasional.

<!-- Catatan Duplikasi: Spesifikasi peran & akses juga dijabarkan di Bab IV 4.2. -->

## 3.7 Berkas/Dokumen yang Terlibat

Dokumen/berkas yang terbentuk atau digunakan sepanjang siklus tiket:

- Formulir Pengajuan Tiket
   - Isi minimum: Judul, Deskripsi, Kategori/Jenis, Prioritas, SKPD, Kontak/PIC, Tanggal pengajuan, Lampiran (opsional).
   - Format: entri pada sistem (database); dapat dicetak sebagai PDF ringkas.

- Lampiran Pendukung
   - Jenis: gambar (jpg/png), dokumen (pdf/docx), arsip (zip) sesuai kebijakan.
   - Batas ukuran: 5–10 MB per berkas (dapat dikonfigurasi).
   - Penyimpanan: direktori storage dengan referensi di database; penamaan aman dan unik.

- Catatan Progres/Timeline Aktivitas
   - Isi: komentar, perubahan status, penugasan, waktu stempel, pelaku.
   - Fungsi: audit trail dan komunikasi sepanjang penanganan tiket.

- Ringkasan Hasil Pekerjaan (Berita Acara Sederhana)
   - Isi: ringkasan tindakan, hasil, tanggal selesai, penanggung jawab.
   - Bentuk: field pada tiket + opsi unduh PDF sebagai berita acara ringkas.

- Surat Tugas/Disposisi (opsional, sesuai SOP internal)
   - Isi: perintah kerja/penugasan formal.
   - Sumber: diunggah oleh admin/petugas bila diperlukan.

- Notifikasi Email
   - Template: tiket dibuat, diassign, status berubah, tiket selesai.
   - Variabel: nomor tiket, judul, status, petugas, tautan detail.

- Laporan Periodik
   - Jenis: rekap per periode, per SKPD, per kategori, per status.
   - Format: tampilan layar, ekspor CSV/PDF; dapat dicetak.

- Ekspor Data
   - Tujuan: analisis lanjutan (mis. spreadsheet) atau arsip.
   - Konten: subset kolom tiket, progres terpilih; terikat kebijakan privasi internal.

- SOP & Panduan Pengguna
   - Isi: langkah penggunaan sistem, peran & tanggung jawab, kebijakan status.
   - Bentuk: dokumen PDF/Markdown di repository dokumentasi internal.

Aspek tata kelola berkas/dokumen:
- Retensi: backup harian, retensi minimal 30 hari (dapat disesuaikan kebijakan pemda).
- Klasifikasi: informasi internal; lindungi data sensitif (kontak pribadi, lampiran tertentu).
- Penamaan & Versi: penamaan unik otomatis untuk lampiran; perubahan metadata tercatat di audit log.
- Akses: sesuai peran; SKPD hanya mengakses lampiran pada tiketnya; Petugas/Admin mengakses lampiran sesuai tugas.
- Kepatuhan: patuhi kebijakan keamanan informasi pemda (encrypt in-transit/HTTPS; kontrol akses).

---

# BAB IV

# DESAIN APLIKASI

## 4.1 Gambaran Umum Desain Aplikasi

Desain Sistem Ticketing Layanan Kominfo Kota Bukittinggi dirancang dengan pendekatan sederhana, fungsional, dan mudah digunakan oleh seluruh pengguna internal Pemerintah Kota Bukittinggi. Aplikasi ini berfokus pada kejelasan informasi, kemudahan input data, serta kemudahan monitoring beban kerja.

Aplikasi bersifat berbasis web dan dapat diakses sesuai dengan hak akses masing-masing pengguna.

## 4.2 Desain Peran dan Hak Akses Pengguna

Sistem ini memiliki beberapa peran pengguna dengan hak akses yang berbeda, yaitu:

1. **Pengguna SKPD**

   * Mengajukan tiket permintaan pekerjaan.
   * Melihat status dan riwayat tiket yang diajukan.

2. **Petugas Kominfo**

   * Melihat seluruh tiket yang masuk.
   * Memproses dan memperbarui status tiket.
   * Mengisi keterangan progres dan hasil pekerjaan.

3. **Administrator Sistem**

   * Mengelola data pengguna dan SKPD.
   * Mengelola data master (jenis pekerjaan, status, dan lainnya).

4. **Pimpinan**

   * Melihat dashboard ringkasan pekerjaan.
   * Mengakses laporan jumlah dan beban kerja.

### 4.2.1 Spesifikasi Detail Peran & Akses

- Admin Sistem
  - Akses: penuh ke konfigurasi, data master (SKPD, jenis pekerjaan, prioritas), manajemen pengguna/peran, audit log, laporan, seluruh tiket (read/write), pengaturan SMTP/identitas.
  - Aksi kunci: CRUD master data; tambah/nonaktifkan pengguna; reset kata sandi; re-assign tiket; re-open tiket (opsional); set batas unggahan; kelola template email.
  - Batasan: tindakan destruktif (hapus permanen) membutuhkan konfirmasi dua langkah.

- Petugas Kominfo
  - Akses: lihat semua tiket; ubah tiket yang ditugaskan; tambah progres, komentar, lampiran; ubah status (Baru→Diproses→Selesai); generate ringkasan hasil; lihat laporan ringkas.
  - Aksi kunci: ambil tiket (self-assign) jika diperbolehkan; serahkan ke petugas lain (opsional kebijakan); tandai butuh klarifikasi; unggah bukti.
  - Batasan: tidak dapat mengubah data master/kebijakan; tidak dapat menghapus lampiran pengguna tanpa alasan & log.

- Pengguna SKPD
  - Akses: buat tiket; lihat tiket milik SKPD-nya; komentar; tambah lampiran; lihat ringkasan hasil.
  - Batasan: tidak dapat mengubah atribut inti tiket setelah dibuat (judul/kategori/prioritas) selain menambah komentar/lampiran; tidak dapat melihat tiket SKPD lain.

- Pimpinan
  - Akses: dashboard dan laporan agregat; drill-down baca ke detail tiket (tanpa ubah); ekspor laporan.
  - Batasan: tidak dapat menambah/mengubah tiket atau master data.

Kontrol akses data dan transisi status dirangkum dalam kebijakan: hanya Petugas/Admin yang dapat memindahkan `Baru`→`Diproses`; penutupan tiket membutuhkan ringkasan hasil; pembukaan ulang tiket memerlukan alasan (role Admin).

<!-- Catatan Duplikasi: Peran & hak akses tumpang tindih dengan Bab III 3.6 dan Bab II 2.1/2.2. -->

## 4.3 Struktur Menu Aplikasi

Struktur menu aplikasi dirancang agar mudah dipahami dan disesuaikan dengan peran pengguna, antara lain:

* **Dashboard**
  Menampilkan ringkasan jumlah tiket, status pekerjaan, dan beban kerja.

* **Tiket Pekerjaan**
  Berisi daftar tiket masuk, tiket diproses, dan tiket selesai.

* **Pengajuan Tiket**
  Digunakan oleh SKPD untuk membuat permintaan pekerjaan baru.

* **Laporan**
  Menampilkan rekap pekerjaan berdasarkan periode, SKPD, dan status.

* **Manajemen Data**
  Digunakan administrator untuk mengelola data pendukung sistem.

### 4.3.1 Spesifikasi Navigasi & Rute

- Konvensi penamaan rute (Laravel):
  - `dashboard.index`, `tickets.index`, `tickets.create`, `tickets.show`, `tickets.updateStatus`,
    `reports.index`, `admin.users.index`, `admin.users.create`, `admin.departments.index`,
    `admin.categories.index`, `admin.settings.index`.
- Visibilitas menu per peran:
  - SKPD: Dashboard, Tiket (daftar & detail), Pengajuan, Laporan (terbatas untuk tiket sendiri).
  - Petugas: Dashboard, Tiket (semua), Laporan ringkas; Pengajuan opsional; tidak ada menu Admin.
  - Pimpinan: Dashboard, Laporan penuh; akses baca ke Tiket.
  - Admin: seluruh menu termasuk Manajemen Data & Pengaturan.
- Indikator UI:
  - Badge jumlah tiket baru pada menu Tiket (role Petugas/Admin).
  - Highlight menu aktif; breadcrumbs pada halaman detail.
- Kebijakan akses pada rute dilindungi middleware: `auth`, `role:admin|petugas|skpd|pimpinan`.

## 4.4 Gambaran Antarmuka Aplikasi

Antarmuka aplikasi dirancang dengan prinsip sederhana dan informatif, antara lain:

1. Halaman dashboard menampilkan grafik atau ringkasan angka jumlah tiket.
2. Halaman pengajuan tiket menggunakan formulir yang jelas dan ringkas.
3. Halaman daftar tiket menampilkan status pekerjaan secara visual.
4. Halaman laporan menyajikan data dalam bentuk tabel dan ringkasan.

Desain visual dibuat konsisten agar pengguna mudah beradaptasi.

### 4.4.1 Spesifikasi Layar

- Login
  - Field: email/username, kata sandi; aksi: masuk, lupa kata sandi.
  - Validasi sisi-klien & sisi-server; pesan kesalahan jelas.

- Dashboard
  - Widget KPI: total tiket periode berjalan, baru, diproses, selesai; tren bulanan; daftar tugas saya (Petugas); aktivitas terbaru.
  - Filter cepat: periode (minggu/bulan/khusus), SKPD (Admin/Pimpinan), kategori.

- Pengajuan Tiket
  - Field wajib: Judul (≤150), Deskripsi (≥20), Kategori, Prioritas, Kontak/PIC.
  - Field opsional: Lampiran (pdf/jpg/png ≤ 10 MB/berkas), tanggal target.
  - Aksi: Kirim (membuat tiket `Baru`), Simpan Draf (opsional), Batalkan.

- Daftar Tiket
  - Kolom: Nomor, Judul, SKPD, Kategori, Prioritas, Status, Petugas, Dibuat, Diperbarui.
  - Fitur: pencarian, filter (status/kategori/prioritas/SKPD/periode/petugas), sortir, pagination.
  - Aksi baris: Lihat, Ubah Status (role sesuai), Assign (Petugas/Admin).

- Detail Tiket
  - Bagian: Informasi Utama, Timeline Aktivitas, Lampiran, Riwayat Status.
  - Aksi: Ubah Status (Petugas/Admin), Assign (Petugas/Admin), Tambah Komentar/Lampiran (SKPD & Petugas), Tutup Tiket (Petugas/Admin).

- Manajemen Data (Admin)
  - Pengguna, SKPD, Kategori, Prioritas: tabel CRUD; pengaturan SMTP dan identitas.

- Laporan
  - Filter: periode, SKPD, kategori, status.
  - Tampil: ringkasan angka, grafik sederhana, tabel rekap; aksi ekspor CSV/PDF.

### 4.4.2 Pola Interaksi & Aksesibilitas

- Komponen: Blade + Tailwind (opsional), form dengan validasi inline, modals untuk aksi kritikal.
- Status UI: loading spinner pada aksi berat; empty state informatif; konfirmasi untuk tindakan destruktif.
- Aksesibilitas: kontras memadai, fokus indikator, teks alt pada ikon, navigasi keyboard.

<!-- Catatan Duplikasi: Spesifikasi UI di Bab II 2.5 memuat poin yang sama; bagian ini memberikan rincian layar. -->

## 4.5 Desain Arsitektur Sistem (Gambaran Umum)

Secara umum, arsitektur sistem dirancang sebagai berikut:

* Aplikasi berbasis web yang diakses melalui jaringan internal Pemko.
* Database terpusat untuk menyimpan data tiket dan laporan.
* Mekanisme autentikasi dan otorisasi berdasarkan peran pengguna.

Arsitektur ini dipilih untuk mendukung kemudahan pengelolaan, keamanan data, dan pengembangan sistem di masa depan.

### 4.5.1 Arsitektur Logis

- Presentasi: Blade/Vite, komponen UI, validasi klien, notifikasi toast.
- Aplikasi (Laravel): controller, service layer, policy/authorization, validation, job/queue (opsional untuk email).
- Data: MySQL/MariaDB; storage untuk lampiran; migrasi & seeding.
- Integrasi: SMTP untuk email notifikasi.

### 4.5.2 Topologi Deploy (Contoh)

- Server aplikasi: Linux (Nginx + PHP-FPM) atau Windows (IIS). PHP 8.x.
- Database: MySQL/MariaDB; akses jaringan internal.
- Penyimpanan lampiran: disk lokal `storage/app/public` atau network share; publikasi via symlink.
- Konfigurasi via `.env`: kredensial DB, SMTP, batas unggahan, nama instansi.

### 4.5.3 Keamanan, Logging, Observability

- HTTPS wajib; hardening header; rate limiting endpoint login.
- Logging: `storage/logs` terstruktur; audit log aktivitas pada tiket & master data.
- Backup: DB harian, retensi ≥30 hari; pemulihan insiden terprosedur.

## 4.6 Ringkasan Desain Aplikasi

Dengan desain aplikasi ini, diharapkan Sistem Ticketing Layanan Kominfo Kota Bukittinggi dapat menjadi sarana resmi dalam pencatatan dan pelaporan pekerjaan. Desain yang sederhana dan terstruktur memudahkan seluruh pihak dalam menggunakan sistem serta memberikan informasi yang dibutuhkan pimpinan secara cepat dan akurat.

### 4.6.1 Prinsip Desain & Keterkaitan NFR

- Sederhana & konsisten: navigasi dan komponen berulang untuk menurunkan kurva belajar (usability).
- Transparan & auditabel: setiap perubahan tercatat (auditability/security).
- Responsif & cepat: target render daftar ≤ 3 detik (performance).
- Aman secara default: RBAC, validasi input, HTTPS (security).

## 4.7 Desain Data Utama Aplikasi

Untuk mendukung proses bisnis, sistem ini menggunakan beberapa data utama, antara lain:

1. **Data SKPD**: nama SKPD, kontak, dan penanggung jawab.
2. **Data Pengguna**: akun pengguna beserta peran dan hak akses.
3. **Data Tiket Pekerjaan**: nomor tiket, SKPD pemohon, jenis pekerjaan, tanggal pengajuan, status, dan hasil pekerjaan.
4. **Data Jenis Pekerjaan**: kategori pekerjaan yang ditangani Kominfo.

Struktur data ini dirancang sederhana namun cukup untuk mendukung kebutuhan pelaporan dan analisis beban kerja.

### 4.7.1 Entitas & Atribut (Ringkas)

- users: id, name, email, password_hash, role, department_id (nullable), status, created_at, updated_at
- departments (SKPD): id, name, contact, head, created_at, updated_at
- categories: id, name, description, created_at, updated_at
- priorities: id, name, weight, color, created_at, updated_at
- tickets: id, number, title, description, requester_id, department_id, category_id, priority_id, assignee_id (nullable), status, target_date (nullable), closed_at (nullable), created_at, updated_at
- ticket_comments: id, ticket_id, user_id, body, created_at
- ticket_attachments: id, ticket_id, user_id, filename, path, size, mime, created_at
- audit_logs: id, entity_type, entity_id, action, user_id, meta(json), created_at

### 4.7.2 Relasi Utama

- users (1) — (n) tickets sebagai requester/assignee
- departments (1) — (n) users dan tickets
- tickets (1) — (n) ticket_comments dan ticket_attachments
- categories/priorities (1) — (n) tickets

## 4.8 Desain Dashboard Pimpinan

Dashboard pimpinan dirancang untuk memberikan gambaran cepat mengenai kondisi pekerjaan Kominfo, antara lain:

1. Total tiket yang masuk dalam periode tertentu.
2. Jumlah tiket berdasarkan status (baru, diproses, selesai).
3. Rekap jumlah pekerjaan per SKPD.
4. Gambaran beban kerja Kominfo dalam bentuk ringkasan angka.

Informasi disajikan dalam bentuk ringkasan visual agar mudah dipahami dan digunakan sebagai bahan evaluasi.

### 4.8.1 Spesifikasi Widget & KPI

- KPI inti: total tiket periode, baru, diproses, selesai; rata-rata waktu penyelesaian; backlog aktif; 5 SKPD dengan tiket terbanyak.
- Grafik: tren bulanan per status; distribusi kategori/prioritas.
- Filter global: rentang tanggal, SKPD, kategori; hak akses menentukan cakupan data.
- Drill-down: klik elemen grafik membuka daftar tiket terfilter.
- Kinerja: cache ringan untuk query ringkasan per periode.

## 4.9 Desain Laporan Sistem

Laporan dalam sistem ini dirancang untuk mendukung kebutuhan monitoring dan evaluasi, meliputi:

1. Laporan jumlah pekerjaan per periode.
2. Laporan pekerjaan berdasarkan SKPD.
3. Laporan status penyelesaian pekerjaan.

Laporan dapat ditampilkan di layar dan disiapkan untuk dicetak atau diunduh sesuai kebutuhan pimpinan.

### 4.9.1 Spesifikasi Laporan

- Laporan Periode
  - Kolom: nomor, judul, SKPD, kategori, prioritas, status, dibuat, selesai, durasi.
  - Filter: tanggal, SKPD, kategori, status.

- Laporan per SKPD
  - Kolom: SKPD, jumlah tiket (baru/diproses/selesai), durasi rata-rata, kategori teratas.

- Laporan Status Penyelesaian
  - Kolom: status, jumlah, persentase; tren perubahan status.

### 4.9.2 Ekspor & Format

- Ekspor: CSV (UTF-8, delimiter koma) dan PDF (A4, orientasi lanskap untuk tabel lebar).
- Tanda identitas: logo instansi, periode laporan, waktu cetak, penanggung jawab.

<!-- Catatan Duplikasi: Bagian laporan ini konsisten/berulang dengan Bab II 2.2/2.5 (ekspor CSV/PDF) dan Bab III 3.5; Bab I 1.3 juga menyebut pelaporan. -->

## 4.10 Arah Pengembangan Sistem

Sebagai pengembangan lanjutan, sistem ini dapat ditingkatkan dengan:

1. Penambahan indikator waktu penyelesaian pekerjaan.
2. Integrasi notifikasi internal.
3. Pengembangan analisis beban kerja yang lebih mendalam.

Pengembangan lanjutan ini dapat dilakukan secara bertahap sesuai kebutuhan dan kebijakan Pemerintah Kota Bukittinggi.

### 4.10.1 Roadmap Fitur (Usulan)

- P1 (pasca-MVP):
  - SLA dasar per kategori/prioritas (pengingat tenggat).
  - Reopen tiket terkontrol; self-assign untuk petugas.
  - Template respons cepat; cetak berita acara dari tiket.

- P2:
  - Integrasi SSO Pemda; integrasi WhatsApp/SMS gateway.
  - Notifikasi real-time (broadcast) dan queue untuk email.
  - API read-only untuk integrasi pelaporan eksternal.

- P3:
  - Analitik lanjutan (MTTR per kategori/SKPD, heatmap beban kerja).
  - Mobile-friendly yang ditingkatkan atau PWA.
  - Manajemen penjadwalan tugas & kalender tim.
