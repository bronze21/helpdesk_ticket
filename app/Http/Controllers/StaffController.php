<?php

/* 
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
*/

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use DB;
use Hash;
use Illuminate\Http\Request;
use Log;

class StaffController extends Controller
{
    public function __construct() {
        parent::__construct();
        $this->data['crumbs'][1] = [
            'title' => 'Staff',
            'url' => route('staff.index'),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->data['title'] = "List Staff";
        
        $this->data['datas'] = User::queryWithoutRole(['user'])->simplePaginate(10);
        $this->data['roles'] = Role::where('slug','!=','user')->get();
        return view('pages.staff.index',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->data['title'] = 'Create New Staff';
        $this->data['crumbs'][] = [
            'title' => 'Create New Staff',
            'url' => route('staff.create'),
        ];
        return view('pages.staff.create',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|min:3',
            'email'=>'required|email',
            'password'=>'required',
            're_password'=>'required|same:password'
        ]);
        try {
            DB::beginTransaction();
            $newStaff = new User();
            $newStaff->name = $request->name;
            $newStaff->email = $request->email;
            $newStaff->phone_number = $request->has('phone_number')?"62{$request->phone_number}":null;
            $newStaff->password = Hash::make($request->password);
            $newStaff->remember_token = \Str::random(60);
            $newStaff->save();

            if($request->has('selected_role')){
                $newStaff->assignRole($request->selected_role);
            }
            DB::commit();
            return redirect()->route('staff.index')->with('success',"Staff {$newStaff->name} has been created");
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
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
    public function edit(Request $request, User $staff)
    {
        if($staff->role->slug=='user') return redirect()->route('staff.index')->withErrors("Staff not found");

        $this->data['title'] = "Edit Staff {$staff->name}";
        $this->data['crumbs'][] = [
            'title' => 'Edit Staff',
            'url' => route('staff.edit',[$staff->id]),
        ];
        $this->data['data'] = $staff;
        return view('pages.staff.edit',$this->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $staff)
    {
        $request->validate([
            'name'=>'required|min:3',
            'email'=>'required|email',
            'password'=>'nullable|min:8',
        ]);

        try {
            DB::beginTransaction();
            $staff->name = $request->name;
            $staff->email = $request->email;
            $staff->phone_number = $request->has('phone_number')?"62{$request->phone_number}":null;
            $staff->save();
            DB::commit();
            return redirect()->route('staff.index')->with('success',"Staff \"{$staff->name}\" has been updated");
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            throw $th;
            return redirect()->back()->withErrors(['error' => $th->getMessage()])->withInput();
        }
    }

    public function updatePassword(Request $request, User $staff)
    {
        $request->validate([
            'old_password'=>'required|min:8',
            'password'=>'required|min:8',
            're_password'=>'required|same:password'
        ]);

        try {
            DB::beginTransaction();
            $passwordCheck = Hash::check($request->old_password,$staff->password);
            if(!$passwordCheck) return redirect()->back()->withErrors('Password not match');
    
            $staff->password = Hash::make($request->password);
            $staff->save();
            DB::commit();

            return redirect()->route('staff.edit', $staff->id)->with('success',"Password has been updated");
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            return redirect()->back()->withErrors(['error' => $th->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
