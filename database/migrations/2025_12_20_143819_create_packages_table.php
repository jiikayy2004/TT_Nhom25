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
        Schema::create('packages', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Tên gói (VD: 1 Tháng, VIP...)
        $table->text('description')->nullable(); // Mô tả quyền lợi
        $table->decimal('price', 10, 2); // Giá tiền (10 số, 2 số thập phân)
        $table->integer('duration_days'); // Thời hạn tính bằng ngày (30, 90, 365...)
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
