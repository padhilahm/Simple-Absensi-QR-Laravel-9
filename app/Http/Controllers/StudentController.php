<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;

class StudentController extends Controller
{
    public function index()
    {
        $paginate = 10;

        $search = request()->query('search');
        if ($search) {
            $students = Student::where('name', 'like', "%$search%")
                ->orWhere('student_id_number', 'like', "%$search%")
                ->paginate($paginate);
        } else {
            $students = Student::latest()->paginate($paginate);
        }

        if (request('page')) {
            $no = request('page') * $paginate - $paginate + 1;
        } else {
            return redirect()->route('student.index', ['page' => 1, 'search' => $search]);
        }

        $data = [
            'students' => $students,
            'classes' => StudentClass::all(),
            'no' => $no
        ];
        return view('student.index', $data);
    }

    public function search()
    {
        $search = request()->query('search');
        $students = Student::where('name', 'like', "%$search%")
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
        $student = Student::create($request->all());

        if ($student) {
            return response()->json([
                'code' => 200,
                'message' => 'Student created successfully',
                'student' => $student
            ]);
        }

        return response()->json([
            'code' => 500,
            'message' => 'Student not created'
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
                'message' => 'Student not updated'
            ]);
        }

        // update the student
        $student->update($request->all());
        $student->save();

        if ($student) {
            return response()->json([
                'code' => 200,
                'message' => 'Student updated successfully',
                'student' => $student
            ]);
        }
        return response()->json([
            'code' => 500,
            'message' => 'Student not updated'
        ]);
    }

    public function destroy(Student $student)
    {
        if ($student->delete()) {
            return response()->json([
                'code' => 200,
                'message' => 'Student deleted successfully'
            ]);
        }
        return response()->json([
            'code' => 500,
            'message' => 'Student not deleted'
        ]);
        // return response()->json([
        //     'code' => 500,
        //     'message' => $student
        // ]);
    }

    public function destroyMulti()
    {
        $students = Student::whereIn('id', request('ids'))->get();
        $students->each(function ($student) {
            $student->delete();
        });
        return response()->json([
            'code' => 200,
            'message' => 'Students deleted successfully'
        ]);
    }
}