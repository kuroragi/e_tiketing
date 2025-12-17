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

