<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LectureController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'groups' => Lecture::all()
        ], 200);
    }
    public function show(int $id): JsonResponse
    {
        return response()->json([
            'lectures' => Lecture::with('student_views', 'groups_views')->where('id', $id)->first()
        ], 200);
    }
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->input(),[
            'name' => 'required|unique:lectures',
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $lecture = new Lecture();
        $lecture['name'] = $request['name'];
        $lecture['description'] = $request['description'];
        $lecture->save();
        return response()->json([
            'lecture' => $lecture,
        ], 200);
    }
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->input(),[
            'name' => 'required|unique:lectures,name,'.$id,
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        $lecture = Lecture::where('id', $id)->first();
        if($lecture == null) return response()->json([],404);
        $lecture['name'] = $request['name'];
        $lecture['description'] = $request['description'];
        $lecture->save();
        return response()->json([
            'lecture' => $lecture,
        ], 200);
    }
    public function destroy(int $id): JsonResponse
    {
        $lecture = Lecture::with('student_views', 'groups_views')->where('id', $id)->first();
        if($lecture == null) return response()->json([],404);
        $lecture->student_views()->detach($lecture['student_views']);
        $lecture->groups_views()->detach($lecture['groups_views']);
        $lecture->delete();

        return response()->json([
            'message' => 'Lecture deleted'
        ], 200);
    }
}
