<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'nombre');
            $table->integer('upline');
            $table->integer('patrocinador');
            $table->date('fecha_ingreso');
            $table->string('apellido_paterno', 100);
            $table->string('apellido_materno', 100);
            $table->date('fecha_nac');
            $table->string('ife', 100);
            $table->string('tel_cel', 100);
            $table->string('cp',20);
            $table->string('direccion', 150);
            $table->string('colonia', 100);
            $table->string('delegacion', 100);
            $table->string('estado', 100);
            $table->string('beneficiario', 150);
            $table->string('parentesco', 100);
            $table->date('beneficiario_fecha_nac');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
