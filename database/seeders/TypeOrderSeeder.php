<?php

namespace Database\Seeders;

use App\Models\typeOrder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleList=['حضوري','هاتف','الكتروني'];
        foreach($roleList as $role){
            typeOrder::create([
             'name'=>$role
            ]);
        }}
}
