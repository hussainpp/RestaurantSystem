<?php

namespace Database\Factories;

use App\Models\item;
use App\Models\order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\orderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quantity'=>fake()->numberBetween(1,5),
            'item_id'=>fake()->numberBetween(1,item::count()),
            'order_id'=>fake()->numberBetween(1,order::count()),

        ];
    }
}
