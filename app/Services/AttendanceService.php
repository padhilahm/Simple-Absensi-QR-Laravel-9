<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Student;
use App\Models\Attendance;

class AttendanceService
{
    protected Setting $setting;
    protected Attendance $attendance;
    protected Student $student;

    public function __construct(Setting $setting, Attendance $attendance, Student $student)
    {
        $this->setting = $setting;
        $this->attendance = $attendance;
        $this->student = $student;
    }

    public function checkAttendance($studentIdNumber)
    {
        $student = $this->student->where('student_id_number', $studentIdNumber)->first();

        if (!$student) {
            return [
                'status' => 'error',
                'message' => 'Siswa tidak ditemukan'
            ];
        }

        $setting = $this->setting->first();
        if ($setting->attendance_start_time > date('H:i:s') || $setting->attendance_end_time < date('H:i:s')) {
            return [
                'status' => 'error',
                'message' => 'Absensi belum dibuka atau sudah ditutup'
            ];
        }

        // check attendance
        $attendance = $this->attendance->where('student_id', $student->id)->whereDate('created_at', date('Y-m-d'))->first();
        if ($attendance) {
            return [
                'status' => 'success',
                'message' => $student->name . ' sudah absen hari ini'
            ];
        }

        // insert attendance
        $attendance = $this->attendance->create([
            'student_id' => $student->id,
            'student_name' => $student->name,
            'class_name' => $student->studentClass->name,
            'date' => date('Y-m-d'),
            'time' => date('H:i:s')
        ]);

        if ($attendance) {
            return [
                'status' => 'success',
                'message' => $student->name . ' berhasil absen'
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Absensi gagal'
            ];
        }
    }
}
