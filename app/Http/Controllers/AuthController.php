<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Hàm xử lý đăng nhập
    public function login(Request $request)
    {
        // 1. Kiểm tra dữ liệu gửi lên (Validate)
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Kiểm tra Email và Password có khớp trong Database không
        // Auth::attempt sẽ tự động mã hóa password gửi lên và so sánh với password trong DB
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => 'Email hoặc mật khẩu không chính xác!',
            ], 401); // 401: Unauthorized (Không được phép)
        }

        // 3. Nếu đúng, lấy thông tin user
        $user = User::where('email', $request->email)->first();

        // 4. Tạo Token (Chìa khóa bảo mật)
        // Token này sẽ dùng để gọi các API khác sau này
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Trả về kết quả cho Frontend
        return response()->json([
            'status' => true,
            'message' => 'Đăng nhập thành công',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'data' => $user // Trả về cả thông tin user (role, name...)
        ], 200);
    }
    // ---  Hàm xử lý đăng ký ---
    public function register(Request $request)
    {
        // 1. Validate dữ liệu
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email', // Check trùng email
            'password' => 'required|string|confirmed', // Bắt buộc có nhập lại mật khẩu (password_confirmation)
            'phone' => 'nullable|string'
        ]);

        // 2. Tạo User mới vào Database
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']), // Mã hóa mật khẩu
            'phone' => $fields['phone'] ?? null,
            'role' => 'member', // Mặc định đăng ký mới là Hội viên (Member)
        ]);

        // 3. Tạo Token luôn (để đăng ký xong tự đăng nhập luôn)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Đăng ký thành công!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'data' => $user
        ], 201);
    }


    // Hàm đăng xuất (Làm sau)
    public function logout()
    {
        // ...
    }
}
