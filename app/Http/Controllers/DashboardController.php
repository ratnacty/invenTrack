<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', [
            'stats' => [
                'totalBarang'   => Barang::count(),
                'totalKategori' => Kategori::count(),
                'totalUser'     => User::count(),
                'totalStok'     => (int) Barang::sum('stok'),
                'totalNilai'    => (float) Barang::selectRaw('COALESCE(SUM(harga * stok), 0) as total')->value('total'),
            ],
            'recentBarangs' => Barang::with('kategori')
                ->latest()
                ->take(5)
                ->get(['id', 'nama', 'harga', 'stok', 'kategori_id', 'created_at']),
            'stokRendah' => Barang::with('kategori')
                ->where('stok', '<=', 5)
                ->orderBy('stok')
                ->take(10)
                ->get(['id', 'nama', 'stok', 'kategori_id']),
        ]);
    }
}
