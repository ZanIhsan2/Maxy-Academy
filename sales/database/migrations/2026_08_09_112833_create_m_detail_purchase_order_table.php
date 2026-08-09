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
        Schema::create('m_detail_purchase_order', function (Blueprint $table) {
            $table->id();
            $table->integer('kuantitas');
            $table->double('harga_unit');
            $table->string('diskon', 45)->nullable();
            $table->double('total_harga');
            $table->string('m_barang_sku', 10);
            $table->foreignId('m_purchase_order_id')->constrained('m_purchase_order')->onDelete('cascade');

            $table->foreign('m_barang_sku')->references('sku')->on('m_barang')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_detail_purchase_order');
    }
};
