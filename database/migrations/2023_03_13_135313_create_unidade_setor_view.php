<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnidadeSetorView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
          CREATE VIEW semas_unidades AS
          (
            SELECT unidades.id, unidades.nome, unidades.setor_id, unidades.is_enabled
            FROM unidades
            INNER JOIN setors ON setors.id = unidades.setor_id

            WHERE setors.nome = 'SEMAS'
            AND unidades.is_enabled = 1
          )
        ");

        DB::statement("
          CREATE VIEW semed_unidades AS
          (
            SELECT unidades.id, unidades.nome, unidades.setor_id, unidades.is_enabled
            FROM unidades
            INNER JOIN setors ON setors.id = unidades.setor_id

            WHERE setors.nome = 'SEMED'
            AND unidades.is_enabled = 1
          )
        ");

        DB::statement("
          CREATE VIEW semus_unidades AS
          (
            SELECT unidades.id, unidades.nome, unidades.setor_id, unidades.is_enabled
            FROM unidades
            INNER JOIN setors ON setors.id = unidades.setor_id

            WHERE setors.nome = 'SEMUS'
            AND unidades.is_enabled = 1
          )
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      DB::statement('DROP VIEW IF EXISTS semas_unidades');
      DB::statement('DROP VIEW IF EXISTS semed_unidades');
      DB::statement('DROP VIEW IF EXISTS semus_unidades');
    }
}
