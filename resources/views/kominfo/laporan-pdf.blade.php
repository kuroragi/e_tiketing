<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Tiket Pekerjaan</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
            color: #1e293b;
            background: #fff;
            line-height: 1.45;
        }

        /* ── Header Kop ─────────────────────────────────────── */
        .kop {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 3px solid #1e40af;
            margin-bottom: 14px;
        }

        .kop .kop-title {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #1e3a8a;
        }

        .kop .kop-sub {
            font-size: 11pt;
            font-weight: bold;
            color: #1e40af;
        }

        .kop .kop-city {
            font-size: 10pt;
            font-weight: bold;
            color: #1e40af;
        }

        .kop .kop-periode {
            font-size: 9pt;
            color: #475569;
            margin-top: 3px;
        }

        /* ── Info Box ───────────────────────────────────────── */
        .info-box {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 14px;
            font-size: 9pt;
            color: #475569;
        }

        .info-box table { width: 100%; }
        .info-box td { padding: 1px 6px; }
        .info-box td:first-child { font-weight: bold; color: #334155; width: 120px; }

        /* ── Ringkasan Statistik ────────────────────────────── */
        .stat-row {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .stat-row td {
            border: 1px solid #cbd5e1;
            text-align: center;
            padding: 7px 4px;
            font-size: 9pt;
        }

        .stat-val {
            font-size: 14pt;
            font-weight: bold;
            line-height: 1.1;
        }

        .stat-lbl {
            font-size: 7.5pt;
            color: #64748b;
            margin-top: 2px;
        }

        .stat-total   .stat-val { color: #1e40af; }
        .stat-selesai .stat-val { color: #16a34a; }
        .stat-proses  .stat-val { color: #0891b2; }
        .stat-baru    .stat-val { color: #ca8a04; }
        .stat-ditolak .stat-val { color: #dc2626; }
        .stat-waktu   .stat-val { color: #7c3aed; }

        /* ── Judul Seksi ────────────────────────────────────── */
        .section-title {
            font-size: 10pt;
            font-weight: bold;
            color: #1e3a8a;
            padding: 5px 0 4px;
            border-bottom: 1.5px solid #bfdbfe;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        /* ── Tabel Tiket ────────────────────────────────────── */
        .tiket-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            margin-bottom: 14px;
        }

        .tiket-table thead tr {
            background: #1e40af;
            color: #fff;
        }

        .tiket-table thead th {
            padding: 6px 5px;
            font-weight: bold;
            text-align: left;
            border: 1px solid #1e3a8a;
        }

        .tiket-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .tiket-table tbody tr:nth-child(odd) {
            background: #fff;
        }

        .tiket-table tbody td {
            padding: 5px 5px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .badge-status {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 7.5pt;
            font-weight: bold;
        }

        .status-baru                { background: #fef9c3; color: #854d0e; }
        .status-diproses            { background: #dbeafe; color: #1e40af; }
        .status-menunggu_verifikasi { background: #ede9fe; color: #5b21b6; }
        .status-selesai             { background: #dcfce7; color: #14532d; }
        .status-ditolak             { background: #fee2e2; color: #7f1d1d; }
        .status-dibatalkan          { background: #f1f5f9; color: #475569; }

        /* ── Footer ─────────────────────────────────────────── */
        .page-footer {
            margin-top: 20px;
            border-top: 1.5px solid #bfdbfe;
            padding-top: 10px;
        }

        .ttd-area {
            width: 100%;
        }

        .ttd-area td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 9pt;
            padding: 0 10px;
        }

        .ttd-line {
            margin-top: 55px;
            border-top: 1px solid #334155;
            display: inline-block;
            width: 160px;
        }

        .footer-note {
            text-align: center;
            font-size: 7.5pt;
            color: #94a3b8;
            margin-top: 8px;
        }

        /* ── Page break ─────────────────────────────────────── */
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    {{-- ── KOP / HEADER ────────────────────────────────────── --}}
    <div class="kop">
        <div class="kop-title">Laporan Tiket Pekerjaan</div>
        <div class="kop-sub">Dinas Komunikasi dan Informatika</div>
        <div class="kop-city">Kota Bukittinggi</div>
        <div class="kop-periode">
            Per Tanggal:
            @if ($dari->format('Y-m') === $sampai->format('Y-m'))
                {{ $dari->format('d') }} – {{ $sampai->translatedFormat('d F Y') }}
            @else
                {{ $dari->translatedFormat('d F Y') }} s/d {{ $sampai->translatedFormat('d F Y') }}
            @endif
        </div>
    </div>

    {{-- ── INFO CETAK ──────────────────────────────────────── --}}
    <div class="info-box">
        <table>
            <tr>
                <td>Periode Laporan</td>
                <td>: {{ $dari->translatedFormat('d F Y') }} s/d {{ $sampai->translatedFormat('d F Y') }}</td>
                <td>Dicetak Oleh</td>
                <td>: {{ $printedBy }}</td>
            </tr>
            <tr>
                <td>Filter Petugas</td>
                <td>: {{ !empty($selectedPetugas) ? implode(', ', $selectedPetugas) : 'Semua Petugas' }}</td>
                <td>Tanggal Cetak</td>
                <td>: {{ $printedAt->translatedFormat('d F Y, H:i') }} WIB</td>
            </tr>
        </table>
    </div>

    {{-- ── RINGKASAN ────────────────────────────────────────── --}}
    <div class="section-title">&#9632; Ringkasan</div>
    <table class="stat-row">
        <tr>
            <td class="stat-total">
                <div class="stat-val">{{ $summary['total'] }}</div>
                <div class="stat-lbl">Total Tiket</div>
            </td>
            <td class="stat-selesai">
                <div class="stat-val">{{ $summary['selesai'] }}</div>
                <div class="stat-lbl">Selesai</div>
            </td>
            <td class="stat-proses">
                <div class="stat-val">{{ $summary['diproses'] }}</div>
                <div class="stat-lbl">Diproses</div>
            </td>
            <td class="stat-baru">
                <div class="stat-val">{{ $summary['baru'] + $summary['menunggu'] }}</div>
                <div class="stat-lbl">Baru / Verifikasi</div>
            </td>
            <td class="stat-ditolak">
                <div class="stat-val">{{ $summary['ditolak'] + $summary['dibatalkan'] }}</div>
                <div class="stat-lbl">Ditolak / Batal</div>
            </td>
            <td class="stat-waktu">
                <div class="stat-val">{{ $summary['rata_hari'] }}</div>
                <div class="stat-lbl">Rerata Hari Selesai</div>
            </td>
        </tr>
    </table>

    {{-- ── DAFTAR TIKET ─────────────────────────────────────── --}}
    <div class="section-title">&#9632; Daftar Tiket</div>

    @if ($tickets->isEmpty())
        <p style="text-align:center;color:#64748b;padding:20px 0;">Tidak ada data tiket pada periode ini.</p>
    @else
        <table class="tiket-table">
            <thead>
                <tr>
                    <th style="width:3%">No</th>
                    <th style="width:9%">No. Tiket</th>
                    <th style="width:19%">Judul / Deskripsi</th>
                    <th style="width:13%">SKPD</th>
                    <th style="width:11%">Jenis Pekerjaan</th>
                    <th style="width:12%">Petugas</th>
                    <th style="width:9%">Status</th>
                    <th style="width:8%">Tgl Masuk</th>
                    <th style="width:8%">Tgl Selesai</th>
                    <th style="width:8%">Durasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tickets as $i => $ticket)
                    @php
                        $statusClass = 'status-' . $ticket->status;
                        $statusLabel = match($ticket->status) {
                            'baru'                => 'Baru',
                            'diproses'            => 'Diproses',
                            'menunggu_verifikasi' => 'Menunggu Verif.',
                            'selesai'             => 'Selesai',
                            'ditolak'             => 'Ditolak',
                            'dibatalkan'          => 'Dibatalkan',
                            default               => ucfirst($ticket->status),
                        };
                        $durasi = $ticket->resolutionDays();
                    @endphp
                    <tr>
                        <td style="text-align:center;">{{ $i + 1 }}</td>
                        <td style="font-weight:bold;color:#1e40af;">{{ $ticket->number }}</td>
                        <td>
                            <strong>{{ \Illuminate\Support\Str::limit($ticket->title, 55) }}</strong>
                        </td>
                        <td>{{ $ticket->department->name ?? '-' }}</td>
                        <td>{{ $ticket->category->name ?? '-' }}</td>
                        <td>{{ $ticket->assignees->pluck('name')->join(', ') ?: '-' }}</td>
                        <td>
                            <span class="badge-status {{ $statusClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                        <td>{{ $ticket->closed_at ? $ticket->closed_at->format('d/m/Y') : '-' }}</td>
                        <td style="text-align:center;">{{ $durasi !== null ? $durasi . ' hr' : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- ── TANDA TANGAN ─────────────────────────────────────── --}}
    <div class="page-footer">
        <table class="ttd-area">
            <tr>
                <td>
                    Mengetahui,<br>
                    <strong>Kepala Dinas Komunikasi dan Informatika</strong><br>
                    Kota Bukittinggi
                    <div><span class="ttd-line"></span></div>
                    <br>
                    <strong>_________________________</strong><br>
                    <span style="font-size:8pt;">NIP. ___________________________</span>
                </td>
                <td>
                    Bukittinggi, {{ $printedAt->translatedFormat('d F Y') }}<br>
                    <strong>Dibuat oleh,</strong><br>
                    &nbsp;
                    <div><span class="ttd-line"></span></div>
                    <br>
                    <strong>{{ $printedBy }}</strong><br>
                    <span style="font-size:8pt;">Administrator Sistem</span>
                </td>
            </tr>
        </table>
        <div class="footer-note">
            Dokumen ini dicetak secara otomatis oleh Sistem Ticketing Dinas Komunikasi dan Informatika Kota Bukittinggi
            pada {{ $printedAt->translatedFormat('d F Y H:i') }} WIB
        </div>
    </div>

</body>
</html>
