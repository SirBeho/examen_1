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
        Schema::create('terceros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->char('sexo');
            $table->date('nacimiento');
            $table->string('documento');
            $table->unsignedBigInteger('tipo_documento_id');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('email');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->foreign('tipo_documento_id')->references('id')->on('tipos_documentos');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terceros');
    }
};
