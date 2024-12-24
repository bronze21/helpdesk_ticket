<?php

/* 
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
*/

namespace Database\Seeders;

use App\Models\Role;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'isActive'=>1,
                'created_at'=>now(),
                'updated_at'=>now()
            ],
            [
                'name' => 'Staff',
                'slug' => 'staff',
                'isActive'=>1,
                'created_at'=>now(),
                'updated_at'=>now()
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'isActive'=>1,
                'created_at'=>now(),
                'updated_at'=>now()
            ],
        ];

        try{
            DB::beginTransaction();
            Role::insert($roles);
            DB::commit();
            $this->command->info('Success seed roles');
        }catch (\Throwable $th){
            DB::rollBack();
            throw $th;
        }
    }
}
