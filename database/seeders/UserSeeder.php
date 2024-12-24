<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use DB;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $listUser = [
            [
                'name'=>'Bagus',
                'email'=>'bagusharianto.dv@gmail.com',
                'email_verified_at'=>now(),
                'password'=>Hash::make('admin123'),
                'remember_token'=>Str::random(60),
                'created_at'=>now(),
                'updated_at'=>now(),
                'role'=>'admin',
            ],
            [
                'name'=>'admin',
                'email'=>'admin@domain.com',
                'email_verified_at'=>now(),
                'password'=>Hash::make('admin123'),
                'remember_token'=>Str::random(60),
                'created_at'=>now(),
                'updated_at'=>now(),
                'role'=>'admin',
            ],
            [
                'name'=>'staff 1',
                'email'=>'staff_1@domain.com',
                'email_verified_at'=>now(),
                'password'=>Hash::make('staff123'),
                'remember_token'=>Str::random(60),
                'created_at'=>now(),
                'updated_at'=>now(),
                'role'=>'staff',
            ],
            [
                'name'=>'staff 2',
                'email'=>'staff_2@domain.com',
                'email_verified_at'=>now(),
                'password'=>Hash::make('staff123'),
                'remember_token'=>Str::random(60),
                'created_at'=>now(),
                'updated_at'=>now(),
                'role'=>'staff',
            ],
            [
                'name'=>'user 1',
                'email'=>'user_1@domain.com',
                'email_verified_at'=>now(),
                'password'=>Hash::make('user123'),
                'remember_token'=>Str::random(60),
                'created_at'=>now(),
                'updated_at'=>now(),
                'role'=>'user',
            ],
            [
                'name'=>'user 2',
                'email'=>'user_2@domain.com',
                'email_verified_at'=>now(),
                'password'=>Hash::make('user123'),
                'remember_token'=>Str::random(60),
                'created_at'=>now(),
                'updated_at'=>now(),
                'role'=>'user',
            ],
        ];
        try {
            DB::beginTransaction();
            foreach($listUser as $user){
                $role = Role::where('slug',$user['role'])->first();
                unset($user['role']);
                $user = User::create($user);
                $role->users()->attach($user->id);
            }
            DB::commit();
            $this->command->info("Success seed users");
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
