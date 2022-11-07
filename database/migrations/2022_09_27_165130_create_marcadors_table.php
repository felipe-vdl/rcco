<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarcadorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marcadors', function (Blueprint $table) {
            $table->id();
            $table->text('nome');
            $table->string('color')->default('#000', 9);
            $table->tinyInteger('is_enabled')->default(1);

            $table->bigInteger('setor_id') ->unsigned();
            $table->foreign('setor_id')->references('id')->on('setors')->onDelete('cascade');

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
        Schema::dropIfExists('marcadors');
    }
}
