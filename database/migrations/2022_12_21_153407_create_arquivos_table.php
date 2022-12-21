<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArquivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arquivos', function (Blueprint $table) {
            $table->id();
            $table->string('nome_origem');
            $table->string('filename');
            $table->string('extension');
            
            $table->bigInteger('pergunta_id')->unsigned();
            $table->foreign('pergunta_id')->references('id')->on('perguntas')->onDelete('cascade');

            $table->bigInteger('resposta_id') ->unsigned();
            $table->foreign('resposta_id')->references('id')->on('respostas')->onDelete('cascade');

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
        Schema::dropIfExists('arquivos');
    }
}
