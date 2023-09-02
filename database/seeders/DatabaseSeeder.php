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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(TiposDocumentoSeeder::class);
        $this->call(TerceroSeeder::class);
        $this->call(TrabajadorSeeder::class);
        $this->call(CategoriaSeeder::class);
        $this->call(ArticuloSeeder::class);
        $this->call(ComprobanteSeeder::class);
        $this->call(VentaSeeder::class);
        $this->call(DetalleVentaSeeder::class);


    }
}
