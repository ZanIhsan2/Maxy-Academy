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
        Schema::create('m_purchase_order', function (Blueprint $table) {
            $table->id();
            $table->string('no_order', 45);
            $table->dateTime('tanggal_dibutuhkan');
            $table->foreignId('m_vendor_id1')->constrained('m_vendor')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_purchase_order');
    }
};
