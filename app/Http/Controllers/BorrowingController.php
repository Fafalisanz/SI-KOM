<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Borrowing::with(['details.product', 'user']);

        if ($request->filled('search')) {
            $query->where('borrower_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->latest()->paginate(10)->withQueryString();

        return view('borrowings.index', compact('borrowings'));
    }

    public function create(): View
    {
        // Hanya tampilkan produk yang stoknya masih ada
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();

        return view('borrowings.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'borrower_name' => 'required|string|max:255',
            'borrow_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
        ], [
            'items.required' => 'Pilih minimal 1 barang untuk dipinjam.',
        ]);

        // Validasi stok cukup untuk setiap barang yang dipilih
        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            if ($product->stock < $item['qty']) {
                return back()->withInput()->with(
                    'error',
                    "Stok \"{$product->name}\" tidak cukup. Sisa stok: {$product->stock}."
                );
            }
        }

        DB::transaction(function () use ($validated) {
            $borrowing = Borrowing::create([
                'user_id' => auth()->id(),
                'borrower_name' => $validated['borrower_name'],
                'borrow_date' => $validated['borrow_date'],
                'status' => 'borrowed',
            ]);

            foreach ($validated['items'] as $item) {
                $borrowing->details()->create([
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'item_status' => 'Dipinjam',
                ]);

                Product::find($item['product_id'])->decrement('stock', $item['qty']);
            }
        });

        return redirect()->route('borrowings.index')->with('success', 'Peminjaman berhasil dicatat.');
    }

    public function show(Borrowing $borrowing): View
    {
        $borrowing->load(['details.product', 'user']);

        return view('borrowings.show', compact('borrowing'));
    }

    public function edit(Borrowing $borrowing): View
    {
        return view('borrowings.edit', compact('borrowing'));
    }

    public function update(Request $request, Borrowing $borrowing): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,borrowed,returned,overdue',
            'return_date' => 'nullable|date',
        ]);

        $borrowing->update($validated);

        return redirect()->route('borrowings.index')->with('success', 'Status peminjaman berhasil diperbarui.');
    }

    /**
     * Proses pengembalian barang: set status returned, isi tanggal kembali,
     * dan kembalikan stok produk terkait.
     */
    public function returnItem(Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->status === 'returned') {
            return redirect()->route('borrowings.index')->with('error', 'Peminjaman ini sudah dikembalikan sebelumnya.');
        }

        DB::transaction(function () use ($borrowing) {
            foreach ($borrowing->details as $detail) {
                $detail->product()->increment('stock', $detail->qty);
                $detail->update(['item_status' => 'Dikembalikan']);
            }

            $borrowing->update([
                'status' => 'returned',
                'return_date' => now(),
            ]);
        });

        return redirect()->route('borrowings.index')->with('success', 'Barang berhasil ditandai sebagai dikembalikan.');
    }

    public function destroy(Borrowing $borrowing): RedirectResponse
    {
        // Kalau barang belum dikembalikan, kembalikan dulu stoknya sebelum record dihapus
        if ($borrowing->status !== 'returned') {
            foreach ($borrowing->details as $detail) {
                $detail->product()->increment('stock', $detail->qty);
            }
        }

        $borrowing->delete();

        return redirect()->route('borrowings.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }
}