<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Marcador;
use App\Models\Setor;

class MarcadorController extends Controller
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
        
        $marcadores = Marcador::with('criador')->get();
        return view('marcador.index', compact('marcadores', 'setores_usuario_logado'));
    }

    public function create()
    {
        $setores = Setor::get();
        return view('marcador.create', compact('setores'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $nome_maiusculo = mb_strtoupper($request->nome, 'UTF-8');

            if (Marcador::where('nome', $nome_maiusculo)->where('setor_id', $request->setor_id)->exists()) {
                return back()->withErrors('Um marcador com o mesmo nome já existe.');
            } else {
                $setor = new Marcador;
                
                $setor->nome = $nome_maiusculo;
                $setor->setor_id = $request->setor_id;
                $setor->user_id = Auth::user()->id;
                
                $setor->save();
            }

            DB::commit();
            return redirect()->route('marcador.index')->with('sucesso', 'Marcador criado com sucesso');

        } catch (Throwable $th) {
            DB::rollback();
            dd($th);
            return redirect()->route('marcador.index')->with('error', 'Houve um erro ao tentar criar um marcador.');
        }
    }

    public function is_enabled(Request $request)
    {
        DB::beginTransaction();

        $marcador = Marcador::where('id', $request->marcador_id)->first();
        $marcador->is_enabled = $request->is_enabled;
        $marcador->update();

        DB::commit();
        return redirect()->back()->with('sucesso', 'Operação efetuada com sucesso.');
    }
}
