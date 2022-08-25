<?php

namespace App\Http\Controllers;

use App\Models\StudentClass;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreStudentClassRequest;
use App\Http\Requests\UpdateStudentClassRequest;

class StudentClassController extends Controller
{
    public function index()
    {
        $data = [
            'classes' => StudentClass::all()
        ];
        return view('class.index', $data);
    }
    public function create()
    {
        //
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
        $class = StudentClass::create($request->all());

        if ($class) {
            return response()->json([
                'code' => 200,
                'message' => 'Class created successfully'
            ]);
        }

        return response()->json([
            'code' => 500,
            'message' => 'Class not created'
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
                'message' => 'Class not updated'
            ]);
        }

        // update the student
        $studentClass->update($request->all());
        $studentClass->save();

        if ($studentClass) {
            return response()->json([
                'code' => 200,
                'message' => 'Class updated successfully',
                'class' => $studentClass
            ]);
        }
        return response()->json([
            'code' => 500,
            'message' => 'Class not updated'
        ]);
    }
    public function destroy(StudentClass $studentClass)
    {
        if ($studentClass->delete()) {
            return response()->json([
                'code' => 200,
                'message' => 'Class deleted successfully'
            ]);
        }
        return response()->json([
            'code' => 500,
            'message' => $studentClass
        ]);
    }
}