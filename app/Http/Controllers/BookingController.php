<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;


class BookingController extends Controller
{
    // --- 1. LẤY DANH SÁCH ĐẶT LỊCH ---
    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'pt_id' => 'required|exists:users,id',
            'schedule_time' => 'required|date',
            'status' => 'nullable|string',
            'note' => 'nullable|string'
        ]);

        // Kiểm tra xem PT đó có phải là PT thật không (tránh đặt nhầm cho Member khác)
        $pt = User::find($request->pt_id);
        if ($pt->role !== 'pt') {
            return response()->json([
                'status' => false,
                'message' => 'Người bạn chọn không phải là Huấn luyện viên!'
            ], 400);
        }

        // Tạo lịch mới
        $booking = Booking::create([
            'user_id' => $request->user_id,
            'pt_id' => $request->pt_id,
            'schedule_time' => $request->schedule_time,
            'note' => $request->note, // Ghi chú (nếu có)
            'status' => 'pending'     // Mặc định là Chờ duyệt
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Đã gửi yêu cầu đặt lịch! Vui lòng chờ PT xác nhận.',
            'data' => $booking
        ]);
    }

    // --- 2. XEM DANH SÁCH LỊCH (READ) ---
    public function index(Request $request)
    {
        // Lấy danh sách lịch, kèm theo tên Khách và tên PT để hiển thị cho đẹp
        $bookings = Booking::with(['user', 'pt'])
                        ->orderBy('schedule_time', 'desc') // Lịch mới nhất lên đầu
                        ->get();

        return response()->json([
            'status' => true,
            'data' => $bookings
        ]);
    }
    
    // --- 3. DUYỆT / TỪ CHỐI LỊCH (UPDATE STATUS) ---
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::find($id);
        
        if (!$booking) {
            return response()->json(['status' => false, 'message' => 'Không tìm thấy lịch!']);
        }

        // Cập nhật trạng thái (confirmed / rejected)
        $booking->status = $request->status;
        $booking->save();

        return response()->json([
            'status' => true,
            'message' => 'Đã cập nhật trạng thái lịch tập!'
        ]);
    }
}
    