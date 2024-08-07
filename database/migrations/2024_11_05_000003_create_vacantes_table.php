<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateVacantesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vacantes', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 255);
            $table->text('descripcion');
            $table->date('fecha_apertura');
            $table->date('fecha_cierre');
            $table->string('imagen', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacantes');
    }
}