<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id')->notNull(); 
            $table->string('nombre_empresa', 255)->nullable(); 
            $table->string('direccion_empresa', 255)->nullable(); 
            $table->string('telefono_empresa', 20)->nullable(); 
            $table->string('sector_empresa', 255)->nullable();
            $table->timestamps(); 

            // Definición de clave foránea
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
}
