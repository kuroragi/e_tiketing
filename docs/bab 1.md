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