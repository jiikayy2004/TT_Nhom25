<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            
            // 1. Người đặt lịch (Hội viên)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // 2. Người được đặt (PT - Huấn luyện viên)
            // Vì PT cũng nằm trong bảng users, nên ta vẫn liên kết tới users
            $table->unsignedBigInteger('pt_id'); 
            $table->foreign('pt_id')->references('id')->on('users')->onDelete('cascade');
            
            // 3. Thời gian tập (Ngày + Giờ)
            $table->dateTime('schedule_time'); 
            
            // 4. Trạng thái lịch
            // pending: Chờ PT duyệt
            // confirmed: PT đã đồng ý
            // rejected: PT từ chối
            // cancelled: Khách hủy
            $table->string('status')->default('pending');
            
            // 5. Ghi chú của khách (Ví dụ: "Tập chân", "Tập ngực")
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};