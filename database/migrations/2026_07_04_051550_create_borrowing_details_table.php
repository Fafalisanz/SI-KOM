<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::create('borrowing_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('borrowing_id')->constrained('borrowings')->onDelete('cascade');
        $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Barang
        $table->integer('qty')->default(1);
        $table->string('item_status')->nullable(); // Status Kondisi Barang saat dipinjam/kembali
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowing_details');
    }
};
