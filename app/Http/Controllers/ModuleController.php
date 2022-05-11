<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modules = Module::all();
        return response([
            'data' => $modules
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
        if (Auth::user()->role_id == 1) {
            $fields = $request->validate([
                'name' => 'required|string',
                'teacher_id' => 'required|numeric|exists:users,id',
            ]);

            $complaint = Module::create([
                'name' => $fields['name'],
                'teacher_id' => $fields['teacher_id'],
            ]);
            return response([
                'data' => $complaint,
                'message' => 'Module created successfully'
            ], 201);

        } else {
            return response([
                'message' => 'You are not authorized, you should be Admin !'
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
        return Module::find($id);
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
        if (Auth::user()->role_id == 1) {
            $fields = $request->validate([
                'name' => 'required|string',
                'teacher_id' => 'required|numeric|exists:users,id',
            ]);

            $complaint = Module::where('id', $id)->update([
                'name' => $fields['name'],
                'teacher_id' => $fields['teacher_id'],
            ]);
            return response([
                'data' => $complaint,
                'message' => 'Module created successfully'
            ], 201);

        } else {
            return response([
                'message' => 'You are not authorized, you should be Admin !'
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
        if (Auth::user()->role_id == 1) {
            if (Module::has('marks')->get()){
                return response([
                    'message' => 'Error! module has marks'
                ]);
            }else{
                Module::destroy($id);
                return response([
                    'message' => 'Module has been deleted successfully'
                ]);
            }

        } else {
            return response([
                'message' => 'You are not authorized, you should be Admin !'
            ]);
        }
    }
}
