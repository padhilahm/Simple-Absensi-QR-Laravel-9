<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreStudentClassRequest;
use App\Http\Requests\UpdateStudentClassRequest;

class StudentClassController extends Controller
{
    protected User $user;
    protected StudentClass $studentClass;

    public function __construct(User $user, StudentClass $studentClass)
    {
        $this->user = $user;
        $this->studentClass = $studentClass;
    }

    public function index()
    {
        $data = [
            'classes' => StudentClass::all(),
            'user' => User::find(auth()->user()->id),
        ];
        return view('class.index', $data);
    }

    public function store(StoreStudentClassRequest $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'errors' => $validate->errors()
            ]);
        }

        // save the class
        $class = $this->studentClass->create($request->all());

        if ($class) {
            return response()->json([
                'code' => 200,
                'message' => 'Kelas berhasil ditambahkan'
            ]);
        }

        return response()->json([
            'code' => 500,
            'message' => 'Kelas gagal ditambahkan'
        ]);
    }

    public function show(StudentClass $studentClass)
    {
        return response()->json([
            'code' => 200,
            'message' => 'Class found',
            'class' => $studentClass
        ]);
    }

    public function update(UpdateStudentClassRequest $request, StudentClass $studentClass)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'errors' => $validate->errors(),
                'message' => 'Kelas gagal diedit'
            ]);
        }

        // update the student
        $studentClass->update($request->all());
        $studentClass->save();

        if ($studentClass) {
            return response()->json([
                'code' => 200,
                'message' => 'Kelas berhasil diedit'
            ]);
        }

        return response()->json([
            'code' => 500,
            'message' => 'Kelas gagal diedit'
        ]);
    }

    public function destroy(StudentClass $studentClass)
    {
        if ($studentClass->delete()) {
            return response()->json([
                'code' => 200,
                'message' => 'Kelas berhasil dihapus'
            ]);
        }
        return response()->json([
            'code' => 500,
            'message' => $studentClass
        ]);
    }
}
