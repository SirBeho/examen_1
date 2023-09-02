<?php

namespace Database\Seeders;

use App\Models\tiposDocumento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TiposDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        tiposDocumento::create(['descripcion' => 'RNC']);
        tiposDocumento::create(['descripcion' => 'Cedula']);
        tiposDocumento::create(['descripcion' => 'Pasaporte']);
    }
}
