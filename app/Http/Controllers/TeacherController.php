<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::where('role_id', 2)->get();
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
            'phone' => 'required|numeric|digits:10|unique:users,phone',
            'password' => 'required|string|confirmed'
        ]);

        $teacher = User::create([
            'num' => $fields['num'],
            'lname' => $fields['lname'],
            'fname' => $fields['fname'],
            'birthdate' => $fields['birthdate'],
            'role_id' => 2,
            'phone' => $fields['phone'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);
        return response([
            'data' => $teacher,
            'message' => 'teacher created successfully'
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
            'phone' => 'required|numeric|digits:10|unique:users,phone,' . $id,
            'password' => 'required|string|confirmed'
        ]);

        $teacher = User::where('id', $id)->update([
            'num' => $fields['num'],
            'lname' => $fields['lname'],
            'fname' => $fields['fname'],
            'birthdate' => $fields['birthdate'],
            'role_id' => 2,
            'phone' => $fields['phone'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);
        return response([
            'data' => $teacher,
            'message' => 'teacher created successfully'
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
        if (User::where('id',$id)->has('modules')->get()){
            return response([
                'message' => 'Error! Delete teacher modules before'
            ]);
        }else{
            User::destroy($id);
            return response([
                'message' => 'record has been deleted successfully'
            ]);
        }

    }
}
