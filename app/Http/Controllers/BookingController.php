<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;

class BookingController extends Controller
{
    // ==========================================
    // 1. TẠO MỚI ĐẶT LỊCH (BOOKING)
    // ==========================================
    public function store(Request $request)
    {
        // A. Validate dữ liệu
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'schedule_time' => 'required|date',
            // Các trường dưới đây cho phép null (tuỳ vào đặt PT hay đặt Lớp)
            'pt_id' => 'nullable|exists:users,id',
            'schedule_id' => 'nullable|exists:schedules,id',
            'package_id' => 'nullable|exists:packages,id',
            'status' => 'nullable|string',
            'note' => 'nullable|string'
        ]);

        // B. Logic kiểm tra riêng
        // Nếu có chọn PT, phải kiểm tra xem người đó có đúng là PT không
        if ($request->pt_id) {
            $pt = User::find($request->pt_id);
            if (!$pt || $pt->role !== 'pt') {
                return response()->json([
                    'status' => false,
                    'message' => 'Người bạn chọn không phải là Huấn luyện viên!'
                ], 400);
            }
        }

        // C. Tạo Booking mới vào Database
        // Mình dùng $request->all() kết hợp merge để đảm bảo lấy đủ trường
        // Hoặc liệt kê chi tiết như dưới đây cho an toàn:
        $booking = Booking::create([
            'user_id' => $request->user_id,
            'pt_id' => $request->pt_id,             // Có thể null
            'schedule_id' => $request->schedule_id, // Có thể null
            'package_id' => $request->package_id,   // Có thể null
            'schedule_time' => $request->schedule_time,
            'note' => $request->note,
            'status' => 'pending' // Mặc định là Chờ duyệt
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Đã gửi yêu cầu đặt lịch! Vui lòng chờ xác nhận.',
            'data' => $booking
        ]);
    }

   // ==========================================
    // 2. XEM DANH SÁCH LỊCH
    // ==========================================
    public function index(Request $request)
    {
        // Khởi tạo query lấy kèm thông tin User, PT, Lớp, Gói
        $query = Booking::with(['user', 'pt', 'schedule', 'package'])
                        ->orderBy('created_at', 'desc');

        // KIỂM TRA QUYỀN HẠN:
        // 1. Nếu là Admin: Không làm gì cả (Mặc định lấy tất cả)
        
        // 2. Nếu là PT: Chỉ lấy lịch của chính mình (được book) HOẶC lịch mình tự đặt
        if ($request->user()->role === 'pt') {
            $query->where(function($q) use ($request) {
                $q->where('pt_id', $request->user()->id)    // Khách book mình
                  ->orWhere('user_id', $request->user()->id); // Mình tự đặt
            });
        }
        
        // 3. Nếu là Member (Khách): Chỉ lấy lịch của mình
        else if ($request->user()->role === 'member') {
            $query->where('user_id', $request->user()->id);
        }

        $bookings = $query->get();

        return response()->json([
            'status' => true,
            'data' => $bookings
        ]);
    }
    
    // ==========================================
    // 3. DUYỆT / TỪ CHỐI LỊCH (Dành cho Admin/PT)
    // ==========================================
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::find($id);
        
        if (!$booking) {
            return response()->json(['status' => false, 'message' => 'Không tìm thấy lịch!']);
        }

        // Cập nhật trạng thái (confirmed / rejected)
        if ($request->has('status')) {
            $booking->status = $request->status;
            $booking->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Đã cập nhật trạng thái lịch tập!',
            'data' => $booking
        ]);
    }
}