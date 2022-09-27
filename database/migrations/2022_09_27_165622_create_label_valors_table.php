<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabelValorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('label_valors', function (Blueprint $table) {
            $table->id();
            
            $table->string('valor')->nullable();

            $table->bigInteger('pergunta_id') ->unsigned();
            $table->foreign('pergunta_id')->references('id')->on('perguntas')->onDelete('cascade');

            $table->bigInteger('resposta_id') ->unsigned();
            $table->foreign('resposta_id')->references('id')->on('respostas')->onDelete('cascade');

            $table->bigInteger('label_option_id') ->unsigned();
            $table->foreign('label_option_id')->references('id')->on('label_options')->onDelete('cascade');

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
        Schema::dropIfExists('label_valors');
    }
}
