<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentService
{
    protected Student $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public function getAll($paginate, $search)
    {
        if ($search) {
            $students = Student::with('studentClass')
                ->where('name', 'like', "%$search%")
                ->orWhere('student_id_number', 'like', "%$search%")
                ->paginate($paginate);
        } else {
            $students = Student::with('studentClass')
                ->latest()
                ->paginate($paginate);
        }

        if (request('page')) {
            $no = request('page') * $paginate - $paginate + 1;
        } else {
            $no = 1;
        }

        return [
            'students' => $students,
            'no' => $no,
        ];
    }

    public function multiDelete()
    {
        DB::beginTransaction();
        try {
            $students = $this->student->whereIn('id', request('ids'))->get();
            $students->each(function ($student) {
                $student->delete();
            });

            DB::commit();
            return [
                'code' => 200,
                'message' => 'Siswa berhasil dihapus'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'code' => 500,
                'message' => 'Siswa gagal dihapus'
            ];
        }
    }
}
