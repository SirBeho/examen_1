<?php

namespace Database\Factories;

use App\Models\tiposDocumento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tercero>
 */
class TerceroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre'=>fake()->firstName(),
            'apellido'=>fake()->lastName(),
            'sexo'=>fake()->randomElement(['m', 'f']),
            'nacimiento'=>fake()->date(),
            'documento'=>fake()->ean8(),
            'tipo_documento_id'=>tiposDocumento::inRandomOrder()->first()->id,
            'direccion'=>fake()->address(),
            'telefono'=>fake()->phoneNumber(),
            'email'=>fake()->email()
        ];
    }
}
