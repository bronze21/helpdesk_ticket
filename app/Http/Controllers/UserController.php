<?php

/* 
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
*/

namespace App\Http\Controllers;

use App\Models\User;
use DataTables;
use DB;
use Hash;
use Illuminate\Http\Request;
use Log;
use Str;
use Yajra\DataTables\Contracts\DataTable;

class UserController extends Controller
{
    public function __construct() {
        parent::__construct();
        $this->data['crumbs'][1] = [
            'title' => 'User',
            'url' => route('users.index'),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->data['title'] = "List User";
        
        $this->data['datas'] = User::queryRole('user')->simplePaginate(10);
        return view('pages.users.index',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->data['title'] = 'Create New User';
        $this->data['crumbs'][] = [
            'title' => 'Create New User',
            'url' => route('users.create'),
        ];
        return view('pages.users.create',$this->data);
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
            $newUser = new User();
            $newUser->name = $request->name;
            $newUser->email = $request->email;
            $newUser->phone_number = $request->has('phone_number')?"62{$request->phone_number}":null;
            $newUser->password = Hash::make($request->password);
            $newUser->remember_token = Str::random(60);
            $newUser->save();

            $newUser->assignRole('user');
            DB::commit();
            return redirect()->route('users.index')->with('success',"User {$newUser->name} has been created");
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
    public function edit(Request $request, User $user)
    {
        if($user->role->slug!='user') return redirect()->route('users.index')->withErrors("User not found");

        $this->data['title'] = "Edit User {$user->name}";
        $this->data['crumbs'][] = [
            'title' => 'Edit User',
            'url' => route('users.edit',[$user->id]),
        ];
        $this->data['data'] = $user;
        return view('pages.users.edit',$this->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'=>'required|min:3',
            'email'=>'required|email',
            'password'=>'nullable|min:8',
        ]);

        try {
            DB::beginTransaction();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone_number = $request->has('phone_number')?"62{$request->phone_number}":null;
            $user->save();
            DB::commit();
            return redirect()->route('users.index')->with('success',"User \"{$user->name}\" has been updated");
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            throw $th;
            return redirect()->back()->withErrors(['error' => $th->getMessage()])->withInput();
        }
    }

    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'old_password'=>'required|min:8',
            'password'=>'required|min:8',
            're_password'=>'required|same:password'
        ]);

        try {
            DB::beginTransaction();
            $passwordCheck = Hash::check($request->old_password,$user->password);
            if(!$passwordCheck) return redirect()->back()->withErrors('Password not match');
    
            $user->password = Hash::make($request->password);
            $user->save();
            DB::commit();

            return redirect()->route('users.edit', $user->id)->with('success',"Password has been updated");
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            return redirect()->back()->withErrors(['error' => $th->getMessage()])->withInput();
        }
    }

    public function withoutRole(Request $request)
    {
        $users = User::queryWithoutRole();
        return DataTables::of($users)
        ->addIndexColumn()
        ->addColumn('assignRole',function($user){
            return '<button type="button" class="btn btn-sm btn-outline-dark user_assign_role" x-on:click="assignRole($el,'.$user->id.')">Assign Role</button>';
        })
        ->rawColumns(['checkbox','assignRole'])
        ->make(true);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

