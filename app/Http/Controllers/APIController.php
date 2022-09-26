<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unidade;
use App\Models\Topico;

class APIController extends Controller
{
    public function unidades(Request $request) {
        $DBunidades = Unidade::where('setor_id', (int)$request->query('setor_id'))->get();

        $unidades = [];
        foreach ($DBunidades as $unidade) {
            array_push($unidades, $unidade);
        }

        return $unidades;
    }

    public function topicos(Request $request) {
        $DBtopicos = Topico::where('setor_id', (int)$request->query('setor_id'))->get();

        $topicos = [];
        foreach ($DBtopicos as $topico) {
            array_push($topicos, $topico);
        }

        return $topicos;
    }
}
