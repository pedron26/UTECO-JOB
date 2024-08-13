<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerfilesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('perfiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->string('nombre', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->text('experiencia')->nullable();
            $table->text('educacion')->nullable();
            $table->text('habilidades')->nullable();
            $table->string('imagen', 255)->nullable();
            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfiles');
    }
}
