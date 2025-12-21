<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 1. LẤY DANH SÁCH (Chỉ lấy Member và PT, trừ Admin )
    public function index()
    {
        // Lấy tất cả user mà role KHÔNG PHẢI là admin
        $users = User::where('role', '!=', 'admin')->orderBy('id', 'desc')->get();

        return response()->json([
            'status' => true,
            'data' => $users
        ]);
    }

    // 2. THÊM HỘI VIÊN MỚI (Do Admin tạo)
    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'nullable',
            // Mặc định tạo member, nhưng cho phép chọn role nếu muốn
            'role' => 'in:member,pt' 
        ]);

        // Tạo user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Mã hóa password
            'phone' => $request->phone,
            'role' => $request->role ?? 'member', // Nếu không chọn thì mặc định là member
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Thêm hội viên thành công!',
            'data' => $user
        ]);
    }

    // 3. XÓA HỘI VIÊN
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy người dùng này!'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'Đã xóa hội viên thành công!'
        ]);
    }
}