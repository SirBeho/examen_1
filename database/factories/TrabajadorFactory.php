<?php

namespace Database\Factories;

use App\Models\Tercero;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trabajador>
 */
class TrabajadorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

       

            'tercero_id'=>Tercero::inRandomOrder()->first()->id,
            'usuario'=>fake()->userName(),
            'contra'=>fake()->password(),
           
        ];
    }
}
