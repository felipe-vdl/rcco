<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Unidade;
use App\Models\Setor;

class UnidadeController extends Controller
{
    public function index()
    {
        $unidades = Unidade::with('criador', 'setor')->get();
        return view('unidade.index', compact('unidades'));
    }

    public function create()
    {
        $setores = Setor::get();

        $setores_usuario_logado = [];

        $checa_setor = Auth::user()->setores;

        foreach($checa_setor as $setor) {
            $str = Setor::where('nome', $setor->nome)->first();
            array_push($setores_usuario_logado, $str->nome);
        }

        return view('unidade.create', compact('setores', 'setores_usuario_logado'));
    }

    public function store(Request $request)
    {
        $nome_maiusculo = mb_strtoupper($request->nome, 'UTF-8');

        if(Unidade::where('nome', $nome_maiusculo)->exists()) {
            return back()->withErrors('Uma unidade com o mesmo nome já existe.');
        };

        $setor = Setor::where('nome', $request->setor)->get();

        $unidade = new Unidade;
        $unidade->nome  = $nome_maiusculo;
        $unidade->user_id = Auth::user()->id;
        $unidade->setor_id = $setor[0]->id;
        $unidade->save();

        return redirect()->route('unidade.index')->with('sucesso','Unidade criada com sucesso.');
    }
}
