<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // ==========================================
    // 1. XỬ LÝ ĐĂNG NHẬP
    // ==========================================
    public function login(Request $request)
    {
        // A. Validate dữ liệu
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // B. Kiểm tra thông tin đăng nhập
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => 'Email hoặc mật khẩu không chính xác!',
            ], 401);
        }

        // C. Lấy thông tin User
        $user = User::where('email', $request->email)->first();

        // D. Tạo Token
        $token = $user->createToken('auth_token')->plainTextToken;

        // E. Trả về kết quả
        return response()->json([
            'status' => true,
            'message' => 'Đăng nhập thành công',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'data' => $user
        ], 200);
    }

    // ==========================================
    // 2. XỬ LÝ ĐĂNG KÝ
    // ==========================================
    public function register(Request $request)
    {
        // A. Validate dữ liệu (Đã thêm address)
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6', // Tối thiểu 6 ký tự cho an toàn
            'phone' => 'nullable|string',
            'address' => 'nullable|string' // <-- MỚI THÊM
        ]);

        // B. Tạo User mới
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
            'phone' => $fields['phone'] ?? null,
            'address' => $fields['address'] ?? null, // <-- MỚI THÊM
            'role' => 'member', // Mặc định là Member
        ]);

        // C. Tạo Token luôn để user không phải đăng nhập lại
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Đăng ký thành công!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'data' => $user
        ], 201);
    }

    // ==========================================
    // 3. XỬ LÝ ĐĂNG XUẤT
    // ==========================================
    public function logout(Request $request)
    {
        // Xóa Token hiện tại đang dùng (các thiết bị khác vẫn đăng nhập bình thường)
        $request->user()->currentAccessToken()->delete();

        // Nếu muốn đăng xuất khỏi TẤT CẢ thiết bị thì dùng:
        // $request->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Đăng xuất thành công!'
        ]);
    }
}