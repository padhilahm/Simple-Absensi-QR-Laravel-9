<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\AttendanceService;
use App\Http\Requests\StoreAttendanceRequest;

class AttendanceController extends Controller
{
    protected Setting $setting;
    protected AttendanceService $attendanceService;

    public function __construct(Setting $setting, AttendanceService $attendanceService)
    {
        $this->setting = $setting;
        $this->attendanceService = $attendanceService;
    }

    public function index()
    {
        $data = [
            'title' => 'Absensi',
            'start_time' => $this->setting->first()->attendance_start_time,
            'end_time' => $this->setting->first()->attendance_end_time,
        ];
        return view('index', $data);
    }

    public function store(StoreAttendanceRequest $request)
    {
        return response()->json(
            $this->attendanceService->checkAttendance($request->qr_code)
        );
    }
}
