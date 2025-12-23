<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('schedules', function (Blueprint $table) {
        $table->id();
        $table->string('name');         // Tên lớp (VD: Yoga Morning)
        $table->string('type');         // Bộ môn (Yoga, Gym, Boxing...)
        $table->string('trainer');      // Tên HLV
        $table->dateTime('start_time'); // Thời gian bắt đầu (Gồm cả ngày và giờ)
        $table->dateTime('end_time');   // Thời gian kết thúc
        $table->integer('max_slots')->default(20); // Số chỗ tối đa
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
