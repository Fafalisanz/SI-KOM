<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrowing_id',
        'product_id',
        'qty',
        'item_status',
    ];

    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}