<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package; // Gọi Model Package để làm việc với bảng 'packages' trong Database

class PackageController extends Controller
{
    // --- 1. HÀM LẤY DANH SÁCH GÓI TẬP ---
    public function index()
    {
        // Lấy tất cả dữ liệu trong bảng 'packages'
        // all(): Lấy hết
        $packages = Package::all();

        // Trả về dữ liệu dạng JSON cho Frontend
        return response()->json([
            'status' => true,
            'data' => $packages
        ]);
    }

    // --- 2. HÀM TẠO GÓI TẬP MỚI ---
   public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer' // Validate đúng tên này
        ]);

        try {
            $package = Package::create([
                'name' => $request->name,
                'price' => $request->price,
                // Cột trong DB (bên trái) => Dữ liệu từ Frontend (bên phải)
                'duration_days' => $request->duration_days 
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Thêm gói tập thành công!',
                'data' => $package
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // --- 3. HÀM XÓA GÓI TẬP ---
    public function destroy($id)
    {
        // Tìm gói tập theo ID
        $package = Package::find($id);

        // Nếu không tìm thấy (ví dụ ID 9999 không tồn tại)
        if (!$package) {
            return response()->json([
                'status' => false,
                'message' => 'Gói tập không tồn tại!'
            ], 404);
        }

        // Thực hiện xóa
        $package->delete();

        return response()->json([
            'status' => true,
            'message' => 'Đã xóa gói tập thành công!'
        ]);
    }
    // --- 4. HÀM CẬP NHẬT GÓI TẬP (SỬA) ---
    public function update(Request $request, $id)
    {
        // 1. Tìm gói tập theo ID gửi lên
        $package = Package::find($id);

        // Nếu không tìm thấy (ví dụ ID tào lao)
        if (!$package) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy gói tập này!'
            ], 404);
        }

        // 2. Kiểm tra dữ liệu đầu vào (Validate)
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer'
        ]);

        // 3. Thực hiện cập nhật
        // Hàm update() sẽ tự động lấy dữ liệu khớp với $fillable và lưu lại
        $package->update([
            'name' => $request->name,
            'price' => $request->price,
            'duration_days' => $request->duration_days
        ]);

        // 4. Báo thành công
        return response()->json([
            'status' => true,
            'message' => 'Cập nhật gói tập thành công!',
            'data' => $package
        ]);
    }
}