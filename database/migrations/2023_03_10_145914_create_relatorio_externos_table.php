<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelatorioExternosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relatorio_externos', function (Blueprint $table) {
            $table->id();

            $table->string('data');
            $table->string('nome');
            $table->string('filename');
            $table->string('extensao', 16);

            $table->bigInteger('unidade_id') ->unsigned();
            $table->foreign('unidade_id')->references('id')->on('unidades')->onDelete('cascade');

            $table->bigInteger('user_id') ->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('relatorio_externos');
    }
}
