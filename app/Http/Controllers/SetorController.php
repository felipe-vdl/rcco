<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Setor;

class SetorController extends Controller
{
    public function index()
    {
        $setores = Setor::with('criador')->get();
        return view('setor.index', compact('setores'));
    }

    public function create()
    {
        return view('setor.create');
    }
    
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $nome_maiusculo = mb_strtoupper($request->nome, 'UTF-8');

            if (Setor::where('nome', $nome_maiusculo)->exists()) {
                return back()->withErrors('Um setor com o mesmo nome já existe.');
            } else {
                $setor = new Setor;
                
                $setor->nome = $nome_maiusculo;
                $setor->user_id = Auth::user()->id;
                
                $setor->save();
            }

            DB::commit();
            return redirect()->route('setor.index')->with('sucesso', 'Setor criado com sucesso');

        } catch (Throwable $th) {
            DB::rollback();
            dd($th);
            return redirect()->route('setor.index')->with('error', 'Houve um erro ao tentar criar um setor.');
        }
    }
}