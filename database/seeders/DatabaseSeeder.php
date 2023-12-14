<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(MenuSeeder::class);
        $this->call(ItemSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(TypeOrderSeeder::class);
        $this->call(StateOrderSeeder::class);
        $this->call(PromoCodeSeeder::class);
        \App\Models\User::factory(20)->create();
        $this->call(OrderSeeder::class);
        $this->call(OrderItemSeeder::class);
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
