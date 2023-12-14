<?php

namespace Database\Seeders;

use App\Models\orderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        orderItem::factory(15)->create();
    }
}
