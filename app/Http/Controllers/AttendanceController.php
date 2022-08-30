<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;

class AttendanceController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Absensi',
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

        // check attendance
        $attendance = Attendance::where('student_id', $student->id)
            ->where('date', date('Y-m-d'))
            ->first();
        if ($attendance) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kamu sudah absen hari ini'
            ]);
        }

        // insert attendance
        Attendance::create([
            'student_id' => $student->id,
            'student_name' => $student->name,
            'class_name' => $student->studentClass->name,
            'date' => date('Y-m-d'),
            'time' => date('H:i:s')
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $student->name . ' berhasil absen'
        ]);
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
