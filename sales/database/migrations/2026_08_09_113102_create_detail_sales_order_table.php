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
        Schema::create('detail_sales_order', function (Blueprint $table) {
            $table->id();
            $table->integer('kuantitas');
            $table->double('harga_unit');
            $table->string('diskon', 45)->nullable();
            $table->double('total_harga');
            $table->foreignId('sales_order_id')->constrained('sales_order')->onDelete('cascade');
            // Jika ada relasi ke m_barang di sales detail, bisa ditambahkan foreign key m_barang_sku sesuai kebutuhan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_sales_order');
    }
};
