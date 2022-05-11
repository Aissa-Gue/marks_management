<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::where('role_id', 3)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'num' => 'required|numeric|unique:users,num',
            'lname' => 'required|string',
            'fname' => 'required|string',
            'birthdate' => 'required|date',
            'role_id' => 'required|integer|exists:roles,id',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|numeric|digits:10|unique:users,phone',
            'password' => 'required|string|confirmed'
        ]);

        $student = User::create([
            'num' => $fields['num'],
            'lname' => $fields['lname'],
            'fname' => $fields['fname'],
            'birthdate' => $fields['birthdate'],
            'role_id' => 3,
            'phone' => $fields['phone'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);
        return response([
            'data' => $student,
            'message' => 'student created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::find($id);
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
        $fields = $request->validate([
            'num' => 'required|numeric|unique:users,num',
            'lname' => 'required|string',
            'fname' => 'required|string',
            'birthdate' => 'required|date',
            'role_id' => 'required|integer|exists:roles,id',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|numeric|digits:10|unique:users,phone,' . $id,
            'password' => 'required|string|confirmed'
        ]);

        $student = User::where('id', $id)->update([
            'num' => $fields['num'],
            'lname' => $fields['lname'],
            'fname' => $fields['fname'],
            'birthdate' => $fields['birthdate'],
            'role_id' => 3,
            'phone' => $fields['phone'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);
        return response([
            'data' => $student,
            'message' => 'student created successfully'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Mark::where('user_id', $id)->delete();
            User::destroy($id);
            DB::commit();
            return response([
                'message' => 'record has been deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response([
                'message' => 'Error: delete operation is failed'
            ]);
        }
    }
}
