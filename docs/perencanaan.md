# BAB I

# PENDAHULUAN

## 1.1 Latar Belakang

Perkembangan teknologi informasi di lingkungan pemerintahan menuntut adanya sistem kerja yang terstruktur, transparan, dan terdokumentasi dengan baik. Dalam pelaksanaan tugas sehari-hari, Dinas Komunikasi dan Informatika (Kominfo) Kota Bukittinggi kerap menerima permintaan bantuan pekerjaan dari berbagai Satuan Kerja Perangkat Daerah (SKPD), baik yang berkaitan dengan pengelolaan infrastruktur TIK, aplikasi, jaringan, website, maupun dukungan teknis lainnya.

Saat ini, proses permintaan dan pelaporan pekerjaan tersebut masih dilakukan secara konvensional, seperti melalui pesan singkat, komunikasi lisan, atau dokumen tidak terpusat. Kondisi ini menimbulkan beberapa permasalahan, antara lain sulitnya melakukan pelacakan status pekerjaan, tidak tersedianya histori pekerjaan yang rapi, serta keterbatasan pimpinan dalam memantau kinerja dan beban kerja Kominfo terhadap SKPD lain secara menyeluruh.

Untuk mengatasi permasalahan tersebut, diperlukan sebuah Sistem Ticketing Pekerjaan yang berfungsi sebagai media resmi pencatatan permintaan bantuan dari SKPD kepada Kominfo. Sistem ini diharapkan mampu menampilkan status pekerjaan secara real-time, mendokumentasikan proses penanganan, serta menghasilkan laporan pekerjaan yang akurat dan mudah dipahami oleh pimpinan.

Dengan adanya sistem ticketing ini, proses koordinasi antar SKPD dengan Kominfo Kota Bukittinggi dapat berjalan lebih efektif, transparan, dan terukur, serta mendukung peningkatan kualitas pelayanan pemerintahan berbasis digital.

## 1.2 Identifikasi Masalah

Berdasarkan latar belakang yang telah diuraikan, dapat diidentifikasi beberapa permasalahan sebagai berikut:

1. Belum adanya sistem terpusat untuk mencatat dan mengelola permintaan pekerjaan dari SKPD ke Kominfo.
2. Sulitnya memantau status dan progres pekerjaan yang sedang atau telah dikerjakan.
3. Tidak tersedianya data historis pekerjaan yang dapat dijadikan bahan evaluasi dan pelaporan.
4. Pimpinan mengalami keterbatasan dalam memperoleh gambaran menyeluruh terkait beban kerja dan kinerja pelayanan Kominfo.

## 1.3 Rumusan Masalah

Berdasarkan identifikasi masalah tersebut, maka rumusan masalah dalam perancangan aplikasi ini adalah:

1. Bagaimana merancang sistem ticketing yang dapat menampung permintaan pekerjaan dari SKPD ke Kominfo Kota Bukittinggi?
2. Bagaimana sistem dapat menampilkan status dan progres pekerjaan secara jelas dan terstruktur?
3. Bagaimana sistem dapat menghasilkan laporan pekerjaan yang informatif bagi pimpinan?

## 1.4 Tujuan Penelitian dan Perancangan

Tujuan dari perancangan Sistem Ticketing Pekerjaan ini adalah:

1. Membangun sistem terpusat untuk pengelolaan permintaan pekerjaan dari SKPD ke Kominfo.
2. Memudahkan pemantauan status dan progres pekerjaan secara transparan.
3. Menyediakan laporan pekerjaan sebagai bahan evaluasi dan pengambilan keputusan bagi pimpinan.

## 1.5 Manfaat Penelitian dan Perancangan

Adapun manfaat yang diharapkan dari perancangan sistem ini adalah:

1. **Bagi Kominfo Kota Bukittinggi**: membantu pengelolaan pekerjaan agar lebih terorganisir dan terdokumentasi.
2. **Bagi SKPD**: memudahkan pengajuan permintaan bantuan dan mengetahui status penanganan pekerjaan.
3. **Bagi Pimpinan**: menyediakan data dan laporan yang akurat untuk monitoring dan evaluasi kinerja.

## 1.6 Ruang Lingkup

