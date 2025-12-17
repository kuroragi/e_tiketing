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
