<?php

namespace Database\Seeders;

use App\Models\stateOrder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StateOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            stateOrder::create([
             'name'=>'التهيئة',
            ]);
            stateOrder::create([
                'name'=>'في التوصيل',
               ]);
               stateOrder::create([
                'name'=>'ملغى',
               ]);
               stateOrder::create([
                'name'=>'كامل',
               ]);
            

    }
}
