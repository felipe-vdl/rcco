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
        
        $unidades = Unidade::with('criador', 'setor')->get();
        
        return view('unidade.index', compact('unidades', 'setores_usuario_logado'));
    }

    public function create()
    {
        $setores = Setor::get();

        return view('unidade.create', compact('setores'));
    }

    public function store(Request $request)
    {
        $nome_maiusculo = mb_strtoupper($request->nome, 'UTF-8');
        
        if(Unidade::where('nome', $nome_maiusculo)->exists()) {
            return back()->withErrors('Uma unidade com o mesmo nome já existe.');
        };

        $unidade = new Unidade;
        $unidade->nome  = $nome_maiusculo;
        $unidade->user_id = Auth::user()->id;
        $unidade->setor_id = $request->setor_id;
        $unidade->save();

        return redirect()->route('unidade.index')->with('sucesso','Unidade criada com sucesso.');
    }

    public function is_enabled(Request $request)
    {
        DB::beginTransaction();

        $unidade = Unidade::where('id', $request->unidade_id)->first();
        $unidade->is_enabled = $request->is_enabled;
        $unidade->update();

        DB::commit();
        return redirect()->back()->with('sucesso', 'Operação efetuada com sucesso.');
    }
}
