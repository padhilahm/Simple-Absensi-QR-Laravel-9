<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;

class AttendanceController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        $startTime = date('H:i', strtotime($setting->attendance_start_time));
        $endTime = date('H:i', strtotime($setting->attendance_end_time));
        if ($startTime > date('H:i') || $endTime < date('H:i')) {
            $status = 0;
        } else {
            $status = 1;
        }

        $data = [
            'title' => 'Absensi',
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => $status,
        ];
        return view('index', $data);
    }

    public function create()
    {
        //
    }

    public function store(StoreAttendanceRequest $request)
    {
        $studentIdNumber = $request->qr_code;

        $student = Student::where('student_id_number', $studentIdNumber)->first();

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Siswa tidak ditemukan'
            ]);
        }

        $setting = Setting::first();
        if ($setting->attendance_start_time > date('H:i:s') || $setting->attendance_end_time < date('H:i:s')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Absensi belum dibuka atau sudah ditutup'
            ]);
        }

        // check attendance
        $attendance = Attendance::where('student_id', $student->id)
            ->where('date', date('Y-m-d'))
            ->first();
        if ($attendance) {
            return response()->json([
                'status' => 'success',
                'message' => $student->name . ' sudah absen hari ini'
            ]);
        }

        DB::beginTransaction();
        try {
            // insert attendance
            Attendance::create([
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

    public function show(Attendance $attendance)
    {
        //
    }

    public function edit(Attendance $attendance)
    {
        //
    }

    public function update(UpdateAttendanceRequest $request, Attendance $attendance)
    {
        //
    }

    public function destroy(Attendance $attendance)
    {
        //
    }
}
