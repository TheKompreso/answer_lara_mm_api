<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Lecture;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'groups' => Group::all()
        ], 200);
    }
    public function show(int $id): JsonResponse
    {
        return response()->json([
            'group' => Group::with('students')->where('id', $id)->first()
        ], 200);
    }
    public function StudyPlan(Request $request, int $id): JsonResponse
    {
        return response()->json([
            'group' => Group::with('lectures')->where('id', $id)->first()
        ], 200);
    }
    public function StudyPlanUpdate(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->input(),[
            'lectures' => 'required|array',
            'lectures.*' => 'integer'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $group = Group::with('lectures')->where('id', $id)->first();
        if($group == null) return response()->json([],404);

        $group->lectures()->detach($group['lectures']);
        $lectures = Lecture::find($request['lectures']);
        $group->lectures()->attach($lectures);

        return response()->json([
            'group' => Group::with('lectures')->where('id', $id)->first()
        ], 200);
    }
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->input(),[
            'name' => 'required|unique:groups',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        $group = new Group();
        $group['name'] = $request['name'];
        $group->save();
        return response()->json([
            'group' => $group,
        ], 200);
    }
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->input(),[
            'name' => 'required|unique:groups',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        $group = Group::where('id', $id)->first();
        $group['name'] = $request['name'];
        $group->save();
        return response()->json([
            'group' => $group,
        ], 200);
    }
    public function destroy(int $id): JsonResponse
    {
        $group = Group::with('students','lectures')->where('id', $id)->first();
        $group->students()->update(['group_id' => null]);
        $group->lectures()->detach($group['lectures']);
        $group->delete();

        return response()->json([
            'message' => 'Group deleted'
        ], 200);
    }
}
