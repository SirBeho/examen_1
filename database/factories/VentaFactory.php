<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Comprobante;
use App\Models\Comprobantes;
use App\Models\Tercero;
use App\Models\Trabajador;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Venta>
 */
class VentaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'serie'=>fake()->word(),
            'cliente_id'=>Tercero::inRandomOrder()->first()->id,
            'trabajador_id'=>Trabajador::inRandomOrder()->first()->id,
            'fecha'=>fake()->date(),
            'comprobante_id'=>Comprobante::inRandomOrder()->first()->id,
        ];
    }
}
