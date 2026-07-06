<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalBarang = Product::count();
        $barangTersedia = Product::where('stock', '>', 0)->count();
        $barangDipinjam = Borrowing::where('status', 'borrowed')->count();
        $totalKategori = Category::count();

        // === DATA GRAFIK: Peminjaman per Bulan (tahun berjalan) ===
        $tahunIni = now()->year;

        $peminjamanPerBulanRaw = Borrowing::selectRaw('MONTH(borrow_date) as bulan, COUNT(*) as total')
            ->whereYear('borrow_date', $tahunIni)
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $namaBulan = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        $chartLabels = [];
        $chartData = [];

        foreach ($namaBulan as $angkaBulan => $label) {
            $chartLabels[] = $label;
            $chartData[] = $peminjamanPerBulanRaw[$angkaBulan] ?? 0;
        }

        // === AKTIVITAS TERBARU: 5 transaksi peminjaman terakhir ===
        $aktivitasTerbaru = Borrowing::with('details.product')
            ->latest()
            ->take(5)
            ->get();

        // === STOK MENIPIS: barang dengan stok <= 5 (bonus fitur) ===
        $stokMenipis = Product::where('stock', '<=', 5)->count();

        return view('dashboard', compact(
            'totalBarang',
            'barangTersedia',
            'barangDipinjam',
            'totalKategori',
            'chartLabels',
            'chartData',
            'aktivitasTerbaru',
            'stokMenipis'
        ));
    }
}