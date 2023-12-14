<?php

namespace Database\Seeders;

use App\Models\menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       menu::create([
         'name'=>'rice',
       ]);
       menu::create([
        'name'=>'pizza'
       ]);
       menu::create([
        'name'=>'sandwich'
       ]);
    }
}
