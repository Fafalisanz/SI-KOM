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
    Schema::create('borrowings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Staff yang menginput
        $table->string('borrower_name'); // Nama Peminjam
        $table->date('borrow_date'); // Tanggal Pinjam
        $table->date('return_date')->nullable(); // Tanggal Kembali
        $table->enum('status', ['pending', 'borrowed', 'returned', 'overdue'])->default('pending'); // Status Peminjaman
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
