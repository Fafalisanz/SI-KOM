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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->unique(); // Kode Barang
            $table->string('name'); // Nama Barang
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // Kategori
            $table->integer('stock')->default(0); // Stok
            $table->string('storage_location'); // Lokasi Penyimpanan
            $table->string('condition'); // Kondisi Barang (Bagus, Rusak, dll.)
            $table->string('image')->nullable(); // Bonus Fitur: Upload Gambar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
