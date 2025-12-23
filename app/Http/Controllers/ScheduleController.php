<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule; // Model Lớp học
use App\Models\Booking;  // Model Đăng ký
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    // ==========================================
    // 1. LẤY DANH SÁCH LỚP (Dùng cho cả Admin và Khách)
    // ==========================================
    public function index(Request $request)
    {
        $date = $request->query('date', now()->toDateString());

        // Lấy lớp theo ngày, sắp xếp theo giờ
        $schedules = Schedule::whereDate('start_time', $date)
                             ->orderBy('start_time', 'asc')
                             ->get();

        $data = $schedules->map(function($s) {
            return [
                'id' => $s->id,
                'name' => $s->name,
                'type' => $s->type ?? 'Gym',
                'trainer' => $s->trainer,
                'start_time' => $s->start_time,
                'end_time' => $s->end_time,
                'time' => date('H:i', strtotime($s->start_time)),
                'end' => date('H:i', strtotime($s->end_time)),
                // Tính số chỗ còn trống
                'slots' => ($s->max_slots ?? 20) - ($s->booked_slots ?? 0),
                'max_slots' => $s->max_slots ?? 20
            ];
        });

        return response()->json(['status' => true, 'data' => $data]);
    }

    // ==========================================
    // 2. KHÁCH HÀNG ĐĂNG KÝ LỚP (Hàm bạn đang thiếu)
    // ==========================================
    public function bookClass(Request $request)
    {
        $user = $request->user(); // Lấy user đang đăng nhập
        $schedule_id = $request->schedule_id;

        // 1. Tìm lớp học
        $schedule = Schedule::find($schedule_id);
        if (!$schedule) {
            return response()->json(['status' => false, 'message' => 'Lớp học không tồn tại!'], 404);
        }

        // 2. Kiểm tra xem lớp đã đầy chưa
        if ($schedule->booked_slots >= $schedule->max_slots) {
            return response()->json(['status' => false, 'message' => 'Lớp học đã hết chỗ!'], 400);
        }

        // 3. Kiểm tra xem khách đã đăng ký lớp này chưa (tránh spam)
        $exists = Booking::where('user_id', $user->id)
                         ->where('schedule_id', $schedule_id)
                         ->exists();
        
        if ($exists) {
            return response()->json(['status' => false, 'message' => 'Bạn đã đăng ký lớp này rồi!'], 400);
        }

        // 4. Tiến hành đăng ký
        try {
            DB::beginTransaction();

            // Tạo booking mới
            Booking::create([
                'user_id' => $user->id,
                'schedule_id' => $schedule->id,
                'schedule_time' => $schedule->start_time,
                'status' => 'confirmed' // Mặc định là đã xác nhận vì đặt theo lớp
            ]);

            // Tăng số lượng đã đặt của lớp lên 1
            $schedule->increment('booked_slots');

            DB::commit();

            return response()->json([
                'status' => true, 
                'message' => 'Đăng ký lớp thành công!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'Lỗi Server: ' . $e->getMessage()], 500);
        }
    }

    // ==========================================
    // 3. ADMIN TẠO LỚP MỚI
    // ==========================================
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'start_time' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Thiếu thông tin!'], 400);
        }

        try {
            $schedule = Schedule::create([
                'name' => $request->name,
                'type' => $request->type,
                'trainer' => $request->trainer,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'max_slots' => $request->max_slots ?? 20,
                'booked_slots' => 0
            ]);

            return response()->json(['status' => true, 'message' => 'Tạo lớp thành công!', 'data' => $schedule]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }

    // ==========================================
    // 4. ADMIN XÓA LỚP
    // ==========================================
    public function destroy($id)
    {
        $schedule = Schedule::find($id);
        if($schedule) {
            $schedule->delete();
            return response()->json(['status' => true, 'message' => 'Đã xóa thành công']);
        }
        return response()->json(['status' => false, 'message' => 'Không tìm thấy lớp']);
    }
    // 5. LẤY DANH SÁCH HỘI VIÊN TRONG LỚP
    public function getMembers($id)
    {
        // Lấy danh sách booking của lớp này, kèm thông tin User
        $bookings = Booking::where('schedule_id', $id)
                           ->where('status', 'confirmed') // Chỉ lấy lịch đã xác nhận
                           ->with('user') // Load thông tin bảng users
                           ->get();

        return response()->json([
            'status' => true,
            'data' => $bookings
        ]);
    }
}