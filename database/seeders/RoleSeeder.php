<?php

namespace Database\Seeders;

use App\Models\role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleList=['مدیر','موظف','کاشیر','طباخ','مساعد طباخ','خدمة توصیل','عامل نظافة'];
        foreach($roleList as $role){
           role::create([
            'name'=>$role
           ]);
        }
    }
}
