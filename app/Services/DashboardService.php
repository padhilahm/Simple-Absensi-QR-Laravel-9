<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentClass;

class DashboardService
{

    protected StudentClass $studentClass;
    protected Student $student;

    public function __construct(StudentClass $studentClass, Student $student)
    {
        $this->studentClass = $studentClass;
        $this->student = $student;
    }

    public function mainDashboard()
    {
        $classess = $this->studentClass->with('students')->get();
        $arrayZero = 0;
        foreach ($classess as $class) {
            // jumlah siswa
            $classess[$arrayZero]['total'] = $class->students->count();

            // jumlah siswa yang hadir
            $classess[$arrayZero]['present'] = $class
                ->students()
                ->whereHas('attendances', function ($query) {
                    $query->where('date', date('Y-m-d'));
                })
                ->count();

            // jumlah siswa yang tidak hadir
            $classess[$arrayZero]['absent'] = $class
                ->students()
                ->whereDoesntHave('attendances', function ($query) {
                    $query->where('date', date('Y-m-d'));
                })
                ->count();

            $arrayOne = 0;
            // status kehadiran
            foreach ($class->students as $student) {
                $status = $student->attendances->where('date', date('Y-m-d'))->count();
                if ($status > 0) {
                    $classess[$arrayZero]['students'][$arrayOne]['attendance_status'] = 'Hadir';
                } else {
                    $classess[$arrayZero]['students'][$arrayOne]['attendance_status'] = 'Tidak';
                }
                $arrayOne++;
            }
            $arrayZero++;
        }

        return $classess;
    }

    public function attendanceDashboard($id, $date)
    {
        $students = $this->student->with('attendances')->where('student_class_id', $id)->get();
        $i = 0;
        // status kehadiran
        foreach ($students as $student) {
            $status = $student->attendances->where('date', $date)->count();
            if ($status > 0) {
                $students[$i]['attendance_status'] = 'Hadir';
            } else {
                $students[$i]['attendance_status'] = 'Tidak';
            }
            $i++;
        }

        return $students;
    }
}
