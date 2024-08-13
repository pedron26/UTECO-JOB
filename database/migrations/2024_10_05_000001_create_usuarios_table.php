<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id(); 
            $table->string('nombre', 255)->notNull();
            $table->string('email', 255)->unique()->notNull();
            $table->string('password', 255)->notNull();
            $table->unsignedBigInteger('rol_id')->notNull(); 
            $table->timestamps();

            // Definición de la clave foránea
            $table->foreign('rol_id')->references('id')->on('roles')->onDelete('cascade');
            $table->enum('tipo_cuenta', ['usuario', 'empresa'])->default('usuario')->notNull();
            $table->enum('estado', ['activo', 'pendiente'])->default('activo')->notNull();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
}
