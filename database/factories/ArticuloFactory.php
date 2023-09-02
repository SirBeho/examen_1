<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Articulo>
 */
class ArticuloFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'codigo'=>fake()->unique()->ean8(),
            'nombre'=>fake()->firstName(),
            'descripcion'=>fake()->text($maxNbChars = 20)  (),
            'precio_c'=>fake()->randomNumber(),
            'precio_v'=>fake()->randomNumber(),
            'strock'=>fake()->randomNumber(),
            'categoria_id'=>Categoria::inRandomOrder()->first()->id,
        ];
    }
}
