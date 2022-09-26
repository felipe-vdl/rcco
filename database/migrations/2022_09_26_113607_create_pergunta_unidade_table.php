<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerguntaUnidadeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pergunta_unidade', function (Blueprint $table) {
            $table->id();
            
            $table->bigInteger('pergunta_id')   ->unsigned();
            $table->foreign('pergunta_id')->references('id')->on('perguntas')->onDelete('cascade');

            $table->bigInteger('unidade_id')   ->unsigned();
            $table->foreign('unidade_id')->references('id')->on('unidades')->onDelete('cascade');

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
        Schema::dropIfExists('pergunta_unidade');
    }
}
