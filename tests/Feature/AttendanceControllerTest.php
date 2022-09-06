<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Setting;
use App\Models\Student;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendanceControllerTest extends TestCase
{
    public function testAttendanceIndex()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testAttendanceStoreStudentNotFound()
    {
        $response = $this->postJson('/attendance', [
            'qr_code' => '1234567890'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'error',
                'message' => 'Siswa tidak ditemukan'
            ]);
    }

    public function testAttendanceStoreAttendanceNotOpen()
    {
        // insert student
        $student = Student::create([
            'student_id_number' => '1234567890',
            'name' => 'John Doe',
            'student_class_id' => 1
        ]);

        // set setting attendance start time and end time
        $setting = Setting::first();
        $setting->attendance_start_time = date('H:i:s', strtotime('+1 hour'));
        $setting->attendance_end_time = date('H:i:s', strtotime('+2 hour'));
        $setting->save();

        $response = $this->postJson('/attendance', [
            'qr_code' => $student->student_id_number
        ]);

        // delete student
        $student->delete();

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'error',
                'message' => 'Absensi belum dibuka atau sudah ditutup'
            ]);
    }

    public function testAttendanceStoreAttendanceAlready()
    {
        // insert student
        $student = Student::create([
            'student_id_number' => '1234567890',
            'name' => 'John Doe',
            'student_class_id' => 1
        ]);

        // set setting attendance start time and end time
        $setting = Setting::first();
        $setting->attendance_start_time = date('H:i:s', strtotime('-1 hour'));
        $setting->attendance_end_time = date('H:i:s', strtotime('+1 hour'));
        $setting->save();

        // insert attendance
        $attendance = $student->attendances()->create([
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'student_id' => $student->id
        ]);

        $response = $this->postJson('/attendance', [
            'qr_code' => $student->student_id_number
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => $student->name . ' sudah absen hari ini'
            ]);

        // delete student
        $student->delete();

        // delete attendance
        $attendance->delete();
    }

    public function testAttendanceStoreSuccess()
    {
        // insert student
        $student = Student::create([
            'student_id_number' => '12345678901',
            'name' => 'John Doe',
            'student_class_id' => 1
        ]);

        // set setting attendance start time and end time
        $setting = Setting::first();
        $setting->attendance_start_time = date('H:i:s', strtotime('-1 hour'));
        $setting->attendance_end_time = date('H:i:s', strtotime('+1 hour'));
        $setting->save();

        $response = $this->postJson('/attendance', [
            'qr_code' => $student->student_id_number
        ]);

        // insert attendance
        $attendance = $student->attendances()->create([
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'student_id' => $student->id
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => $student->name . ' berhasil absen hari ini'
            ]);

        // delete student
        $student->delete();

        // delete attendance
        $attendance->delete();
    }
}
