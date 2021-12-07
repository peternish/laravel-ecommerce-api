<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'value' => rand(1, 99999),
            'status' => rand(0, 1),
            'user_id' => User::all()->random()->id
        ];
    }
}
