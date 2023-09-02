<?php

namespace Database\Factories;

use App\Models\Articulo;
use App\Models\Venta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Detalleenta>
 */
class DetalleVentaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
          'venta_id'=>Venta::all()->random(),
          'articulo_id'=>Articulo::inRandomOrder()->first()->id,
          'cantidad'=>fake()->randomNumber(),
          'precio'=> fake()->numberBetween(20, 200),
          'descuento'=> fake()->numberBetween(0, 10),
        ];
    }
}
