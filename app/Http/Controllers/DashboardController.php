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
        $classess = StudentClass::with('students')->get();
        $i = 0;
        foreach ($classess as $class) {
            // jumlah siswa
            $classess[$i]['total'] = $class->students->count();

            // jumlah siswa yang hadir
            $classess[$i]['present'] = $class
                ->students()
                ->whereHas('attendances', function ($query) {
                    $query->where('date', date('Y-m-d'));
                })
                ->count();

            // jumlah siswa yang tidak hadir
            $classess[$i]['absent'] = $class
                ->students()
                ->whereDoesntHave('attendances', function ($query) {
                    $query->where('date', date('Y-m-d'));
                })
                ->count();

            $j = 0;

            // status kehadiran
            foreach ($class->students as $student) {
                $status = $student->attendances->where('date', date('Y-m-d'))->count();
                if ($status > 0) {
                    $classess[$i]['students'][$j]['attendance_status'] = 'Hadir';
                } else {
                    $classess[$i]['students'][$j]['attendance_status'] = 'Tidak';
                }
                $j++;
            }
            $i++;
        }
        $data = [
            'classess' => $classess
        ];
        return view('dashboard.index', $data);
    }

    public function attendance($id = '', $date = '')
    {
        $students = Student::with('attendances')->where('student_class_id', $id)->get();
        $i = 0;
        // status kehadiran
        foreach ($students as $student) {
            $status = $student->attendances->where('date', date('Y-m-d'))->count();
            if ($status > 0) {
                $students[$i]['attendance_status'] = 'Hadir';
            } else {
                $students[$i]['attendance_status'] = 'Tidak';
            }
            $i++;
        }

        $data = [
            'date' => $date,
            'id' => $id,
            'students' => $students
        ];
        return view('dashboard.attendance', $data);
    }
}
