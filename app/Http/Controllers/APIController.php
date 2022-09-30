<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Unidade;
use App\Models\Topico;
use App\Models\Resposta;
use App\Models\Pergunta;
use App\Models\User;

class APIController extends Controller
{
    public function unidades(Request $request) {
        $DBunidades = Unidade::where('setor_id', (int)$request->query('setor_id'))->get();

        $unidades = [];
        if (Auth::user()->nivel === "Admin" OR Auth::user()->nivel === "Super-Admin") {
            foreach ($DBunidades as $unidade) {
                array_push($unidades, $unidade);
            }

        } else if (Auth::user()->nivel === "User") {
            foreach (Auth::user()->unidades as $unidade) {
                if ($unidade->setor_id == (int)$request->query('setor_id')) {
                    array_push($unidades, $unidade);
                }
            }
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

    public function gerarTabela (Request $request)
    {
        $respostas = Resposta::with('criador', 'unidade', 'pergunta', 'label_valors')
        ->where('unidade_id', $request->query('unidade_id'))
        ->groupBy('data')
        ->get();

        $user = User::find((int)$request->query('user_id'));

        $dados = (object) array(
            'tabela'  => $respostas,
            'usuario' => $user,
        );

        return json_encode($dados);
    }

    public function formulario (Request $request)
    {
        $unidade_id = $request->query('unidade_id');
        $setor_id = $request->query('setor_id');

        $topicos = Topico::with(['perguntas' => function($query) use ($unidade_id) {
                $query->whereHas('unidades', function($q) use ($unidade_id) {
                    $q->where('unidades.id', $unidade_id);
                })->orderBy('index', 'DESC')->orderBy('created_at', 'ASC');
            }, 'setor', 'perguntas.unidades', 'perguntas.label_options'])
            ->whereHas('perguntas.unidades', function($query) use ($unidade_id) {
                $query->where('unidades.id', $unidade_id);
            })->get();

        // dd($topicos);
        return json_encode($topicos);
    }
}
