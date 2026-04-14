<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Priority;
use App\Models\Setting;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Rules\SafeFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LandingController extends Controller
{
    /**
     * Halaman landing publik.
     */
    public function index()
    {
        $showStats = Setting::get('landing_show_stats', true);

        $stats = [
            'total'    => Ticket::count(),
            'selesai'  => Ticket::where('status', 'selesai')->count(),
            'diproses' => Ticket::whereIn('status', ['diproses', 'menunggu_verifikasi'])->count(),
            'rata_hari' => $this->avgResolutionDays(),
        ];

        return view('landing', compact('stats', 'showStats'));
    }

    /**
     * Form pengaduan publik.
     */
    public function createTicket()
    {
        // Cek apakah pengaduan publik diaktifkan
        if (! Setting::get('landing_enable_public_ticket', true)) {
            return redirect()->route('landing')->with('warning', 'Layanan pengaduan publik sedang tidak aktif.');
        }

        $categories = Category::aktif()->orderBy('name')->get();
        $priorities = Priority::ordered()->get();

        // Captcha sederhana
        $captchaNum1 = random_int(2, 15);
        $captchaNum2 = random_int(1, 10);
        $captchaHash = hash_hmac('sha256', $captchaNum1 + $captchaNum2, config('app.key'));

        // Cari layanan yang dipilih dari landing page
        $selectedService = null;
        $kategoriId = request('kategori');
        if ($kategoriId) {
            foreach ($this->getServices() as $service) {
                if (isset($service['category_id']) && (string) $service['category_id'] === (string) $kategoriId) {
                    $selectedService = $service;
                    break;
                }
            }
        }

        return view('public.submit-ticket', compact('categories', 'priorities', 'captchaNum1', 'captchaNum2', 'captchaHash', 'selectedService'));
    }

    /**
     * Simpan pengaduan publik.
     */
    public function storeTicket(Request $request)
    {
        // Cek apakah pengaduan publik diaktifkan
        if (! Setting::get('landing_enable_public_ticket', true)) {
            return redirect()->route('landing')->with('warning', 'Layanan pengaduan publik sedang tidak aktif.');
        }

        // Validasi captcha
        $answer = (int) $request->input('captcha_answer');
        $hash   = $request->input('captcha_hash');
        $valid  = hash_equals($hash, hash_hmac('sha256', (string) $answer, config('app.key')));

        if (! $valid) {
            return back()->withErrors(['captcha_answer' => 'Jawaban verifikasi salah.'])->withInput();
        }

        $validated = $request->validate([
            'public_name'    => 'required|string|max:255',
            'public_email'   => 'required|email|max:255',
            'public_phone'   => 'required|string|max:20',
            'public_nik'     => 'nullable|string|size:16',
            'public_address' => 'nullable|string|max:500',
            'title'          => 'required|string|max:255',
            'description'    => 'required|string|min:20',
            'category_id'    => 'required|exists:categories,id',
            'priority_id'    => 'required|exists:priorities,id',
            'lampiran'       => 'nullable|array|max:5',
            'lampiran.*'     => ['file', 'max:10240', new SafeFile()],
        ], [
            'public_name.required'    => 'Nama lengkap harus diisi.',
            'public_email.required'   => 'Email harus diisi.',
            'public_email.email'      => 'Format email tidak valid.',
            'public_phone.required'   => 'Nomor HP harus diisi.',
            'public_nik.size'         => 'NIK harus terdiri dari 16 digit.',
            'title.required'          => 'Judul pengaduan harus diisi.',
            'description.required'    => 'Deskripsi pengaduan harus diisi.',
            'description.min'         => 'Deskripsi minimal 20 karakter.',
            'category_id.required'    => 'Kategori layanan harus dipilih.',
            'priority_id.required'    => 'Prioritas harus dipilih.',
        ]);

        // Generate tracking code (UUID)
        $trackingCode = (string) Str::uuid();

        $ticket = Ticket::create([
            'number'         => Ticket::generateNumber(),
            'title'          => $validated['title'],
            'description'    => $validated['description'],
            'requester_id'   => null,
            'department_id'  => null,
            'category_id'    => $validated['category_id'],
            'priority_id'    => $validated['priority_id'],
            'contact_pic'    => $validated['public_name'],
            'public_name'    => $validated['public_name'],
            'public_email'   => $validated['public_email'],
            'public_phone'   => $validated['public_phone'],
            'public_nik'     => $validated['public_nik'] ?? null,
            'public_address' => $validated['public_address'] ?? null,
            'source'         => 'public',
            'tracking_code'  => $trackingCode,
            'status'         => 'baru',
        ]);

        // Simpan lampiran
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $storedName = $file->hashName();
                $path = $file->storeAs('lampiran', $storedName, 'public');

                TicketAttachment::create([
                    'ticket_id'     => $ticket->id,
                    'user_id'       => null,
                    'original_name' => $file->getClientOriginalName(),
                    'stored_name'   => $storedName,
                    'path'          => $path,
                    'mime_type'     => $file->getMimeType(),
                    'size'          => $file->getSize(),
                ]);
            }
        }

        // Audit log
        AuditLog::create([
            'user_id'     => null,
            'action'      => 'created',
            'entity_type' => 'Ticket',
            'entity_id'   => $ticket->id,
            'entity_name' => $ticket->number,
            'description' => "Pengaduan publik baru: {$ticket->number} - {$ticket->title} (oleh {$ticket->public_name})",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return redirect()->route('public.ticket.success', $ticket->tracking_code);
    }

    /**
     * Halaman sukses setelah submit ticket.
     */
    public function ticketSuccess(string $trackingCode)
    {
        $ticket = Ticket::with(['category', 'priority'])
            ->where('tracking_code', $trackingCode)
            ->firstOrFail();

        return view('public.ticket-success', compact('ticket'));
    }

    /**
     * Lacak tiket publik.
     */
    public function trackTicket(Request $request)
    {
        $ticket   = null;
        $comments = collect();

        if ($request->filled('code')) {
            $code = trim($request->code);

            $ticket = Ticket::with(['category', 'priority', 'requester'])
                ->where('tracking_code', $code)
                ->orWhere('number', $code)
                ->first();

            if ($ticket) {
                // Hanya tampilkan komentar publik (comment, status_change, progress)
                $comments = $ticket->comments()
                    ->with('user')
                    ->whereIn('type', ['comment', 'status_change', 'progress'])
                    ->orderByDesc('created_at')
                    ->get();
            }
        }

        return view('public.track-ticket', compact('ticket', 'comments'));
    }

    // ── Helpers ──────────────────────────────────────────────────

    private function avgResolutionDays(): float
    {
        $tickets = Ticket::where('status', 'selesai')->whereNotNull('closed_at')->get();
        if ($tickets->isEmpty()) return 0;
        return round($tickets->avg(fn($t) => $t->created_at->diffInDays($t->closed_at)), 1);
    }

    private function getServices(): array
    {
        $custom = Setting::get('landing_services');

        if (is_array($custom) && count($custom)) {
            return $custom;
        }

        // Default services
        return [
            [
                'icon'                 => 'bi-camera-video',
                'title'                => 'Permintaan Data CCTV',
                'description'          => 'Permintaan rekaman CCTV untuk keperluan investigasi, keamanan, atau bukti kejadian di area publik.',
                'color'                => '#6366f1',
                'category_id'          => null,
                'title_placeholder'    => 'Permintaan rekaman CCTV di [lokasi] pada [tanggal]',
                'desc_placeholder'     => "Saya membutuhkan rekaman CCTV di lokasi... pada tanggal... pukul...\n\nKeperluan: (investigasi, laporan polisi, dll.)\nDeskripsi singkat kejadian:",
            ],
            [
                'icon'                 => 'bi-hdd-network',
                'title'                => 'Gangguan Jaringan & Internet',
                'description'          => 'Laporkan gangguan jaringan internet, WiFi publik, atau infrastruktur telekomunikasi di wilayah Bukittinggi.',
                'color'                => '#0ea5e9',
                'category_id'          => null,
                'title_placeholder'    => 'Gangguan jaringan internet di [nama lokasi/kelurahan]',
                'desc_placeholder'     => "Terjadi gangguan jaringan di lokasi... sejak tanggal/pukul...\n\nJenis gangguan: (putus total / lambat / tidak stabil)\nDampak yang dirasakan:",
            ],
            [
                'icon'                 => 'bi-globe2',
                'title'                => 'Informasi Website Resmi',
                'description'          => 'Permintaan update informasi, pelaporan konten tidak sesuai, atau saran untuk website pemerintah.',
                'color'                => '#10b981',
                'category_id'          => null,
                'title_placeholder'    => 'Laporan/permintaan terkait website [nama website]',
                'desc_placeholder'     => "URL halaman yang terdampak: ...\n\nJenis permintaan: (update informasi / konten tidak sesuai / saran)\nDetail permintaan:",
            ],
            [
                'icon'                 => 'bi-database',
                'title'                => 'Permintaan Data Publik',
                'description'          => 'Permintaan data statistik, data terbuka, atau informasi publik dari Dinas Kominfo.',
                'color'                => '#f59e0b',
                'category_id'          => null,
                'title_placeholder'    => 'Permintaan data [jenis data] untuk [keperluan]',
                'desc_placeholder'     => "Jenis data yang diminta: ...\n\nPeriode/rentang data: ...\nKeperluan penggunaan data:\nInstansi/organisasi pemohon (jika ada):",
            ],
            [
                'icon'                 => 'bi-megaphone',
                'title'                => 'Pengaduan Layanan Publik',
                'description'          => 'Sampaikan keluhan atau pengaduan terkait layanan publik berbasis teknologi informasi.',
                'color'                => '#ef4444',
                'category_id'          => null,
                'title_placeholder'    => 'Pengaduan terkait [nama layanan yang dipermasalahkan]',
                'desc_placeholder'     => "Layanan yang bermasalah: ...\n\nKronologi kejadian:\nUpaya yang sudah dilakukan:\nHarapan/permintaan tindak lanjut:",
            ],
            [
                'icon'                 => 'bi-question-circle',
                'title'                => 'Pertanyaan & Konsultasi',
                'description'          => 'Ajukan pertanyaan atau konsultasi terkait layanan Kominfo untuk kebutuhan organisasi atau umum.',
                'color'                => '#8b5cf6',
                'category_id'          => null,
                'title_placeholder'    => 'Konsultasi mengenai [topik pertanyaan]',
                'desc_placeholder'     => "Topik konsultasi: ...\n\nLatar belakang pertanyaan:\nInformasi atau solusi yang diharapkan:",
            ],
        ];
    }
}
