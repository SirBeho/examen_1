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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('serie');
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('trabajador_id');
            $table->date('fecha');
            $table->unsignedBigInteger('comprobante_id');
            $table->foreign('cliente_id')->references('id')->on('terceros');
            $table->foreign('trabajador_id')->references('id')->on('trabajadores');
            $table->foreign('comprobante_id')->references('id')->on('comprobantes');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