Untuk menjaga fokus perancangan, ruang lingkup sistem ini dibatasi pada:

1. Pengelolaan tiket permintaan pekerjaan dari SKPD ke Kominfo.
2. Pemantauan status pekerjaan (baru, diproses, selesai).
3. Penyajian laporan pekerjaan yang telah dilakukan Kominfo.

## 1.7 Estimasi Waktu Pengerjaan

Estimasi waktu pengerjaan Sistem Ticketing Layanan Kominfo Kota Bukittinggi dirancang agar realistis dan dapat disesuaikan dengan kondisi sumber daya yang tersedia. Perkiraan waktu pengerjaan dibagi ke dalam beberapa tahapan sebagai berikut:

1. **Analisis Kebutuhan Sistem**: 2 minggu
2. **Perancangan Proses Bisnis dan Alur Sistem**: 2 minggu
3. **Perancangan Desain Aplikasi (UI/UX & Arsitektur Sistem)**: 2 minggu
4. **Pengembangan dan Implementasi Sistem**: 4–6 minggu
5. **Pengujian dan Penyempurnaan Sistem**: 2 minggu
6. **Implementasi Awal dan Pelatihan Pengguna**: 1 minggu

Dengan demikian, total estimasi waktu pengerjaan sistem ini adalah sekitar **13–15 minggu**.

## 1.8 Sistematika Penulisan

Sistematika penulisan perancangan aplikasi ini adalah sebagai berikut:

* **Bab I Pendahuluan**, membahas latar belakang, rumusan masalah, tujuan, manfaat, ruang lingkup, dan estimasi waktu pengerjaan.
* **Bab II Analisis Kebutuhan Sistem**, membahas kebutuhan fungsional dan non-fungsional aplikasi.
* **Bab III Proses Bisnis Sistem**, membahas alur kerja dan interaksi antar pihak yang terlibat.
* **Bab IV Desain Aplikasi**, membahas rancangan tampilan dan struktur sistem.

---

# BAB II

# ANALISIS KEBUTUHAN SISTEM

## 2.1 Gambaran Umum Sistem

Sistem Ticketing Layanan Kominfo Kota Bukittinggi merupakan aplikasi internal Pemerintah Kota Bukittinggi yang digunakan untuk mencatat, mengelola, dan melaporkan pekerjaan atau permintaan bantuan teknis dari SKPD kepada Dinas Komunikasi dan Informatika. Sistem ini dirancang untuk memberikan gambaran jumlah dan beban kerja Kominfo secara terukur dan terdokumentasi.

## 2.2 Pemangku Kepentingan (Stakeholder)

Adapun pihak-pihak yang terlibat dalam sistem ini antara lain:

1. **SKPD**: sebagai pihak pengaju permintaan pekerjaan.
2. **Kominfo**: sebagai pihak pelaksana dan penanggung jawab pekerjaan.
3. **Pimpinan**: sebagai pihak yang memantau laporan dan beban kerja.
4. **Administrator Sistem**: sebagai pengelola data dan pengguna sistem.

## 2.3 Kebutuhan Fungsional

Kebutuhan fungsional sistem meliputi:

1. Sistem dapat menerima dan mencatat tiket permintaan pekerjaan dari SKPD.
2. Sistem dapat menampilkan daftar tiket beserta statusnya.
3. Sistem dapat mengelompokkan pekerjaan berdasarkan jenis dan SKPD pemohon.
4. Sistem dapat mencatat progres dan hasil pekerjaan yang dilakukan Kominfo.
5. Sistem dapat menampilkan rekap jumlah pekerjaan dan beban kerja.
6. Sistem dapat menghasilkan laporan pekerjaan untuk pimpinan.

## 2.4 Kebutuhan Non-Fungsional

Kebutuhan non-fungsional sistem meliputi:

1. **Keamanan**: sistem hanya dapat diakses oleh pengguna internal Pemko sesuai hak akses.
2. **Ketersediaan**: sistem dapat diakses selama jam kerja pemerintahan.
3. **Kemudahan Penggunaan**: antarmuka sistem mudah dipahami oleh pengguna non-teknis.
4. **Kinerja**: sistem mampu menangani data tiket dalam jumlah besar.

