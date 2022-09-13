<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreAttendanceRequest;

class AttendanceController extends Controller
{
    protected Setting $setting;
    protected Attendance $attendance;
    protected Student $student;
    protected User $user;

    public function __construct(Setting $setting, Attendance $attendance, Student $student, User $user)
    {
        $this->setting = $setting;
        $this->attendance = $attendance;
        $this->student = $student;
        $this->user = $user;
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
        $studentIdNumber = $request->qr_code;

        $student = $this->student->where('student_id_number', $studentIdNumber)->first();

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Siswa tidak ditemukan'
            ]);
        }

        $setting = $this->setting->first();
        if ($setting->attendance_start_time > date('H:i:s') || $setting->attendance_end_time < date('H:i:s')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Absensi belum dibuka atau sudah ditutup'
            ]);
        }

        // check attendance
        $attendance = $this->attendance->where('student_id', $student->id)->whereDate('created_at', date('Y-m-d'))->first();
        if ($attendance) {
            return response()->json([
                'status' => 'success',
                'message' => $student->name . ' sudah absen hari ini'
            ]);
        }

        DB::beginTransaction();
        try {
            // insert attendance
            $this->attendance->create([
                'student_id' => $student->id,
                'student_name' => $student->name,
                'class_name' => $student->studentClass->name,
                'date' => date('Y-m-d'),
                'time' => date('H:i:s')
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => $student->name . ' berhasil absen hari ini'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan'
            ]);
        }
    }
}
