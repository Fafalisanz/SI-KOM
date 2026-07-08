<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(
        private ?string $search = null,
        private ?int $categoryId = null
    ) {}

    public function collection(): Collection
    {
        $query = Product::with('category');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('product_code', 'like', "%{$this->search}%");
            });
        }

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        return $query->orderBy('name')->get();
    }

    public function headings(): array
    {
        return [
            'Kode Barang',
            'Nama Barang',
            'Kategori',
            'Stok',
            'Lokasi Penyimpanan',
            'Kondisi Barang',
        ];
    }

    public function map($product): array
    {
        return [
            $product->product_code,
            $product->name,
            $product->category->name ?? '-',
            $product->stock,
            $product->storage_location,
            $product->condition,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}