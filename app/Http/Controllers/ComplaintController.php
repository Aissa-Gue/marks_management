<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->role_id == 1) {
            $complaintPv = User::with('complaintPv', 'complaintPv.module', 'complaintPv.student')->get();
            return response($complaintPv);

        } elseif (Auth::user()->role_id == 2) {
            $complaintModule = User::with('modules', 'modules.teacher', 'complaintsModule', 'complaintsModule.module', 'complaintsModule.student')
                ->where('teacher_id', Auth::id())
                ->get();
            return response($complaintModule);


        } elseif (Auth::user()->role_id == 3) {
            $complaintModule = User::with('modules', 'modules.teacher', 'complaintsModule', 'complaintsModule.module', 'complaintsModule.student')
                ->where('id', Auth::id())
                ->get();
            return response($complaintModule);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->role_id == 3) {
            $fields = $request->validate([
                'module_id' => 'nullable|numeric|exists:modules,id',
                'student_id' => 'required|numeric|exists:users,id',
                'message' => 'required|string',
            ]);

            $complaint = Complaint::create([
                'module_id' => $fields['module_id'],
                'student_id' => Auth::id(),
                'message' => $fields['message'],
            ]);
            return response([
                'data' => $complaint,
                'message' => 'Complaint created successfully'
            ], 201);

        } else {
            return response([
                'message' => 'You are not authorized, you should be student !'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Auth::user()->role_id == 1) {
            $complaint = Complaint::with('module', 'module.teacher', 'student')
                ->whereNull('module_id')
                ->where('id', $id)
                ->first();
        } elseif (Auth::user()->role_id == 2) {
            $complaint = Complaint::with('module', 'module.teacher', 'student')
                ->where('teacher_id', Auth::id())
                ->where('id', $id)
                ->first();
        } elseif (Auth::user()->role_id == 3) {
            $complaint = Complaint::with('module', 'module.teacher', 'student')
                ->where('student_id', Auth::id())
                ->where('id', $id)
                ->first();
        } else {
            return response([
                'data' => 'You are not authorized !'
            ]);
        }
        return response($complaint);
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
        if (Auth::user()->role_id == 3) {
            $fields = $request->validate([
                'module_id' => 'nullable|numeric|exists:modules,id',
                'student_id' => 'required|numeric|exists:users,id',
                'message' => 'required|string',
            ]);

            $complaint = Complaint::where('id', $id)->update([
                'module_id' => $fields['module_id'],
                'student_id' => Auth::id(),
                'message' => $fields['message'],
            ]);
            return response([
                'data' => $complaint,
                'message' => 'Complaint created successfully'
            ], 201);

        } else {
            return response([
                'message' => 'You are not authorized, you should be student !'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->role_id == 3) {
            Complaint::where('id', $id)
                ->where('student_id', Auth::id())
                ->delete();
        } else {
            return response([
                'message' => 'You are not authorized, you should be student !'
            ]);
        }
    }
}
