<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Department;
use App\Models\Priority;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\TicketComment;
use App\Models\User;
use App\Rules\SafeFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KominfoController extends Controller
{
    //  Dashboard (unified for all roles) 

    public function dashboard(Request $request)
    {
        $user  = Auth::user();
        $query = Ticket::query();

        // Scope berdasarkan peran
        if ($user->isSkpd()) {
            $query->where('department_id', $user->department_id);
        } elseif ($user->isPetugas()) {
            $query->whereHas('assignees', fn($q) => $q->where('users.id', $user->id));
        }

        // --- Statistik tiket utama ---
        $stats = [
            'total'    => (clone $query)->count(),
            'baru'     => (clone $query)->where('status', 'baru')->count(),
            'diproses' => (clone $query)->where('status', 'diproses')->count(),
            'selesai'  => (clone $query)->where('status', 'selesai')->count(),
        ];
        $selesaiQuery = (clone $query)->where('status', 'selesai')->whereNotNull('closed_at')->get();
        $stats['rata_penyelesaian'] = $selesaiQuery->count()
            ? round($selesaiQuery->avg(fn($t) => $t->created_at->diffInDays($t->closed_at)), 1)
            : 0;

        // --- 10 tiket terbaru ---
        $recentTickets = (clone $query)
            ->with(['requester', 'department', 'category', 'priority', 'assignees'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // --- Quick actions ---
        $quickActions = [];
        if ($user->isSkpd() || $user->isAdmin()) {
            $quickActions[] = ['icon' => 'plus-circle',      'title' => 'Buat Tiket',       'url' => route('tiket.create'),             'color' => 'primary'];
        }
        if ($user->isSkpd()) {
            $quickActions[] = ['icon' => 'ticket-perforated','title' => 'Tiket Saya',       'url' => route('tiket.saya'),               'color' => 'info'];
        }
        if (! $user->isSkpd()) {
            $quickActions[] = ['icon' => 'list-task',        'title' => 'Daftar Tiket',     'url' => route('tiket.index'),              'color' => 'info'];
        }
        if ($user->isAdmin() || $user->isPetugas()) {
            $quickActions[] = ['icon' => 'people',           'title' => 'Manajemen Tiket',  'url' => route('ticket.management.index'),  'color' => 'warning'];
        }
        if ($user->isAdmin() || $user->isPimpinan() || $user->isPetugas()) {
            $quickActions[] = ['icon' => 'bar-chart-line',   'title' => 'Laporan',          'url' => route('laporan.index'),            'color' => 'success'];
        }

        // --- Data ekstra per peran ---
        $adminStats       = null;
        $pimpinanStats    = null;
        $recentActivities = collect();
        $petugasWorkload  = collect();
        $skpdStats        = collect();
        $chartData        = null;

        // ── ADMIN ──────────────────────────────────────────────────────────────
        if ($user->isAdmin()) {
            $adminStats = [
                ['label' => 'Total Pengguna',   'nilai' => User::count(),
                 'icon'  => 'bi-people',          'color' => 'primary',
                 'sub'   => User::where('created_at', '>=', now()->subWeek())->count() . ' minggu ini'],
                ['label' => 'Total SKPD Aktif', 'nilai' => Department::aktif()->count(),
                 'icon'  => 'bi-building',         'color' => 'info',
                 'sub'   => 'departemen terdaftar'],
                ['label' => 'Belum Ditugaskan', 'nilai' => Ticket::whereDoesntHave('assignees')->whereIn('status', ['baru'])->count(),
                 'icon'  => 'bi-person-x',         'color' => 'danger',
                 'sub'   => 'tiket menunggu petugas'],
                ['label' => 'Selesai Bulan Ini','nilai' => Ticket::where('status', 'selesai')->whereMonth('updated_at', now()->month)->count(),
                 'icon'  => 'bi-check-circle',     'color' => 'success',
                 'sub'   => 'bulan ' . now()->translatedFormat('F')],
            ];

            $recentActivities = AuditLog::with('user')
                ->orderByDesc('created_at')->limit(8)->get()
                ->map(fn($log) => [
                    'user'   => $log->user->name ?? 'Sistem',
                    'action' => $log->actionLabel(),
                    'target' => $log->entity_name ?? '-',
                    'waktu'  => $log->created_at->diffForHumans(),
                    'icon'   => match($log->action) {
                        'created'        => 'bi-plus-circle',
                        'updated'        => 'bi-pencil',
                        'status_changed' => 'bi-arrow-repeat',
                        'assigned'       => 'bi-person-check',
                        'login'          => 'bi-box-arrow-in-right',
                        default          => 'bi-activity',
                    },
                    'color'  => match($log->action) {
                        'created'  => 'success',
                        'updated'  => 'info',
                        'login'    => 'primary',
                        'assigned' => 'warning',
                        default    => 'secondary',
                    },
                ]);

            $skpdStats = Department::aktif()
                ->withCount(['tickets as total_tiket', 'tickets as tiket_baru' => fn($q) => $q->where('status', 'baru')])
                ->orderByDesc('total_tiket')->limit(6)->get();
        }

        // ── PIMPINAN ───────────────────────────────────────────────────────────
        if ($user->isPimpinan()) {
            $sedangBerjalan  = Ticket::whereIn('status', ['baru', 'diproses', 'menunggu_verifikasi'])->count();
            $selesaiBulanIni = Ticket::where('status', 'selesai')
                ->whereMonth('closed_at', now()->month)->whereYear('closed_at', now()->year)->count();
            $selesaiAll = Ticket::where('status', 'selesai')->whereNotNull('closed_at')->get();
            $rataHari   = $selesaiAll->count()
                ? round($selesaiAll->avg(fn($t) => $t->created_at->diffInDays($t->closed_at)), 1) : 0;

            $pimpinanStats = [
                ['label' => 'Total Tiket',       'nilai' => Ticket::count(),     'icon' => 'bi-ticket-perforated', 'color' => 'primary'],
                ['label' => 'Sedang Dikerjakan', 'nilai' => $sedangBerjalan,     'icon' => 'bi-hourglass-split',   'color' => 'warning'],
                ['label' => 'Selesai Bulan Ini', 'nilai' => $selesaiBulanIni,    'icon' => 'bi-check2-circle',     'color' => 'success'],
                ['label' => 'Rata-rata Selesai', 'nilai' => $rataHari . ' hari', 'icon' => 'bi-stopwatch',         'color' => 'info'],
            ];

            $skpdStats = Department::aktif()
                ->withCount(['tickets as total_tiket', 'tickets as tiket_baru' => fn($q) => $q->where('status', 'baru')])
                ->orderByDesc('total_tiket')->limit(8)->get();
        }

        // ── CHART DATA (Admin + Pimpinan) ──────────────────────────────────────
        if ($user->isAdmin() || $user->isPimpinan()) {
            $chartMonthly = [];
            for ($i = 5; $i >= 0; $i--) {
                $m = now()->subMonths($i);
                $chartMonthly[] = [
                    'label'   => $m->translatedFormat('M Y'),
                    'masuk'   => Ticket::whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count(),
                    'selesai' => Ticket::where('status', 'selesai')
                        ->whereYear('closed_at', $m->year)->whereMonth('closed_at', $m->month)->count(),
                ];
            }

            $chartStatus = [
                'labels' => ['Baru', 'Diproses', 'Menunggu Verifikasi', 'Selesai', 'Ditolak/Batal'],
                'data'   => [
                    Ticket::where('status', 'baru')->count(),
                    Ticket::where('status', 'diproses')->count(),
                    Ticket::where('status', 'menunggu_verifikasi')->count(),
                    Ticket::where('status', 'selesai')->count(),
                    Ticket::whereIn('status', ['ditolak', 'dibatalkan'])->count(),
                ],
                'colors' => ['#eab308', '#3b82f6', '#f97316', '#22c55e', '#ef4444'],
            ];

            $chartData = compact('chartMonthly', 'chartStatus');
        }

        // ── WORKLOAD (Admin + Petugas) ─────────────────────────────────────────
        if ($user->isAdmin() || $user->isPetugas()) {
            $petugasWorkload = User::role('petugas')
                ->withCount(['assignedTicketsMulti as aktif_count' => fn($q) => $q->whereIn('status', ['baru', 'diproses'])])
                ->orderBy('aktif_count')->get();
        }

        // ══════════════════════════════════════════════════════════════════════
        //  TAB ANALITIK — data laporan (hanya Admin, Petugas, Pimpinan)
        // ══════════════════════════════════════════════════════════════════════
        $analyticsData = null;

        if ($user->isAdmin() || $user->isPetugas() || $user->isPimpinan()) {
            // Periode default: bulan ini; bisa dioverride via query string
            $dari   = $request->filled('dari')   ? Carbon::parse($request->dari)->startOfDay()   : now()->startOfMonth();
            $sampai = $request->filled('sampai') ? Carbon::parse($request->sampai)->endOfDay()   : now()->endOfMonth();
            $deptId = $request->get('analytics_dept');
            $catId  = $request->get('analytics_cat');

            $base = Ticket::whereBetween('created_at', [$dari, $sampai]);
            if ($deptId) $base->where('department_id', $deptId);
            if ($catId)  $base->where('category_id', $catId);

            $totalA   = (clone $base)->count();
            $selesaiA = (clone $base)->where('status', 'selesai')->count();
            $avgA     = (clone $base)->where('status', 'selesai')->whereNotNull('closed_at')->get()
                ->avg(fn($t) => $t->created_at->diffInDays($t->closed_at));

            $summary = [
                'total_tiket'        => $totalA,
                'tiket_selesai'      => $selesaiA,
                'persentase_selesai' => $totalA ? round($selesaiA / $totalA * 100) : 0,
                'rata_waktu'         => round($avgA ?? 0, 1),
                'backlog'            => (clone $base)->whereIn('status', ['baru', 'diproses'])->count(),
            ];

            $statusDist = [
                'baru'       => (clone $base)->where('status', 'baru')->count(),
                'diproses'   => (clone $base)->where('status', 'diproses')->count(),
                'selesai'    => $selesaiA,
                'ditolak'    => (clone $base)->where('status', 'ditolak')->count(),
                'dibatalkan' => (clone $base)->where('status', 'dibatalkan')->count(),
            ];

            // Tren bulanan 6 bulan terakhir (untuk tab analitik)
            $trendMonthly = [];
            for ($i = 5; $i >= 0; $i--) {
                $m = now()->subMonths($i);
                $trendMonthly[] = [
                    'label'   => $m->translatedFormat('M Y'),
                    'masuk'   => Ticket::whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count(),
                    'selesai' => Ticket::where('status', 'selesai')
                        ->whereYear('closed_at', $m->year)->whereMonth('closed_at', $m->month)->count(),
                ];
            }

            // Rekap per SKPD (dengan filter periode)
            $skpdReport = Department::withCount([
                'tickets as total'   => fn($q) => $q->whereBetween('created_at', [$dari, $sampai]),
                'tickets as selesai' => fn($q) => $q->whereBetween('created_at', [$dari, $sampai])->where('status', 'selesai'),
            ])->having('total', '>', 0)->orderByDesc('total')->get()
                ->map(fn($d) => [
                    'nama'       => $d->name,
                    'total'      => $d->total,
                    'selesai'    => $d->selesai,
                    'persentase' => $d->total ? round($d->selesai / $d->total * 100) : 0,
                ])->toArray();

            // Rekap per kategori
            $jenisReport = Category::withCount([
                'tickets as jumlah' => fn($q) => $q->whereBetween('created_at', [$dari, $sampai]),
            ])->having('jumlah', '>', 0)->orderByDesc('jumlah')->get()
                ->map(fn($c) => [
                    'nama'       => $c->name,
                    'jumlah'     => $c->jumlah,
                    'persentase' => $totalA ? round($c->jumlah / $totalA * 100) : 0,
                ])->toArray();

            $analyticsData = compact(
                'summary', 'statusDist', 'trendMonthly', 'skpdReport', 'jenisReport',
                'dari', 'sampai', 'deptId', 'catId'
            );
        }

        $skpdList   = Department::aktif()->orderBy('name')->get();
        $categories = Category::aktif()->orderBy('name')->get();

        return view('kominfo.dashboard', compact(
            'stats', 'recentTickets', 'quickActions',
            'adminStats', 'pimpinanStats', 'recentActivities',
            'petugasWorkload', 'skpdStats', 'chartData',
            'analyticsData', 'skpdList', 'categories'
        ));
    }

    //  Form Pengajuan 

    public function create()
    {
        $user = Auth::user();
        $this->authorize('create', Ticket::class);

        $skpdList      = Department::aktif()->orderBy('name')->get();
        $jenisKerjaan  = Category::aktif()->orderBy('name')->get();
        $prioritasList = Priority::ordered()->get();

        return view('kominfo.tiket-pengajuan', compact('skpdList', 'jenisKerjaan', 'prioritasList'));
    }

    //  Simpan Tiket Baru 

    public function store(Request $request)
    {
        $user = Auth::user();
        $this->authorize('create', Ticket::class);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'category_id' => 'required|exists:categories,id',
            'priority_id' => 'required|exists:priorities,id',
            'contact_pic' => 'required|string|max:255',
            'target_date' => 'nullable|date|after:today',
            'lampiran'    => 'nullable|array|max:5',
            'lampiran.*'  => ['file', 'max:10240', new SafeFile()],
        ], [
            'title.required'       => 'Judul tiket harus diisi.',
            'description.required' => 'Deskripsi harus diisi.',
            'description.min'      => 'Deskripsi minimal 20 karakter.',
            'category_id.required' => 'Jenis pekerjaan harus dipilih.',
            'priority_id.required' => 'Prioritas harus dipilih.',
            'contact_pic.required' => 'Kontak/PIC harus diisi.',
        ]);

        $ticket = Ticket::create([
            'number'       => Ticket::generateNumber(),
            'title'        => $validated['title'],
            'description'  => $validated['description'],
            'requester_id' => $user->id,
            'department_id'=> $user->department_id,
            'category_id'  => $validated['category_id'],
            'priority_id'  => $validated['priority_id'],
            'contact_pic'  => $validated['contact_pic'],
            'target_date'  => $validated['target_date'] ?? null,
            'status'       => 'baru',
        ]);

        // Simpan lampiran
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $storedName = $file->hashName();
                $path       = $file->storeAs('lampiran', $storedName, 'public');

                TicketAttachment::create([
                    'ticket_id'     => $ticket->id,
                    'user_id'       => $user->id,
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
            'user_id'      => $user->id,
            'action'       => 'created',
            'entity_type'  => 'Ticket',
            'entity_id'    => $ticket->id,
            'entity_name'  => $ticket->number,
            'description'  => "Tiket baru dibuat: {$ticket->number} - {$ticket->title}",
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->userAgent(),
        ]);

        return redirect()->route('tiket.show', $ticket->id)
            ->with('success', "Tiket berhasil diajukan dengan nomor: {$ticket->number}");
    }

    //  Tiket Saya (SKPD) 

    public function myTickets(Request $request)
    {
        $user  = Auth::user();
        $base  = Ticket::where('requester_id', $user->id); // unfiltered base for stats

        $query = Ticket::with(['requester', 'department', 'category', 'priority', 'assignee', 'assignees'])
            ->where('requester_id', $user->id);

        if ($request->filled('status'))      $query->where('status', $request->status);
        if ($request->filled('category_id')) $query->where('category_id', $request->category_id);
        if ($request->filled('priority_id')) $query->where('priority_id', $request->priority_id);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('number', 'like', "%{$search}%")->orWhere('title', 'like', "%{$search}%"));
        }

        $tickets     = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        $skpdList    = Department::aktif()->orderBy('name')->get();
        $petugasList = collect();
        $categories  = Category::aktif()->orderBy('name')->get();
        $priorities  = Priority::ordered()->get();
        $viewMode    = 'saya';

        $stats = [
            'total'               => (clone $base)->count(),
            'baru'                => (clone $base)->where('status', 'baru')->count(),
            'diproses'            => (clone $base)->where('status', 'diproses')->count(),
            'menunggu_verifikasi' => (clone $base)->where('status', 'menunggu_verifikasi')->count(),
            'selesai'             => (clone $base)->where('status', 'selesai')->count(),
        ];

        return view('kominfo.tiket-daftar', compact(
            'tickets', 'skpdList', 'petugasList', 'categories', 'priorities', 'stats', 'viewMode'
        ));
    }

    //  Daftar Tiket 

    public function index(Request $request)
    {
        $user  = Auth::user();
        $this->authorize('viewAny', Ticket::class);

        $query = Ticket::with(['requester', 'department', 'category', 'priority', 'assignee', 'assignees']);

        // Petugas hanya melihat tiket yang ditugaskan kepada mereka
        if ($user->isPetugas()) {
            $query->whereHas('assignees', fn($q) => $q->where('users.id', $user->id));
        }

        // Terapkan filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('priority_id')) {
            $query->where('priority_id', $request->priority_id);
        }
        if ($request->filled('department_id') && ! $user->isSkpd()) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('assignee_id')) {
            // Hanya admin yang bisa filter berdasarkan petugas; petugas sudah di-scope sendiri
            if (! $user->isPetugas()) {
                $query->whereHas('assignees', fn($q) => $q->where('users.id', $request->assignee_id));
            }
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }
        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->dari)->startOfDay(),
                Carbon::parse($request->sampai)->endOfDay(),
            ]);
        }

        // Sorting
        $sort = $request->get('sort', 'terbaru');
        match ($sort) {
            'prioritas' => $query->join('priorities', 'tickets.priority_id', '=', 'priorities.id')
                                 ->orderByDesc('priorities.weight')
                                 ->select('tickets.*'),
            'terlama'   => $query->orderBy('created_at'),
            default     => $query->orderByDesc('created_at'),
        };

        $tickets      = $query->paginate(20)->withQueryString();
        $skpdList     = Department::aktif()->orderBy('name')->get();
        $petugasList  = User::role('petugas')->where('status', 'aktif')
            ->withCount(['assignedTicketsMulti as aktif_count' => fn($q) => $q->whereIn('status', ['baru', 'diproses'])])
            ->orderBy('name')->get();
        $categories   = Category::aktif()->orderBy('name')->get();
        $priorities   = Priority::ordered()->get();
        $viewMode     = 'semua';

        $statsBase = Ticket::query();
        if ($user->isPetugas()) {
            $statsBase->whereHas('assignees', fn($q) => $q->where('users.id', $user->id));
        }

        $stats = [
            'total'               => (clone $statsBase)->count(),
            'baru'                => (clone $statsBase)->where('status', 'baru')->count(),
            'diproses'            => (clone $statsBase)->where('status', 'diproses')->count(),
            'menunggu_verifikasi' => (clone $statsBase)->where('status', 'menunggu_verifikasi')->count(),
            'selesai'             => (clone $statsBase)->where('status', 'selesai')->count(),
        ];

        return view('kominfo.tiket-daftar', compact(
            'tickets', 'skpdList', 'petugasList', 'categories', 'priorities', 'stats', 'viewMode'
        ));
    }

    //  Detail Tiket 

    public function show($id)
    {
        $ticket = Ticket::with([
            'requester', 'department', 'category', 'priority', 'assignee', 'assignees',
            'comments.user', 'attachments.uploader',
        ])->findOrFail($id);

        $user = Auth::user();

        // SKPD hanya boleh lihat tiket departemennya sendiri
        $this->authorize('view', $ticket);


        $petugasList = User::role('petugas')->where('status', 'aktif')
            ->withCount(['assignedTicketsMulti as aktif_count' => fn($q) => $q->whereIn('status', ['baru', 'diproses'])])
            ->orderBy('name')->get();

        // Progress log (petugas punya), komentar publik dipisah
        $progressList = $ticket->comments()->progress()->with('user')->orderBy('created_at')->get();
        $commentList  = $ticket->comments()->public()->with('user')->orderBy('created_at')->get();

        return view('kominfo.tiket-detail', compact('ticket', 'petugasList', 'progressList', 'commentList'));
    }

    //  Update Status 

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        abort_unless($user->isAdmin() || $user->isPetugas(), 403, 'Akses ditolak.');

        $ticket = Ticket::findOrFail($id);

        $request->validate([
            'status'  => 'required|in:baru,diproses,menunggu_verifikasi,selesai,ditolak,dibatalkan',
            'catatan' => 'nullable|string|max:1000',
            'summary' => 'nullable|string',
        ]);

        $oldStatus = $ticket->status;
        $newStatus = $request->status;

        // Petugas dapat set: diproses, menunggu_verifikasi, ditolak
        // Admin (superadmin) dapat set: diproses, selesai (verifikasi), ditolak
        if ($user->isPetugas() && ! $user->isAdmin()) {
            abort_unless(
                in_array($newStatus, ['diproses', 'menunggu_verifikasi', 'ditolak']),
                403, 'Petugas tidak berwenang menetapkan status ini.'
            );
        }
        if ($user->isAdmin() && ! $user->isPetugas()) {
            abort_unless(
                in_array($newStatus, ['diproses', 'selesai', 'ditolak']),
                403, 'Admin tidak dapat menetapkan status ini secara langsung.'
            );
        }

        $updateData = ['status' => $newStatus];

        // Set timestamps berdasarkan status
        if ($newStatus === 'diproses' && ! $ticket->started_at) {
            $updateData['started_at'] = now();
        }
        // closed_at hanya di-set saat benar-benar ditutup (selesai/ditolak/dibatalkan)
        if (in_array($newStatus, ['selesai', 'ditolak', 'dibatalkan'])) {
            $updateData['closed_at'] = now();
            if ($request->filled('summary')) {
                $updateData['summary'] = $request->summary;
            }
        }
        // Simpan ringkasan pekerjaan saat petugas meminta verifikasi
        if ($newStatus === 'menunggu_verifikasi' && $request->filled('summary')) {
            $updateData['summary'] = $request->summary;
        }

        $ticket->update($updateData);

        // Tambah komentar status change
        $body = "Status diubah dari **{$oldStatus}** ke **{$newStatus}**";
        if ($request->filled('catatan')) {
            $body .= "\n\n" . $request->catatan;
        }

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id'   => $user->id,
            'body'      => $body,
            'type'      => 'status_change',
        ]);

        // Audit log
        AuditLog::create([
            'user_id'     => $user->id,
            'action'      => 'status_changed',
            'entity_type' => 'Ticket',
            'entity_id'   => $ticket->id,
            'entity_name' => $ticket->number,
            'old_value'   => ['status' => $oldStatus],
            'new_value'   => ['status' => $newStatus],
            'description' => "Status tiket {$ticket->number} diubah: {$oldStatus}  {$newStatus}",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        $statusLabel = ['baru' => 'Baru', 'diproses' => 'Diproses', 'menunggu_verifikasi' => 'Menunggu Verifikasi', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak', 'dibatalkan' => 'Dibatalkan'];
        $msg = 'Status tiket berhasil diubah ke ' . ($statusLabel[$newStatus] ?? $newStatus);

        return $request->expectsJson()
            ? response()->json(['success' => true, 'message' => $msg])
            : redirect()->route('tiket.show', $ticket->id)->with('success', $msg);
    }

    //  Assign Tiket 

    public function assign(Request $request, $id)
    {
        $user = Auth::user();
        $ticket = Ticket::findOrFail($id);
        $this->authorize('assign', $ticket);

        $request->validate([
            'assignee_ids'   => 'required|array|min:1',
            'assignee_ids.*' => 'exists:users,id',
            'catatan'        => 'nullable|string|max:500',
        ]);

        $assigneeIds = $request->input('assignee_ids');
        $assignees   = User::whereIn('id', $assigneeIds)->get();

        // Sync pivot table
        $syncData = [];
        foreach ($assigneeIds as $uid) {
            $syncData[$uid] = [
                'assigned_by_id' => $user->id,
                'assigned_at'    => now(),
            ];
        }
        $ticket->assignees()->sync($syncData);

        // Keep assignee_id (lead) as first selected for backward compat
        $ticket->update([
            'assignee_id' => $assigneeIds[0],
            'assigned_at' => now(),
            'status'      => $ticket->status === 'baru' ? 'diproses' : $ticket->status,
            'started_at'  => $ticket->started_at ?? now(),
        ]);

        $names = $assignees->pluck('name')->join(', ');
        $body  = "Tiket ditugaskan ke **{$names}**";
        if ($request->filled('catatan')) {
            $body .= "\n\n" . $request->catatan;
        }

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id'   => $user->id,
            'body'      => $body,
            'type'      => 'assignment',
        ]);

        AuditLog::create([
            'user_id'     => $user->id,
            'action'      => 'assigned',
            'entity_type' => 'Ticket',
            'entity_id'   => $ticket->id,
            'entity_name' => $ticket->number,
            'new_value'   => ['assignee_ids' => $assigneeIds, 'assignee_names' => $names],
            'description' => "Tiket {$ticket->number} ditugaskan ke {$names}",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return $request->expectsJson()
            ? response()->json([
                'success'  => true,
                'message'  => "Tiket berhasil ditugaskan ke {$names}",
                'assignees' => $assignees->map(fn($a) => ['id' => $a->id, 'name' => $a->name]),
            ])
            : redirect()->route('tiket.show', $ticket->id)
                ->with('success', "Tiket berhasil ditugaskan ke {$names}");
    }

    public function addComment(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $user   = Auth::user();

        // Gunakan policy untuk otorisasi
        $this->authorize('addComment', $ticket);


        $request->validate([
            'body' => 'required|string|min:3|max:5000',
        ]);

        $comment = TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id'   => $user->id,
            'body'      => $request->body,
            'type'      => 'comment',
        ]);

        AuditLog::create([
            'user_id'     => $user->id,
            'action'      => 'updated',
            'entity_type' => 'Ticket',
            'entity_id'   => $ticket->id,
            'entity_name' => $ticket->number,
            'description' => "Komentar ditambahkan pada tiket {$ticket->number}",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return $request->expectsJson()
            ? response()->json([
                'success' => true,
                'comment' => [
                    'id'         => $comment->id,
                    'body'       => $comment->body,
                    'user_name'  => $user->name,
                    'user_role'  => $user->role,
                    'type'       => $comment->type,
                    'created_at' => $comment->created_at->format('d M Y H:i'),
                ],
            ])
            : redirect()->route('tiket.show', $ticket->id)
                ->with('success', 'Komentar berhasil ditambahkan.');
    }

    //  Progress Pekerjaan (Petugas) 

    public function addProgress(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $user   = Auth::user();

        $this->authorize('addProgress', $ticket);

        // Progress hanya bisa ditambah saat tiket sedang diproses
        abort_unless(
            in_array($ticket->status, ['diproses', 'baru']),
            422, 'Progress hanya bisa ditambahkan pada tiket yang sedang aktif.'
        );

        $request->validate([
            'body' => 'required|string|min:5|max:3000',
        ], [
            'body.required' => 'Isi rincian progress tidak boleh kosong.',
            'body.min'      => 'Rincian progress minimal 5 karakter.',
        ]);

        $progress = TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id'   => $user->id,
            'body'      => $request->body,
            'type'      => 'progress',
        ]);

        AuditLog::create([
            'user_id'     => $user->id,
            'action'      => 'updated',
            'entity_type' => 'Ticket',
            'entity_id'   => $ticket->id,
            'entity_name' => $ticket->number,
            'description' => "Progress ditambahkan pada tiket {$ticket->number} oleh {$user->name}",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return $request->expectsJson()
            ? response()->json([
                'success'  => true,
                'progress' => [
                    'id'         => $progress->id,
                    'body'       => e($progress->body),
                    'user_name'  => $user->name,
                    'user_init'  => strtoupper(substr($user->name, 0, 1)),
                    'created_at' => $progress->created_at->format('d M Y H:i'),
                ],
            ])
            : redirect()->route('tiket.show', $ticket->id)
                ->with('success', 'Progress pekerjaan berhasil ditambahkan.');
    }

    //  Batalkan Tiket (SKPD) 

    public function cancelTicket(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $user   = Auth::user();

        // Gunakan policy cancel
        $this->authorize('cancel', $ticket);

        // Hanya tiket berstatus baru yang bisa dibatalkan
        if ($ticket->status !== 'baru') {
            return back()->with('error', 'Tiket hanya bisa dibatalkan jika masih berstatus Baru.');
        }

        $oldStatus = $ticket->status;
        $ticket->update([
            'status'    => 'dibatalkan',
            'closed_at' => now(),
        ]);

        $catatan = $request->input('catatan', 'Tiket dibatalkan oleh pengaju.');

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id'   => $user->id,
            'body'      => "Status diubah dari **{$oldStatus}** ke **dibatalkan**\n\n{$catatan}",
            'type'      => 'status_change',
        ]);

        AuditLog::create([
            'user_id'     => $user->id,
            'action'      => 'status_changed',
            'entity_type' => 'Ticket',
            'entity_id'   => $ticket->id,
            'entity_name' => $ticket->number,
            'old_value'   => ['status' => $oldStatus],
            'new_value'   => ['status' => 'dibatalkan'],
            'description' => "Tiket {$ticket->number} dibatalkan oleh pengaju.",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return redirect()->route('tiket.saya')
            ->with('success', "Tiket {$ticket->number} berhasil dibatalkan.");
    }

    //  Upload Lampiran 

    public function uploadAttachment(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $user   = Auth::user();

        $this->authorize('uploadAttachment', $ticket);

        $request->validate([
            'lampiran'   => 'required|array|max:5',
            'lampiran.*' => ['file', 'max:10240', new SafeFile()],
        ]);

        $uploaded = [];
        foreach ($request->file('lampiran') as $file) {
            $storedName = $file->hashName();
            $path       = $file->storeAs('lampiran', $storedName, 'public');

            $attachment = TicketAttachment::create([
                'ticket_id'     => $ticket->id,
                'user_id'       => $user->id,
                'original_name' => $file->getClientOriginalName(),
                'stored_name'   => $storedName,
                'path'          => $path,
                'mime_type'     => $file->getMimeType(),
                'size'          => $file->getSize(),
            ]);

            $uploaded[] = [
                'id'            => $attachment->id,
                'original_name' => $attachment->original_name,
                'size'          => $attachment->humanSize(),
            ];
        }

        AuditLog::create([
            'user_id'     => $user->id,
            'action'      => 'updated',
            'entity_type' => 'Ticket',
            'entity_id'   => $ticket->id,
            'entity_name' => $ticket->number,
            'description' => count($uploaded) . " lampiran ditambahkan pada tiket {$ticket->number}",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return response()->json(['success' => true, 'attachments' => $uploaded]);
    }

    //  Download Lampiran 

    public function downloadAttachment($attachmentId)
    {
        $attachment = TicketAttachment::with('ticket')->findOrFail($attachmentId);
        $user       = Auth::user();

        $this->authorize('downloadAttachment', $attachment->ticket);

        if (! Storage::disk('public')->exists($attachment->path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($attachment->path, $attachment->original_name);
    }

    //  Laporan 

    public function laporan(Request $request)
    {
        $user = Auth::user();
        abort_unless(
            $user->isAdmin() || $user->isPetugas() || $user->isPimpinan(),
            403, 'Akses ditolak.'
        );

        $dari    = $request->filled('dari')    ? Carbon::parse($request->dari)->startOfDay()    : now()->startOfMonth();
        $sampai  = $request->filled('sampai')  ? Carbon::parse($request->sampai)->endOfDay()    : now()->endOfMonth();
        $deptId  = $request->get('department_id');
        $catId   = $request->get('category_id');

        $base = Ticket::whereBetween('created_at', [$dari, $sampai]);
        if ($deptId) $base->where('department_id', $deptId);
        if ($catId)  $base->where('category_id', $catId);

        $total   = (clone $base)->count();
        $selesai = (clone $base)->where('status', 'selesai')->count();
        $avg     = (clone $base)->where('status', 'selesai')->whereNotNull('closed_at')->get()
            ->avg(fn($t) => $t->created_at->diffInDays($t->closed_at));

        $summary = [
            'total_tiket'        => $total,
            'tiket_selesai'      => $selesai,
            'persentase_selesai' => $total ? round($selesai / $total * 100) : 0,
            'rata_waktu'         => round($avg ?? 0, 1),
            'backlog'            => (clone $base)->whereIn('status', ['baru', 'diproses'])->count(),
        ];

        $statusDistribution = [
            'baru'       => (clone $base)->where('status', 'baru')->count(),
            'diproses'   => (clone $base)->where('status', 'diproses')->count(),
            'selesai'    => $selesai,
            'ditolak'    => (clone $base)->where('status', 'ditolak')->count(),
            'dibatalkan' => (clone $base)->where('status', 'dibatalkan')->count(),
        ];

        // Rekap per SKPD
        $skpdReport = Department::withCount([
            'tickets as total'   => fn($q) => $q->whereBetween('created_at', [$dari, $sampai]),
            'tickets as selesai' => fn($q) => $q->whereBetween('created_at', [$dari, $sampai])->where('status', 'selesai'),
        ])->having('total', '>', 0)->orderByDesc('total')->get()
            ->map(fn($d) => [
                'nama'       => $d->name,
                'total'      => $d->total,
                'selesai'    => $d->selesai,
                'persentase' => $d->total ? round($d->selesai / $d->total * 100) : 0,
            ])->toArray();

        // Rekap per kategori
        $jenisReport = Category::withCount([
            'tickets as jumlah' => fn($q) => $q->whereBetween('created_at', [$dari, $sampai]),
        ])->having('jumlah', '>', 0)->orderByDesc('jumlah')->get()
            ->map(fn($c) => [
                'nama'       => $c->name,
                'jumlah'     => $c->jumlah,
                'persentase' => $total ? round($c->jumlah / $total * 100) : 0,
            ])->toArray();

        $skpdList   = Department::aktif()->orderBy('name')->get();
        $categories = Category::aktif()->orderBy('name')->get();

        // Statistik petugas — hanya dibutuhkan oleh admin & pimpinan
        $petugasStats = null;
        if ($user->isAdmin() || $user->isPimpinan()) {
            $petugasStats = User::role('petugas')
                ->withCount([
                    'assignedTickets as total_assigned' => fn($q) => $q->whereBetween('created_at', [$dari, $sampai]),
                    'assignedTickets as total_selesai'  => fn($q) => $q->whereBetween('created_at', [$dari, $sampai])->where('status', 'selesai'),
                ])
                ->orderByDesc('total_assigned')
                ->get();
        }

        return view('kominfo.laporan', compact(
            'summary', 'statusDistribution', 'skpdReport', 'jenisReport',
            'skpdList', 'categories', 'dari', 'sampai', 'petugasStats'
        ));
    }

    //  Export CSV 

    public function exportCsv(Request $request)
    {
        $user = Auth::user();
        abort_unless(
            $user->isAdmin() || $user->isPimpinan(),
            403, 'Akses ditolak.'
        );

        $dari   = $request->filled('dari')   ? Carbon::parse($request->dari)->startOfDay()  : now()->startOfMonth();
        $sampai = $request->filled('sampai') ? Carbon::parse($request->sampai)->endOfDay()  : now()->endOfMonth();

        $tickets = Ticket::with(['department', 'category', 'priority', 'requester', 'assignee'])
            ->whereBetween('created_at', [$dari, $sampai])
            ->orderByDesc('created_at')
            ->get();

        $filename = 'laporan-tiket-' . now()->format('Y-m-d') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($tickets) {
            $handle = fopen('php://output', 'w');
            // BOM untuk Excel UTF-8
            fputs($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['No. Tiket', 'Judul', 'SKPD', 'Kategori', 'Prioritas', 'Status', 'Pemohon', 'Petugas', 'Tgl Dibuat', 'Tgl Selesai', 'Durasi (hari)']);

            foreach ($tickets as $t) {
                fputcsv($handle, [
                    $t->number,
                    $t->title,
                    $t->department->name ?? '-',
                    $t->category->name   ?? '-',
                    $t->priority->name   ?? '-',
                    $t->statusLabel(),
                    $t->requester->name  ?? '-',
                    $t->assignee->name   ?? '-',
                    $t->created_at->format('d/m/Y'),
                    $t->closed_at        ? $t->closed_at->format('d/m/Y') : '-',
                    $t->resolutionDays() ?? '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
