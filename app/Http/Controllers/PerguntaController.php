<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Setor;
use App\Models\Topico;
use App\Models\Pergunta;
use App\Models\PerguntaUnidade;

class PerguntaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setores = Setor::get();
        $setores_usuario_logado = [];

        if (Auth::user()->nivel === "Admin") {
            foreach(Auth::user()->setores as $setor) {
                array_push($setores_usuario_logado, $setor->id);
            }

        } else if (Auth::user()->nivel === "Super-Admin") {
            foreach($setores as $setor) {
                array_push($setores_usuario_logado, $setor->id);
            }
        }
        
        $perguntas = Pergunta::with('criador', 'topico')->get();
        
        return view('pergunta.index', compact('perguntas', 'setores_usuario_logado'));
    }

    public function create()
    {
        $setores = Setor::get();
        $setores_usuario_logado = [];

        if (Auth::user()->nivel === "Admin") {
            foreach(Auth::user()->setores as $setor) {
                array_push($setores_usuario_logado, $setor);
            }

        } else if (Auth::user()->nivel === "Super-Admin") {
            foreach($setores as $setor) {
                array_push($setores_usuario_logado, $setor);
            }
        }

        return view('pergunta.create', compact('setores_usuario_logado'));
    }

    public function store(Request $request)
    {
        // TODO: Tipos de formato: text, textarea, checkbox, radio, dropdown.
        // LabelOption: checkbox, radio, dropdown.
    }
}
