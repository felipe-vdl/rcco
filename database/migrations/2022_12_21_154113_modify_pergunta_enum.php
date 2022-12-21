<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPerguntaEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE perguntas MODIFY COLUMN formato ENUM('text', 'textarea', 'checkbox', 'radio', 'dropdown', 'file')");
        DB::statement("ALTER TABLE perguntas MODIFY COLUMN tipo ENUM('number', 'string', 'image', 'document', 'video')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
