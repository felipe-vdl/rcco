<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabelOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('label_options', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            
            $table->bigInteger('pergunta_id')   ->unsigned();
            $table->foreign('pergunta_id')->references('id')->on('perguntas')->onDelete('cascade');

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
        Schema::dropIfExists('label_options');
    }
}
