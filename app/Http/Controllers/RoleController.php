<?php

/* 
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
*/

namespace App\Http\Controllers;

use App\Models\Role;
use DataTables;
use DB;
use Illuminate\Http\Request;
use Log;
use Validator;

class RoleController extends Controller
{
    public function __construct() {
        parent::__construct();
        $this->data['crumbs'][1] = [
            'title' => 'List Role',
            'url' => route('roles.index'),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->data['title'] = 'List Role';
        $this->data['datas'] = Role::query()->simplePaginate(10);
        return view('pages.roles.index',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->data['title'] = 'Create New Role';
        $this->data['crumbs'][] = [
            'title' => 'Create New Role',
            'url' => route('roles.create'),
        ];
        return view('pages.roles.create',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|min:3|unique:roles,name',
        ]);

        try {
            DB::beginTransaction();
            $newRole = new Role();
            $newRole->name = $request->name;
            $newRole->slug = \Str::slug($request->name);
            $newRole->save();
            DB::commit();
            return redirect()->route('roles.index')->with('success','Role has been created');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            return redirect()->back()->withErrors(['error' => $th->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $this->data['title'] = "Edit Role {$role->name}";
        $this->data['crumbs'][] = [
            'title' => 'Edit Role',
            'url' => route('roles.edit',[$role->id]),
        ];
        $this->data['data'] = $role;
        return view('pages.roles.edit',$this->data);
    }

    public function users(Request $request, Role $role)
    {
        if(!$request->ajax()) return redirect()->back();

        $users = $role->users()->select('users.*');
        return DataTables::of($users)
        ->addIndexColumn()
        ->addColumn('unassignRole',function($user){
            return '<button type="button" class="btn btn-sm btn-outline-dark user_assign_role" x-on:click="unassignRole($el,'.$user->id.')">Unassign Role</button>';
        })
        ->rawColumns(['unassignRole'])
        ->make(true);
    }

    public function attachUsers(Request $request, Role $role)
    {
        $validator = Validator::make($request->input(),[
            'users'=>'array|min:1',
            'users.*'=>'required|exists:users,id'
        ]);

        if($validator->fails()) return $this->json_error(null,$validator->errors()->first());

        try {
            DB::beginTransaction();
            $role->users()->syncWithoutDetaching($request->users);
            DB::commit();
            return $this->json_success(null,'Role has been updated');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            return $this->json_error(null,$th->getMessage());
        }
    }

    public function detachUsers(Request $request, Role $role)
    {
        $validator = Validator::make($request->input(),[
            'users'=>'array|min:1',
            'users.*'=>'required|exists:users,id'
        ]);
        if($validator->fails()) return $this->json_error(null,$validator->errors()->first());

        try {
            DB::beginTransaction();
            $role->users()->detach($request->users);
            DB::commit();
            return $this->json_success(null,'Role has been updated');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            return $this->json_error(null,$th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name'=>'required|unique:roles,slug,'.$role->id.',id'
        ]);

        try {
            DB::beginTransaction();
            $role->name = $request->name;
            $role->slug = \Str::slug($request->name);
            $role->save();
            DB::commit();
            return redirect()->route('roles.index')->with('success','Role has been updated');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            return redirect()->back()->withErrors(['error' => $th->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Role $role)
    {
        try {
            DB::beginTransaction();
            $role->delete();
            DB::commit();
            if(!$request->ajax()){
                return redirect()->route('roles.index')->with('success','Role has been deleted');
            }else{
                session()->flash('success','Role has been deleted');
                return $this->json_success(null,'Role has been deleted');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            Log::error($th);
            return redirect()->back()->withErrors(['error' => $th->getMessage()])->withInput();
        }
    }
}
