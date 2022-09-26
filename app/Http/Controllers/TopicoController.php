<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Topico;
use App\Models\Setor;

class TopicoController extends Controller
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
        
        $topicos = Topico::with('criador')->get();
        return view('topico.index', compact('topicos', 'setores_usuario_logado'));
    }

    public function create()
    {
        $setores = Setor::get();
        return view('topico.create', compact('setores'));
    }
    
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $nome_maiusculo = mb_strtoupper($request->nome, 'UTF-8');

            if (Topico::where('nome', $nome_maiusculo)->where('setor_id', $request->setor_id)->exists()) {
                return back()->withErrors('Um tópico com o mesmo nome já existe.');
            } else {
                $setor = new Topico;
                
                $setor->nome = $nome_maiusculo;
                $setor->setor_id = $request->setor_id;
                $setor->user_id = Auth::user()->id;
                
                $setor->save();
            }

            DB::commit();
            return redirect()->route('topico.index')->with('sucesso', 'Tópico criado com sucesso');

        } catch (Throwable $th) {
            DB::rollback();
            dd($th);
            return redirect()->route('topico.index')->with('error', 'Houve um erro ao tentar criar um tópico.');
        }
    }
}
