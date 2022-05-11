<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->role_id == 1) {
            $marks = Mark::with('module', 'module.teacher', 'student')->get();
        } elseif (Auth::user()->role_id == 2) {
            $marks = Mark::with('module', 'module.teacher', 'student')
                ->where('teacher_id', Auth::id())
                ->get();
        } elseif (Auth::user()->role_id == 3) {
            $marks = Mark::with('module', 'module.teacher', 'student')
                ->where('student_id', Auth::id())
                ->get();
        }
        return response([
            'data' => $marks
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->role_id == 2) {
            $fields = $request->validate([
                'module_id' => 'required|integer|exists:modules,id,teacher_id,' . Auth::id(),
                'student_id' => 'required|integer|exists:users,id',
                'mark' => 'required|float|between:0,20',
            ]);

            $mark = Mark::create([
                'module_id' => $fields['module_id'],
                'student_id' => $fields['student_id'],
                'mark' => $fields['mark'],
            ]);
            return response([
                'data' => $mark,
                'message' => 'Mark created successfully'
            ], 201);

        } else {
            return response([
                'message' => 'You are not authorized, you should be Teacher !'
            ]);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->role_id == 2) {
            $fields = $request->validate([
                'module_id' => 'required|integer|exists:modules,id,teacher_id,' . Auth::id(),
                'student_id' => 'required|integer|exists:users,id',
                'mark' => 'required|float|between:0,20',
            ]);

            $mark = Mark::where('module_id', $fields['module_id'])
                ->where('student_id', $fields['student_id'])
                ->update([
                    'module_id' => $fields['module_id'],
                    'student_id' => $fields['student_id'],
                    'mark' => $fields['mark'],
                ]);
            return response([
                'data' => $mark,
                'message' => 'Mark updated successfully'
            ], 201);

        } else {
            return response([
                'message' => 'You are not authorized, you should be Teacher !'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $reaquest ,$id)
    {
        if (Auth::user()->role_id == 2) {

            Mark::where('module_id', $reaquest->module_id)
                ->where('student_id', $reaquest->student_id)
                ->delete();
            return response([
                'message' => 'Mark has been deleted successfully'
            ], 201);

        } else {
            return response([
                'message' => 'You are not authorized, you should be Teacher !'
            ]);
        }
    }
}
