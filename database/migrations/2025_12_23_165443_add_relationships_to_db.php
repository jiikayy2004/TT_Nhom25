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
    // 1. Nối bảng SUBSCRIPTIONS với USERS và PACKAGES
    Schema::table('subscriptions', function (Blueprint $table) {
        // Nối user_id -> users.id
        // onDelete('cascade'): Nếu xóa User, tự động xóa luôn đơn đăng ký của họ
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        // Nối package_id -> packages.id
        $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
    });

    // 2. Nối bảng BOOKINGS với USERS
    Schema::table('bookings', function (Blueprint $table) {
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        
        // Nếu bảng bookings của bạn có cột package_id (như trong ảnh 2) thì mở dòng dưới ra:
        $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
    });

    // 3. Nối bảng PAYMENT (nếu có)
    if (Schema::hasTable('payments')) {
        Schema::table('payments', function (Blueprint $table) {
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('db', function (Blueprint $table) {
            //
        });
    }
};
