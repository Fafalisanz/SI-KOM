<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        [$startDate, $endDate, $products, $borrowings, $summary] = $this->buildReportData($request);

        return view('reports.index', compact('products', 'borrowings', 'summary', 'startDate', 'endDate'));
    }

    public function exportPdf(Request $request): Response
    {
        [$startDate, $endDate, $products, $borrowings, $summary] = $this->buildReportData($request);

        $pdf = Pdf::loadView('reports.pdf', compact('products', 'borrowings', 'summary', 'startDate', 'endDate'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-inventaris-' . now()->format('Y-m-d') . '.pdf');
    }

    private function buildReportData(Request $request): array
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $products = Product::with('category')->orderBy('name')->get();

        $borrowings = Borrowing::with(['details.product', 'user'])
            ->whereBetween('borrow_date', [$startDate, $endDate])
            ->latest()
            ->get();

        $summary = [
            'total_borrowings' => $borrowings->count(),
            'returned' => $borrowings->where('status', 'returned')->count(),
            'borrowed' => $borrowings->where('status', 'borrowed')->count(),
            'overdue' => $borrowings->where('status', 'overdue')->count(),
            'pending' => $borrowings->where('status', 'pending')->count(),
            'total_stok' => $products->sum('stock'),
            'total_item' => $products->count(),
        ];

        return [$startDate, $endDate, $products, $borrowings, $summary];
    }
}