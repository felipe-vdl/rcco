<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unidades', function (Blueprint $table) {
            $table->id();

            $table->string('nome');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('setor_id')->unsigned();

            $table->timestamps();
        });

        Schema::table('unidades', function($table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
        Schema::table('unidades', function($table) {
            $table->foreign('setor_id')->references('id')->on('setors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unidades');
    }
}
