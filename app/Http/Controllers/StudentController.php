<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'students' => Student::all()
        ], 200);
    }
    public function show(int $id): JsonResponse
    {
        return response()->json([
            'student' => Student::where('id', $id)->first()
        ], 200);
    }
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->input(),[
            'name' => 'required',
            'email' => 'required|email|unique:students',
            'group_id' => 'integer'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        $student = new Student();
        $student['name'] = $request['name'];
        $student['email'] = $request['email'];
        if(isset($request['group_id'])) $student['group_id'] = $request['group_id'];
        $student->save();
        return response()->json([
            'student' => $student,
        ], 200);
    }
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->input(),[
            'email' => 'email|unique:students',
            'group_id' => 'integer|exists:groups,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        $student = Student::where('id', $id)->first();
        if($student == null) return response()->json([],404);
        if(isset($request['group_id'])) $student->group_id = $request['group_id'];
        if(isset($request['name'])) $student->name = $request['name'];
        if(isset($request['email'])) $student->email = $request['email'];
        $student->save();
        return response()->json([
            'student' => $student,
        ], 200);
    }
    public function destroy(int $id): JsonResponse
    {
        $student = Student::with('lecture_views')->where('id', $id)->first();
        if($student == null) return response()->json([],404);
        $student->lecture_views()->detach($student['lecture_views']);
        $student->delete();
        return response()->json([
            'message' => 'Student deleted'
        ], 200);
    }
}
