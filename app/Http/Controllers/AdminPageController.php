<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Department;
use App\Models\Priority;
use App\Models\Setting;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class AdminPageController extends Controller
{
    //  Dashboard Admin 

    public function dashboard()
    {
        $stats = [
            ['label' => 'Total Pengguna',  'nilai' => User::count(),                              'icon' => 'bi-people',         'color' => 'primary', 'change' => User::where('created_at', '>=', now()->subWeek())->count() . ' minggu ini'],
            ['label' => 'Total SKPD',      'nilai' => Department::aktif()->count(),                'icon' => 'bi-building',        'color' => 'info',    'change' => 'Aktif: ' . Department::aktif()->count()],
            ['label' => 'Total Tiket',     'nilai' => Ticket::count(),                             'icon' => 'bi-ticket',          'color' => 'success', 'change' => '+' . Ticket::where('created_at', '>=', now()->subWeek())->count() . ' minggu ini'],
            ['label' => 'Tiket Pending',   'nilai' => Ticket::whereIn('status', ['baru','diproses'])->count(), 'icon' => 'bi-hourglass-bottom', 'color' => 'warning', 'change' => Ticket::where('status','baru')->count() . ' belum diassign'],
        ];

        $recentActivities = AuditLog::with('user')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
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
                    'logout'         => 'bi-box-arrow-right',
                    default          => 'bi-activity',
                },
                'color'  => match($log->action) {
                    'created'   => 'success',
                    'updated'   => 'info',
                    'login'     => 'primary',
                    'assigned'  => 'warning',
                    default     => 'secondary',
                },
            ])->toArray();

        return view('pages.admin.dashboard', compact('stats', 'recentActivities'));
    }

    //  Pengguna 

    public function pengguna()
    {
        $users       = User::with(['department', 'roles'])->orderBy('name')->paginate(20);
        $departments = Department::aktif()->orderBy('name')->get();
        $roles       = Role::withCount('users')->orderBy('name')->get();
        return view('pages.admin.pengguna', compact('users', 'departments', 'roles'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => ['required', 'confirmed', Password::min(8)->letters()->numbers()->mixedCase()],
            'role'          => 'required|string|exists:roles,name',
            'department_id' => 'nullable|exists:departments,id',
            'status'        => 'required|in:aktif,nonaktif',
        ]);

        // Petugas dan Pimpinan selalu tergabung dalam departemen Kominfo
        $departmentId = $validated['department_id'] ?? null;
        if (in_array($validated['role'], ['petugas', 'pimpinan'])) {
            $kominfoDept  = Department::where('code', 'KOMINFO')->first();
            $departmentId = $kominfoDept?->id;
        }

        $user = User::create([
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'password'      => Hash::make($validated['password']),
            'role'          => $validated['role'],
            'department_id' => $departmentId,
            'status'        => $validated['status'],
        ]);

        // Assign Spatie role
        $user->syncRoles([$validated['role']]);

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'created',
            'entity_type' => 'User',
            'entity_id'   => $user->id,
            'entity_name' => $user->name,
            'description' => "Pengguna baru dibuat: {$user->name} ({$user->role})",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return redirect()->route('admin.pengguna')->with('success', "Pengguna {$user->name} berhasil ditambahkan.");
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => "required|email|unique:users,email,{$id}",
            'role'          => 'required|string|exists:roles,name',
            'department_id' => 'nullable|exists:departments,id',
            'status'        => 'required|in:aktif,nonaktif',
            'password'      => ['nullable', 'confirmed', Password::min(8)->letters()->numbers()->mixedCase()],
        ]);

        // Petugas dan Pimpinan selalu tergabung dalam departemen Kominfo
        $departmentId = $validated['department_id'] ?? null;
        if (in_array($validated['role'], ['petugas', 'pimpinan'])) {
            $kominfoDept  = Department::where('code', 'KOMINFO')->first();
            $departmentId = $kominfoDept?->id;
        }

        $updateData = [
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'role'          => $validated['role'],
            'department_id' => $departmentId,
            'status'        => $validated['status'],
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        // Sync Spatie role
        $user->syncRoles([$validated['role']]);

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'updated',
            'entity_type' => 'User',
            'entity_id'   => $user->id,
            'entity_name' => $user->name,
            'description' => "Data pengguna diperbarui: {$user->name}",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return redirect()->route('admin.pengguna')->with('success', "Data pengguna {$user->name} berhasil diperbarui.");
    }

    public function destroyUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->route('admin.pengguna')->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }

        $name = $user->name;
        $user->delete();

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'deleted',
            'entity_type' => 'User',
            'entity_id'   => $id,
            'entity_name' => $name,
            'description' => "Pengguna dihapus: {$name}",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return redirect()->route('admin.pengguna')->with('success', "Pengguna {$name} berhasil dihapus.");
    }

    //  SKPD 

    public function skpd()
    {
        $departments = Department::withCount('users', 'tickets')->orderBy('name')->paginate(20);
        return view('pages.admin.skpd', compact('departments'));
    }

    public function storeDepartment(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'code'    => 'required|string|max:50|unique:departments,code',
            'contact' => 'nullable|string|max:100',
            'head'    => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'status'  => 'required|in:aktif,nonaktif',
        ]);

        $dept = Department::create($validated);

        AuditLog::create([
            'user_id' => Auth::id(), 'action' => 'created', 'entity_type' => 'Department',
            'entity_id' => $dept->id, 'entity_name' => $dept->name,
            'description' => "SKPD baru: {$dept->name}", 'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.skpd')->with('success', "SKPD {$dept->name} berhasil ditambahkan.");
    }

    public function updateDepartment(Request $request, $id)
    {
        $dept = Department::findOrFail($id);

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'code'    => "required|string|max:50|unique:departments,code,{$id}",
            'contact' => 'nullable|string|max:100',
            'head'    => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'status'  => 'required|in:aktif,nonaktif',
        ]);

        $dept->update($validated);

        AuditLog::create([
            'user_id' => Auth::id(), 'action' => 'updated', 'entity_type' => 'Department',
            'entity_id' => $dept->id, 'entity_name' => $dept->name,
            'description' => "SKPD diperbarui: {$dept->name}", 'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.skpd')->with('success', "SKPD {$dept->name} berhasil diperbarui.");
    }

    public function destroyDepartment(Request $request, $id)
    {
        $dept = Department::findOrFail($id);
        $name = $dept->name;
        $dept->delete();

        AuditLog::create([
            'user_id' => Auth::id(), 'action' => 'deleted', 'entity_type' => 'Department',
            'entity_id' => $id, 'entity_name' => $name,
            'description' => "SKPD dihapus: {$name}", 'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.skpd')->with('success', "SKPD {$name} berhasil dihapus.");
    }

    //  Jenis Pekerjaan 

    public function jenisPekerjaan()
    {
        $categories = Category::withCount('tickets')->orderBy('name')->paginate(20);
        $priorities = Priority::ordered()->get();
        return view('pages.admin.jenis-pekerjaan', compact('categories', 'priorities'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'status'      => 'required|in:aktif,nonaktif',
        ]);

        $cat = Category::create($validated);

        AuditLog::create([
            'user_id' => Auth::id(), 'action' => 'created', 'entity_type' => 'Category',
            'entity_id' => $cat->id, 'entity_name' => $cat->name,
            'description' => "Kategori baru: {$cat->name}", 'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.jenis-pekerjaan')->with('success', "Kategori {$cat->name} berhasil ditambahkan.");
    }

    public function updateCategory(Request $request, $id)
    {
        $cat = Category::findOrFail($id);

        $validated = $request->validate([
            'name'        => "required|string|max:255|unique:categories,name,{$id}",
            'description' => 'nullable|string',
            'status'      => 'required|in:aktif,nonaktif',
        ]);

        $cat->update($validated);

        AuditLog::create([
            'user_id' => Auth::id(), 'action' => 'updated', 'entity_type' => 'Category',
            'entity_id' => $cat->id, 'entity_name' => $cat->name,
            'description' => "Kategori diperbarui: {$cat->name}", 'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.jenis-pekerjaan')->with('success', "Kategori {$cat->name} berhasil diperbarui.");
    }

    public function destroyCategory(Request $request, $id)
    {
        $cat = Category::findOrFail($id);
        $name = $cat->name;
        $cat->delete();

        AuditLog::create([
            'user_id' => Auth::id(), 'action' => 'deleted', 'entity_type' => 'Category',
            'entity_id' => $id, 'entity_name' => $name,
            'description' => "Kategori dihapus: {$name}", 'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.jenis-pekerjaan')->with('success', "Kategori {$name} berhasil dihapus.");
    }

    //  Pengaturan 

    public function pengaturan()
    {
        $settings = Setting::all()->keyBy('key');
        return view('pages.admin.pengaturan', compact('settings'));
    }

    public function savePengaturan(Request $request)
    {
        $allowed = [
            'app_name', 'app_description', 'app_institution',
            'max_upload_size', 'allowed_mimetypes',
            'mail_from_name', 'mail_from_address', 'smtp_host', 'smtp_port',
            // Hubungi Kami
            'contact_phone', 'contact_email', 'contact_address', 'contact_hours',
            'contact_social_facebook', 'contact_social_twitter',
            'contact_social_instagram', 'contact_social_youtube',
        ];

        foreach ($allowed as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->get($key));
            }
        }

        // Simpan daftar departemen sebagai JSON
        if ($request->has('contact_departments')) {
            $raw  = $request->input('contact_departments');
            $data = is_string($raw) ? json_decode($raw, true) : $raw;
            Setting::set('contact_departments', is_array($data) ? json_encode($data) : '[]', 'json');
        }

        AuditLog::create([
            'user_id' => Auth::id(), 'action' => 'updated', 'entity_type' => 'Setting',
            'entity_id' => 0, 'entity_name' => 'Pengaturan Sistem',
            'description' => 'Pengaturan sistem diperbarui', 'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.pengaturan')->with('success', 'Pengaturan berhasil disimpan.');
    }

    //  Log Aktivitas 

    public function logAktivitas(Request $request)
    {
        $query = AuditLog::with('user')->orderByDesc('created_at');

        if ($request->filled('user_id'))     $query->where('user_id', $request->user_id);
        if ($request->filled('entity_type')) $query->where('entity_type', $request->entity_type);
        if ($request->filled('action'))      $query->where('action', $request->action);
        if ($request->filled('dari'))        $query->where('created_at', '>=', Carbon::parse($request->dari)->startOfDay());
        if ($request->filled('sampai'))      $query->where('created_at', '<=', Carbon::parse($request->sampai)->endOfDay());

        $logs      = $query->paginate(50)->withQueryString();
        $users     = User::orderBy('name')->get();
        $entityTypes = AuditLog::distinct()->pluck('entity_type');

        return view('pages.admin.log-aktivitas', compact('logs', 'users', 'entityTypes'));
    }

    //  Laporan Admin 

    public function laporan()
    {
        $dari   = now()->startOfMonth();
        $sampai = now()->endOfMonth();

        $total    = Ticket::whereBetween('created_at', [$dari, $sampai])->count();
        $selesai  = Ticket::whereBetween('created_at', [$dari, $sampai])->where('status', 'selesai')->count();
        $diproses = Ticket::whereBetween('created_at', [$dari, $sampai])->where('status', 'diproses')->count();
        $baru     = Ticket::whereBetween('created_at', [$dari, $sampai])->where('status', 'baru')->count();

        // Rata-rata waktu penyelesaian
        $closedTickets = Ticket::whereNotNull('closed_at')
            ->whereBetween('created_at', [$dari, $sampai])
            ->get();
        $rataWaktu = $closedTickets->count()
            ? round($closedTickets->avg(fn($t) => $t->created_at->diffInDays($t->closed_at)), 1)
            : 0;

        $reportData = [
            'total_tiket'             => $total ?: 0,
            'tiket_selesai'           => $selesai,
            'tiket_diproses'          => $diproses,
            'tiket_baru'              => $baru,
            'persentase_selesai'      => $total ? round($selesai / $total * 100) : 0,
            'rata_waktu_penyelesaian' => $rataWaktu,
            'kepuasan_pengguna'       => 0,
            'periode'                 => $dari->format('F Y'),
        ];

        // Top SKPD by ticket count
        $topSkpd = Department::withCount([
            'tickets as tiket_count' => fn($q) => $q->whereBetween('created_at', [$dari, $sampai]),
            'tickets as selesai_count' => fn($q) => $q->whereBetween('created_at', [$dari, $sampai])->where('status', 'selesai'),
        ])->orderByDesc('tiket_count')->limit(10)->get()
        ->map(fn($d) => ['skpd' => $d->name, 'tiket' => $d->tiket_count ?: 0, 'selesai' => $d->selesai_count ?: 0])
        ->toArray();

        // Top Jenis Pekerjaan
        $topJenis = Category::withCount([
            'tickets as tiket_count' => fn($q) => $q->whereBetween('created_at', [$dari, $sampai]),
        ])->orderByDesc('tiket_count')->limit(10)->get()
        ->map(fn($c) => ['jenis' => $c->name, 'tiket' => $c->tiket_count ?: 0])
        ->toArray();

        $petugasStats = User::role('petugas')
            ->withCount([
                'assignedTickets as total_assigned',
                'assignedTickets as total_selesai' => fn($q) => $q->where('status', 'selesai'),
            ])
            ->orderByDesc('total_assigned')
            ->get();

        return view('pages.admin.laporan', compact('reportData', 'topSkpd', 'topJenis', 'petugasStats', 'dari', 'sampai'));
    }
}
