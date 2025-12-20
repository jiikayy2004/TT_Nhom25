<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; 

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo tài khoản Admin 
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gym.com',
            'password' => Hash::make('123456'), // Mật khẩu là 123456
            'role' => 'admin',
            'phone' => '0909123456',
        ]);

        // Tạo tài khoản Member mẫu để test
        User::create([
            'name' => 'Nguyen Van A',
            'email' => 'member@gym.com',
            'password' => Hash::make('123456'),
            'role' => 'member',
            'phone' => '0909000000',
        ]);
        
        // Tạo 1 gói tập mẫu
        \App\Models\Package::create([
             'name' => 'Gói 1 Tháng',
             'price' => 500000,
             'duration_days' => 30,
             'description' => 'Tập full dụng cụ trong 30 ngày',
        ]);
    }
}
