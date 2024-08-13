<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRolesTable extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->notNull();
            $table->timestamps();
        });

        // Insert initial roles
        DB::table('roles')->insert([
            ['nombre' => 'admin'],
            ['nombre' => 'user'],
            ['nombre' => 'empresa'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
}
