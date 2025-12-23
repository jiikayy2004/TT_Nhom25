<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Package;

class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        // 1. Validate dữ liệu
        $request->validate([
            'user_id' => 'required',
            'pt_id' => 'required',
            'package_id' => 'required',
            'schedule_time' => 'required',
        ]);

        $package = Package::find($request->package_id);
        if(!$package) return response()->json(['status' => false, 'message' => 'Gói không tồn tại']);

        // 2. Tạo Booking
        // Trạng thái là 'pending' (Chờ Admin/PT check tiền rồi duyệt)
        $booking = Booking::create([
            'user_id' => $request->user_id,
            'pt_id' => $request->pt_id,
            'package_id' => $request->package_id,
            'schedule_time' => $request->schedule_time,
            'note' => $request->note,
            'status' => 'pending' 
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Gửi yêu cầu thành công!',
            'booking_id' => $booking->id
        ]);
    }
}