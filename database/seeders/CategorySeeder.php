<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'name' => 'Divisi - Marketing',
                'slug' => 'divisi-marketing',
                'isActive'=>1,
                'created_at'=>now(),
                'updated_at'=>now(),
                'subcategory'=>[
                    'Umum',
                    'Promosi',
                    'Pemasaran'
                ]
            ],
            [
                'name' => 'Divisi - Management',
                'slug' => 'divisi-management',
                'isActive'=>1,
                'created_at'=>now(),
                'updated_at'=>now(),
                'subcategory'=>[
                    'Umum',
                    'Penagihan',
                    'Tunggakan',
                ]
            ],
            [
                'name' => 'Divisi - Finance',
                'slug' => 'divisi-finance',
                'isActive'=>1,
                'created_at'=>now(),
                'updated_at'=>now(),
                'subcategory'=>[
                    'Umum',
                    'Pembayaran',
                    'Cicilan'
                ]
            ],
            [
                'name' => 'Divisi - IT',
                'slug' => 'divisi-it',
                'isActive'=>1,
                'created_at'=>now(),
                'updated_at'=>now(),
                'subcategory'=>[
                    'Umum',
                    'Account',
                    'Website',
                    'Mobile Apps'
                ]
            ],
        ];

        try{
            DB::beginTransaction();
            foreach($datas as $data){
                $subCategory = $data['subcategory'];
                unset($data['subcategory']);
                $seedData = new Category([
                    'name'=>$data['name'],
                    'slug'=>$data['slug'],
                    'isActive'=>$data['isActive']
                ]);
                $seedData->save();
                foreach($subCategory as $sub){
                    $newSubData = new Subcategory([
                        'name' => $sub,
                        'slug' => \Str::slug($seedData->slug.'-'.$sub),
                    ]);
                    $seedData->subCategories()->save($newSubData);
                }
            }
            DB::commit();
            $this->command->info('Success seed categories');
        }catch (\Throwable $th){
            DB::rollBack();
            throw $th;
        }
    }
}
