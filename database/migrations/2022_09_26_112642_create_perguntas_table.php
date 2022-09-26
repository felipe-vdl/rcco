<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerguntasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perguntas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->enum('formato', ['text', 'textarea', 'checkbox', 'radio', 'tom-select']);
            $table->enum('tipo', ['number', 'text']);
            $table->tinyInteger('is_required');
            $table->tinyInteger('is_enabled');
            
            $table->bigInteger('topico_id') ->unsigned();
            $table->foreign('topico_id')->references('id')->on('topicos')->onDelete('cascade');

            $table->bigInteger('user_id')   ->unsigned();
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
        Schema::dropIfExists('perguntas');
    }
}
