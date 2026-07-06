<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Inventaris</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; color: #E11417; margin-bottom: 0; }
        .subtitle { font-size: 11px; color: #777; margin-top: 2px; margin-bottom: 20px; }
        .summary-table { width: 100%; margin-bottom: 20px; }
        .summary-table td {
            width: 25%; text-align: center; padding: 10px; border: 1px solid #eee;
        }
        .summary-table .value { font-size: 18px; font-weight: bold; display: block; }
        .summary-table .label { font-size: 9px; text-transform: uppercase; color: #999; }

        table.data { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        table.data th {
            background: #f5f5f5; text-align: left; padding: 6px 8px;
            font-size: 10px; text-transform: uppercase; color: #666;
            border-bottom: 1px solid #ddd;
        }
        table.data td {
            padding: 6px 8px; border-bottom: 1px solid #f0f0f0; font-size: 11px;
        }
        h2.section-title {
            font-size: 13px; color: #333; border-left: 4px solid #E11417;
            padding-left: 8px; margin-top: 25px; margin-bottom: 10px;
        }
        .badge {
            padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: bold;
        }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-gray { background: #f3f4f6; color: #374151; }
        .footer { margin-top: 30px; font-size: 9px; color: #aaa; text-align: center; }
    </style>
</head>
<body>

    <h1>Laporan Inventaris - PT Telkomsel</h1>
    <p class="subtitle">
        Dicetak pada {{ now()->format('d M Y, H:i') }} WIB &middot;
        Periode peminjaman: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
    </p>

    <table class="summary-table">
        <tr>
            <td>
                <span class="value">{{ $summary['total_borrowings'] }}</span>
                <span class="label">Total Peminjaman</span>
            </td>
            <td>
                <span class="value">{{ $summary['returned'] }}</span>
                <span class="label">Dikembalikan</span>
            </td>
            <td>
                <span class="value">{{ $summary['borrowed'] }}</span>
                <span class="label">Sedang Dipinjam</span>
            </td>
            <td>
                <span class="value">{{ $summary['overdue'] }}</span>
                <span class="label">Terlambat</span>
            </td>
        </tr>
    </table>

    <h2 class="section-title">Laporan Stok Barang ({{ $summary['total_item'] }} jenis, {{ $summary['total_stok'] }} unit)</h2>
    <table class="data">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->product_code }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->condition }}</td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada data barang.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2 class="section-title">Laporan Peminjaman</h2>
    <table class="data">
        <thead>
            <tr>
                <th>Peminjam</th>
                <th>Barang</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($borrowings as $borrowing)
                @php
                    [$badgeClass, $badgeLabel] = match($borrowing->status) {
                        'borrowed' => ['badge-yellow', 'Dipinjam'],
                        'returned' => ['badge-green', 'Dikembalikan'],
                        'overdue' => ['badge-red', 'Terlambat'],
                        default => ['badge-gray', 'Pending'],
                    };
                @endphp
                <tr>
                    <td>{{ $borrowing->borrower_name }}</td>
                    <td>{{ $borrowing->details->pluck('product.name')->filter()->join(', ') ?: '-' }}</td>
                    <td>{{ $borrowing->borrow_date->format('d M Y') }}</td>
                    <td>{{ $borrowing->return_date ? $borrowing->return_date->format('d M Y') : '-' }}</td>
                    <td><span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span></td>
                </tr>
            @empty
                <tr><td colspan="5">Tidak ada data peminjaman pada rentang tanggal ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <p class="footer">
        Sistem Manajemen Inventaris PT Telkomsel &middot; Dokumen ini dihasilkan otomatis oleh sistem.
    </p>

</body>
</html>