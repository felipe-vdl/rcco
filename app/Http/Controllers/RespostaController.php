<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use PDF;
use App\Models\Setor;
use App\Models\Unidade;
use App\Models\Pergunta;
use App\Models\Resposta;
use App\Models\LabelOption;
use App\Models\LabelValor;

class RespostaController extends Controller
{
    public function index()
    {
        $setores = Setor::get();
        $setores_usuario_logado = [];

        if (Auth::user()->nivel !== "Super-Admin") {
            foreach(Auth::user()->setores as $setor) {
                array_push($setores_usuario_logado, $setor);
            }

        } else if (Auth::user()->nivel === "Super-Admin") {
            foreach ($setores as $setor) {
                array_push($setores_usuario_logado, $setor);
            }
        }

        return view('resposta.index', compact('setores_usuario_logado', 'setores'));
    }

    public function create()
    {
        $setores = Setor::get();
        $setores_usuario_logado = [];

        if (Auth::user()->nivel !== "Super-Admin") {
            foreach(Auth::user()->setores as $setor) {
                array_push($setores_usuario_logado, $setor);
            }

        } else if (Auth::user()->nivel === "Super-Admin") {
            foreach ($setores as $setor) {
                array_push($setores_usuario_logado, $setor);
            }
        }

        return view('resposta.create', compact('setores_usuario_logado'));
    }

    public function store(Request $request)
    {
        dd($request->all());
        // 1. foreach topico { foreach (resposta) {new Resposta} };
        // 2. data, unidade_id, pergunta_id, topico_id, user_id
        // 3.1 if(formato === checkbox) { save(), foreach(checkboxvalues) {create LabelValor} }
        // 3.2 else if(text/textarea/radio/dropdown) { valor, save() }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function enviar(Request $request)
    {
        //
    }

    public function GerarPDF (Request $request)
    {
        //
    }
}
