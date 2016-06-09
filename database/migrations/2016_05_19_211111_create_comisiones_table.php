<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComisionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comisiones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('upline_id');
            $table->integer('patroc_id');
            $table->integer('asignado_id');
            $table->string('type',50);
            $table->float('amount');
            $table->date('date_created');
            $table->date('date_payed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('comisiones');
    }
}
