<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class TicketingController extends Controller
{
    /**
     * Menampilkan halaman beranda
     */
    public function home()
    {
        // Data dummy untuk event populer
        $featuredEvents = [
            [
                'id' => 1,
                'title' => 'Konser Musik Jazz Festival 2025',
                'date' => '2025-01-20',
                'time' => '19:00',
                'location' => 'Jakarta Convention Center',
                'price' => 350000,
                'image' => 'https://via.placeholder.com/400x200/6366f1/ffffff?text=Jazz+Festival',
                'category' => 'Musik',
                'available_tickets' => 150
            ],
            [
                'id' => 2,
                'title' => 'Workshop Digital Marketing 2025',
                'date' => '2025-01-25',
                'time' => '09:00',
                'location' => 'Balai Kartini Jakarta',
                'price' => 150000,
                'image' => 'https://via.placeholder.com/400x200/10b981/ffffff?text=Digital+Marketing',
                'category' => 'Edukasi',
                'available_tickets' => 80
            ],
            [
                'id' => 3,
                'title' => 'Stand Up Comedy Night',
                'date' => '2025-02-01',
                'time' => '20:00',
                'location' => 'Taman Ismail Marzuki',
                'price' => 125000,
                'image' => 'https://via.placeholder.com/400x200/f59e0b/ffffff?text=Comedy+Night',
                'category' => 'Hiburan',
                'available_tickets' => 200
            ]
        ];

        return view('pages.home', compact('featuredEvents'));
    }

    /**
     * Menampilkan halaman daftar events
     */
    public function events(Request $request)
    {
        // Data dummy untuk semua events
        $events = [
            [
                'id' => 1,
                'title' => 'Konser Musik Jazz Festival 2025',
                'date' => '2025-01-20',
                'time' => '19:00',
                'location' => 'Jakarta Convention Center',
                'price' => 350000,
                'image' => 'https://via.placeholder.com/400x200/6366f1/ffffff?text=Jazz+Festival',
                'category' => 'Musik',
                'available_tickets' => 150,
                'description' => 'Festival jazz terbesar di Indonesia dengan artis internasional dan lokal terbaik.'
            ],
            [
                'id' => 2,
                'title' => 'Workshop Digital Marketing 2025',
                'date' => '2025-01-25',
                'time' => '09:00',
                'location' => 'Balai Kartini Jakarta',
                'price' => 150000,
                'image' => 'https://via.placeholder.com/400x200/10b981/ffffff?text=Digital+Marketing',
                'category' => 'Edukasi',
                'available_tickets' => 80,
                'description' => 'Pelajari strategi digital marketing terbaru untuk mengembangkan bisnis Anda.'
            ],
            [
                'id' => 3,
                'title' => 'Stand Up Comedy Night',
                'date' => '2025-02-01',
                'time' => '20:00',
                'location' => 'Taman Ismail Marzuki',
                'price' => 125000,
                'image' => 'https://via.placeholder.com/400x200/f59e0b/ffffff?text=Comedy+Night',
                'category' => 'Hiburan',
                'available_tickets' => 200,
                'description' => 'Malam penuh tawa dengan komika-komika terbaik Indonesia.'
            ],
            [
                'id' => 4,
                'title' => 'Seminar Teknologi AI & ML',
                'date' => '2025-02-10',
                'time' => '13:00',
                'location' => 'Universitas Indonesia',
                'price' => 200000,
                'image' => 'https://via.placeholder.com/400x200/ef4444/ffffff?text=AI+Seminar',
                'category' => 'Teknologi',
                'available_tickets' => 120,
                'description' => 'Seminar tentang perkembangan terbaru Artificial Intelligence dan Machine Learning.'
            ],
            [
                'id' => 5,
                'title' => 'Festival Kuliner Nusantara',
                'date' => '2025-02-15',
                'time' => '10:00',
                'location' => 'Monas Jakarta',
                'price' => 75000,
                'image' => 'https://via.placeholder.com/400x200/8b5cf6/ffffff?text=Food+Festival',
                'category' => 'Kuliner',
                'available_tickets' => 500,
                'description' => 'Festival kuliner dengan berbagai makanan khas dari seluruh Indonesia.'
            ],
            [
                'id' => 6,
                'title' => 'Pameran Seni Rupa Modern',
                'date' => '2025-02-20',
                'time' => '10:00',
                'location' => 'Galeri Nasional Indonesia',
                'price' => 50000,
                'image' => 'https://via.placeholder.com/400x200/06b6d4/ffffff?text=Art+Exhibition',
                'category' => 'Seni',
                'available_tickets' => 300,
                'description' => 'Pameran karya seni rupa modern dari seniman Indonesia dan mancanegara.'
            ]
        ];

        // Filter berdasarkan kategori jika ada
        $selectedCategory = $request->get('category');
        if ($selectedCategory && $selectedCategory !== 'all') {
            $events = array_filter($events, function($event) use ($selectedCategory) {
                return strtolower($event['category']) === strtolower($selectedCategory);
            });
        }

        // Filter berdasarkan pencarian
        $search = $request->get('search');
        if ($search) {
            $events = array_filter($events, function($event) use ($search) {
                return stripos($event['title'], $search) !== false || 
                       stripos($event['description'], $search) !== false;
            });
        }

        $categories = ['Musik', 'Edukasi', 'Hiburan', 'Teknologi', 'Kuliner', 'Seni'];

        return view('pages.events', compact('events', 'categories', 'selectedCategory', 'search'));
    }

    /**
     * Menampilkan detail event
     */
    public function eventDetail($id)
    {
        // Data dummy untuk detail event
        $events = [
            1 => [
                'id' => 1,
                'title' => 'Konser Musik Jazz Festival 2025',
                'date' => '2025-01-20',
                'time' => '19:00',
                'location' => 'Jakarta Convention Center',
                'address' => 'Jl. Gatot Subroto Kav. 52-53, Jakarta Selatan',
                'price' => 350000,
                'image' => 'https://via.placeholder.com/800x400/6366f1/ffffff?text=Jazz+Festival',
                'category' => 'Musik',
                'available_tickets' => 150,
                'total_tickets' => 500,
                'description' => 'Festival jazz terbesar di Indonesia tahun 2025! Nikmati penampilan dari artis jazz internasional dan lokal terbaik. Acara ini akan menampilkan berbagai genre jazz dari tradisional hingga kontemporer.',
                'organizer' => 'Jazz Indonesia Foundation',
                'contact' => '+62 812-3456-7890',
                'features' => ['Live Performance', 'Food Court', 'Merchandise', 'VIP Lounge'],
                'ticket_types' => [
                    ['type' => 'Regular', 'price' => 350000, 'benefits' => ['Standing Area', 'Free Welcome Drink']],
                    ['type' => 'VIP', 'price' => 650000, 'benefits' => ['VIP Seating', 'Free Dinner', 'Meet & Greet']],
                    ['type' => 'VVIP', 'price' => 1200000, 'benefits' => ['Front Row Seating', 'Premium Dinner', 'Photo Session', 'Exclusive Merchandise']]
                ]
            ],
            2 => [
                'id' => 2,
                'title' => 'Workshop Digital Marketing 2025',
                'date' => '2025-01-25',
                'time' => '09:00',
                'location' => 'Balai Kartini Jakarta',
                'address' => 'Jl. Gatot Subroto Kav. 37, Jakarta Selatan',
                'price' => 150000,
                'image' => 'https://via.placeholder.com/800x400/10b981/ffffff?text=Digital+Marketing',
                'category' => 'Edukasi',
                'available_tickets' => 80,
                'total_tickets' => 100,
                'description' => 'Workshop intensif tentang strategi digital marketing terbaru. Pelajari cara mengoptimalkan media sosial, SEO, SEM, dan analytics untuk mengembangkan bisnis Anda di era digital.',
                'organizer' => 'Digital Marketing Institute',
                'contact' => '+62 812-3456-7891',
                'features' => ['Hands-on Training', 'Certificate', 'Networking Session', 'Free Materials'],
                'ticket_types' => [
                    ['type' => 'Early Bird', 'price' => 150000, 'benefits' => ['Workshop Materials', 'Certificate', 'Lunch']],
                    ['type' => 'Regular', 'price' => 200000, 'benefits' => ['Workshop Materials', 'Certificate', 'Lunch']]
                ]
            ]
        ];

        $event = $events[$id] ?? null;
        
        if (!$event) {
            abort(404);
        }

        return view('pages.event-detail', compact('event'));
    }

    /**
     * Menampilkan halaman pembelian tiket
     */
    public function purchaseTicket($id)
    {
        // Mendapatkan data event (sama seperti di eventDetail)
        $events = [
            1 => [
                'id' => 1,
                'title' => 'Konser Musik Jazz Festival 2025',
                'date' => '2025-01-20',
                'time' => '19:00',
                'location' => 'Jakarta Convention Center',
                'price' => 350000,
                'image' => 'https://via.placeholder.com/400x300/6366f1/ffffff?text=Jazz+Festival',
                'category' => 'Musik',
                'available_tickets' => 150,
                'ticket_types' => [
                    ['type' => 'Regular', 'price' => 350000, 'benefits' => ['Standing Area', 'Free Welcome Drink']],
                    ['type' => 'VIP', 'price' => 650000, 'benefits' => ['VIP Seating', 'Free Dinner', 'Meet & Greet']],
                    ['type' => 'VVIP', 'price' => 1200000, 'benefits' => ['Front Row Seating', 'Premium Dinner', 'Photo Session']]
                ]
            ],
            2 => [
                'id' => 2,
                'title' => 'Workshop Digital Marketing 2025',
                'date' => '2025-01-25',
                'time' => '09:00',
                'location' => 'Balai Kartini Jakarta',
                'price' => 150000,
                'image' => 'https://via.placeholder.com/400x300/10b981/ffffff?text=Digital+Marketing',
                'category' => 'Edukasi',
                'available_tickets' => 80,
                'ticket_types' => [
                    ['type' => 'Early Bird', 'price' => 150000, 'benefits' => ['Workshop Materials', 'Certificate']],
                    ['type' => 'Regular', 'price' => 200000, 'benefits' => ['Workshop Materials', 'Certificate']]
                ]
            ]
        ];

        $event = $events[$id] ?? null;
        
        if (!$event) {
            abort(404);
        }

        return view('pages.purchase-ticket', compact('event'));
    }

    /**
     * Menampilkan halaman tiket saya
     */
    public function myTickets()
    {
        // Data dummy untuk tiket yang dimiliki user
        $tickets = [
            [
                'id' => 'TKT001',
                'event_title' => 'Konser Musik Jazz Festival 2025',
                'date' => '2025-01-20',
                'time' => '19:00',
                'location' => 'Jakarta Convention Center',
                'ticket_type' => 'VIP',
                'quantity' => 2,
                'total_price' => 1300000,
                'purchase_date' => '2024-12-15',
                'status' => 'Active',
                'qr_code' => 'https://via.placeholder.com/150x150/000000/ffffff?text=QR+CODE'
            ],
            [
                'id' => 'TKT002',
                'event_title' => 'Workshop Digital Marketing 2025',
                'date' => '2025-01-25',
                'time' => '09:00',
                'location' => 'Balai Kartini Jakarta',
                'ticket_type' => 'Early Bird',
                'quantity' => 1,
                'total_price' => 150000,
                'purchase_date' => '2024-12-10',
                'status' => 'Active',
                'qr_code' => 'https://via.placeholder.com/150x150/000000/ffffff?text=QR+CODE'
            ],
            [
                'id' => 'TKT003',
                'event_title' => 'Stand Up Comedy Night',
                'date' => '2024-11-15',
                'time' => '20:00',
                'location' => 'Taman Ismail Marzuki',
                'ticket_type' => 'Regular',
                'quantity' => 3,
                'total_price' => 375000,
                'purchase_date' => '2024-11-01',
                'status' => 'Used',
                'qr_code' => 'https://via.placeholder.com/150x150/000000/ffffff?text=QR+CODE'
            ]
        ];

        return view('pages.my-tickets', compact('tickets'));
    }

    /**
     * Menampilkan halaman kontak
     */
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * Memproses form kontak
     */
    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        // Di sini biasanya akan ada proses pengiriman email atau penyimpanan ke database
        // Untuk demo, kita hanya akan redirect dengan pesan sukses
        
        return redirect()->route('contact')->with('success', 'Pesan Anda telah terkirim! Kami akan merespons dalam 1x24 jam.');
    }

    /**
     * Memproses pembelian tiket
     */
    public function processTicketPurchase(Request $request, $id)
    {
        $request->validate([
            'ticket_type' => 'required|string',
            'quantity' => 'required|integer|min:1|max:10',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20'
        ]);

        // Di sini biasanya akan ada proses pembayaran dan penyimpanan ke database
        // Untuk demo, kita akan redirect ke halaman konfirmasi
        
        $ticketId = 'TKT' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        
        return redirect()->route('my-tickets')->with('success', 'Pembelian tiket berhasil! ID Tiket: ' . $ticketId);
    }
}