## 2.5 Batasan Sistem

Batasan dalam pengembangan sistem ini adalah:

1. Sistem hanya digunakan untuk kebutuhan internal Pemerintah Kota Bukittinggi.
2. Sistem tidak membahas manajemen anggaran atau pembiayaan pekerjaan.
3. Sistem berfokus pada pencatatan, pemantauan, dan pelaporan beban kerja.

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

## 4.4 Gambaran Antarmuka Aplikasi

Antarmuka aplikasi dirancang dengan prinsip sederhana dan informatif, antara lain:

1. Halaman dashboard menampilkan grafik atau ringkasan angka jumlah tiket.
2. Halaman pengajuan tiket menggunakan formulir yang jelas dan ringkas.
3. Halaman daftar tiket menampilkan status pekerjaan secara visual.
4. Halaman laporan menyajikan data dalam bentuk tabel dan ringkasan.

Desain visual dibuat konsisten agar pengguna mudah beradaptasi.

## 4.5 Desain Arsitektur Sistem (Gambaran Umum)

Secara umum, arsitektur sistem dirancang sebagai berikut:

* Aplikasi berbasis web yang diakses melalui jaringan internal Pemko.
* Database terpusat untuk menyimpan data tiket dan laporan.
* Mekanisme autentikasi dan otorisasi berdasarkan peran pengguna.

Arsitektur ini dipilih untuk mendukung kemudahan pengelolaan, keamanan data, dan pengembangan sistem di masa depan.

## 4.6 Ringkasan Desain Aplikasi

Dengan desain aplikasi ini, diharapkan Sistem Ticketing Layanan Kominfo Kota Bukittinggi dapat menjadi sarana resmi dalam pencatatan dan pelaporan pekerjaan. Desain yang sederhana dan terstruktur memudahkan seluruh pihak dalam menggunakan sistem serta memberikan informasi yang dibutuhkan pimpinan secara cepat dan akurat.

## 4.7 Desain Data Utama Aplikasi

Untuk mendukung proses bisnis, sistem ini menggunakan beberapa data utama, antara lain:

1. **Data SKPD**: nama SKPD, kontak, dan penanggung jawab.
2. **Data Pengguna**: akun pengguna beserta peran dan hak akses.
3. **Data Tiket Pekerjaan**: nomor tiket, SKPD pemohon, jenis pekerjaan, tanggal pengajuan, status, dan hasil pekerjaan.
4. **Data Jenis Pekerjaan**: kategori pekerjaan yang ditangani Kominfo.

Struktur data ini dirancang sederhana namun cukup untuk mendukung kebutuhan pelaporan dan analisis beban kerja.

## 4.8 Desain Dashboard Pimpinan

Dashboard pimpinan dirancang untuk memberikan gambaran cepat mengenai kondisi pekerjaan Kominfo, antara lain:

1. Total tiket yang masuk dalam periode tertentu.
2. Jumlah tiket berdasarkan status (baru, diproses, selesai).
3. Rekap jumlah pekerjaan per SKPD.
4. Gambaran beban kerja Kominfo dalam bentuk ringkasan angka.

Informasi disajikan dalam bentuk ringkasan visual agar mudah dipahami dan digunakan sebagai bahan evaluasi.

## 4.9 Desain Laporan Sistem

Laporan dalam sistem ini dirancang untuk mendukung kebutuhan monitoring dan evaluasi, meliputi:

1. Laporan jumlah pekerjaan per periode.
2. Laporan pekerjaan berdasarkan SKPD.
3. Laporan status penyelesaian pekerjaan.

Laporan dapat ditampilkan di layar dan disiapkan untuk dicetak atau diunduh sesuai kebutuhan pimpinan.

## 4.10 Arah Pengembangan Sistem

Sebagai pengembangan lanjutan, sistem ini dapat ditingkatkan dengan:

1. Penambahan indikator waktu penyelesaian pekerjaan.
2. Integrasi notifikasi internal.
3. Pengembangan analisis beban kerja yang lebih mendalam.

Pengembangan lanjutan ini dapat dilakukan secara bertahap sesuai kebutuhan dan kebijakan Pemerintah Kota Bukittinggi.
