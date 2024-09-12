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
        //
        Schema::table('gestores',function(Blueprint $table){
            $table->boolean('copia_correo')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('gestores',function(Blueprint $table){
            $table->dropColumn('copia_correo')->nullable(true);
        });
    }
};
