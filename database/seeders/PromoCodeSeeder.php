<?php

namespace Database\Seeders;

use App\Models\promoCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromoCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        promoCode::factory(10)->create();
    }
}
