<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRespostasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('respostas', function (Blueprint $table) {
            $table->id();
            $table->string('data');
            $table->string('valor')->nullable();

            $table->bigInteger('pergunta_id') ->unsigned();
            $table->foreign('pergunta_id')->references('id')->on('perguntas')->onDelete('cascade');

            $table->bigInteger('topico_id') ->unsigned();
            $table->foreign('topico_id')->references('id')->on('topicos')->onDelete('cascade');

            $table->tinyInteger('status')->default(0);
            $table->string('data_envio')->nullable();

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
        Schema::dropIfExists('respostas');
    }
}
