<?php

namespace Database\Factories;

use App\Models\promoCode;
use App\Models\stateOrder;
use App\Models\typeOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\order>
 */
class OrderFactory extends Factory
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
            'address'=>fake()->address(),
            'phone'=>fake()->phoneNumber(),
            'note'=>fake()->text(),
            'user_id'=>fake()->numberBetween(1,User::count()),
            'type_order_id'=>fake()->numberBetween(1,typeOrder::count()),
            'state_order_id'=>fake()->numberBetween(1,stateOrder::count()),
            'promo_code_id'=>fake()->numberBetween(1,promoCode::count()),



            
        ];
    }
}
