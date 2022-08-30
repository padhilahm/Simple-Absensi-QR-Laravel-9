<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use App\Models\Student;

class DashboardController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            'classess' => StudentClass::with('students')->get(),
        ];
        return view('dashboard.index', $data);
    }

    public function attendance($id = '', $date = '')
    {
        $data = [
            // 'attendances' => Attendance::with('student')->whereHas('student', function ($query) use ($id) {
            //     $query->where('student_class_id', $id);
            // })->get(),
            'date' => $date,
            'id' => $id,
            'students' => Student::with('attendances')->where('student_class_id', $id)->get(),
        ];
        return view('dashboard.attendance', $data);
    }
}
