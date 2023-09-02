<?php

namespace Database\Seeders;

use App\Models\Tercero;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TerceroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tercero::factory(10)->create();
    }
}
