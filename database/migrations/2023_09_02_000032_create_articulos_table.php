<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->id();
           
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->string('descripcion');
            $table->string('precio_c');
            $table->string('precio_v');
            $table->unsignedBigInteger('categoria_id');
            $table->string('strock');
            $table->foreign('categoria_id')->references('id')->on('categorias');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulos');
    }
};
