<?php

namespace Database\Factories;

use App\Models\menu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
              'name'=>fake()->name(),
              'price'=>fake()->numberBetween(1000,30000),
              'image'=>fake()->shuffleString(),
              'details'=>fake()->text(),
              'active'=>1,
              'preparation_time'=>fake()->numberBetween(10,60),
              'menu_id'=>fake()->numberBetween(1,menu::count())
        ];
    }
}
