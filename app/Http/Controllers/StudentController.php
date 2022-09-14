<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\StudentClass;
use App\Imports\StudentImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Services\StudentService;

class StudentController extends Controller
{
    protected StudentService $studentService;
    protected User $user;
    protected StudentClass $studentClass;
    protected Student $student;

    public function __construct(StudentService $studentService, User $user, StudentClass $studentClass, Student $student,)
    {
        $this->studentService = $studentService;
        $this->user = $user;
        $this->studentClass = $studentClass;
        $this->student = $student;
    }

    public function index()
    {
        $paginate = 10;
        $search = request()->query('search');

        $students = $this->studentService->getAll($paginate, $search);

        $data = [
            'students' => $students['students'],
            'classes' => $this->studentClass->all(),
            'no' => $students['no'],
            'user' => $this->user->find(auth()->user()->id),
        ];
        return view('student.index', $data);
    }

    public function search()
    {
        $search = request()->query('search');
        $students = $this->student->where('name', 'like', "%$search%")
            ->orWhere('student_id_number', 'like', "%$search%")
            ->get();
        return response()->json($students);
    }

    public function store(StoreStudentRequest $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'student_id_number' => 'required|unique:students,student_id_number',
                'student_class_id' => 'required'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'errors' => $validate->errors()
            ]);
        }

        // save the student
        $student = $this->student->create($request->all());

        if ($student) {
            return response()->json([
                'code' => 200,
                'message' => 'Siswa berhasil ditambahkan',
                'student' => $student
            ]);
        }

        return response()->json([
            'code' => 500,
            'message' => 'Siswa gagal ditambahkan'
        ]);
    }

    public function show(Student $student)
    {
        if ($student) {
            return response()->json([
                'code' => 200,
                'message' => 'Student found',
                'student' => $student
            ]);
        }

        return response()->json([
            'code' => 500,
            'message' => 'Student not found'
        ]);
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'student_id_number' => 'required|unique:students,student_id_number,' . $student->id,
                'student_class_id' => 'required'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'errors' => $validate->errors(),
                'message' => 'Siswa gagal diedit'
            ]);
        }

        // update the student
        $student->update($request->all());
        $student->save();
        if ($student) {
            return response()->json([
                'code' => 200,
                'message' => 'Siswa berhasil diedit',
                'student' => $student
            ]);
        }

        return response()->json([
            'code' => 500,
            'message' => 'Siswa gagal diedit'
        ]);
    }

    public function destroy(Student $student)
    {
        if ($student->delete()) {
            return response()->json([
                'code' => 200,
                'message' => 'Siswa berhasil dihapus'
            ]);
        }
        return response()->json([
            'code' => 500,
            'message' => 'Siswa gagal dihapus'
        ]);
    }

    public function destroyMulti()
    {
        return response()->json(
            $this->studentService->multiDelete()
        );
    }

    public function card($studentClassId = '')
    {
        $user = $this->user->find(auth()->user()->id);
        $students = $this->student->with('studentClass')
            ->where('student_class_id', $studentClassId)
            ->get();
        return view('student.card', compact('students', 'user'));
    }

    public function import()
    {
        Excel::import(new StudentImport, request()->file('import_excel'));

        return redirect()->route('student.index')->with('success', 'Siswa berhasil diimport');
    }
}
