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
        Schema::create('users', function (Blueprint $table) {
        $table->id(); // Tự động tăng
        $table->string('name');
        $table->string('email')->unique(); // Email không được trùng
        $table->string('phone')->nullable(); // Số điện thoại (có thể để trống)
        $table->string('avatar')->nullable(); // Ảnh đại diện
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        
        // Quan trọng: Phân quyền (admin, pt, member). Mặc định là member.
        $table->enum('role', ['admin', 'pt', 'member'])->default('member');
        
        $table->rememberToken();
        $table->timestamps(); // Tự tạo created_at và updated_at
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
