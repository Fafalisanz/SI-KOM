<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil data statistik riil dari database
        $totalBarang = Product::sum('stock') ?? 0;
        $totalKategori = Category::count() ?? 0;
        
        // Menghitung barang yang sedang dipinjam (bisa disesuaikan nanti)
        $barangDipinjam = Borrowing::where('status', 'borrowed')->count() ?? 0;
        
        // Barang tersedia = sisa barang yang kondisinya bagus
        $barangTersedia = Product::where('condition', 'Bagus')->sum('stock') ?? 0;

        return view('dashboard', compact('totalBarang', 'totalKategori', 'barangDipinjam', 'barangTersedia'));
    }
}