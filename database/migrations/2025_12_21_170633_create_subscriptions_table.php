<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            
            // 1. Liên kết với bảng users (Khách hàng)
            // onDelete('cascade'): Nếu xóa user thì xóa luôn đăng ký của họ (đỡ rác data)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // 2. Liên kết với bảng packages (Gói tập)
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            
            // 3. Thông tin thời hạn
            $table->date('start_date'); // Ngày bắt đầu
            $table->date('end_date');   // Ngày kết thúc
            
            // 4. Trạng thái
            // active: Đang hoạt động, expired: Hết hạn, cancelled: Đã hủy
            $table->string('status')->default('active'); 
            
            $table->timestamps(); // Tạo cột created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};