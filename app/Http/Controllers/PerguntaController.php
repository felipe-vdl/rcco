<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Setor;
use App\Models\Topico;
use App\Models\Pergunta;
use App\Models\PerguntaUnidade;
use App\Models\LabelOption;

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
        try {
            DB::beginTransaction();

            $pergunta = new Pergunta;
            $pergunta->nome             = $request->nome;
            $pergunta->tipo             = $request->tipo;
            $pergunta->formato          = $request->formato;
            $pergunta->is_required      = $request->is_required;
            $pergunta->is_enabled       = 1;
            $pergunta->index            = 0;
            $pergunta->user_id          = Auth::user()->id;
            $pergunta->topico_id        = $request->topico_id;
            $pergunta->save();

            if ($request->formato === 'checkbox') {
                foreach($request->checkboxvalue as $nome) {
                    LabelOption::create([
                        'pergunta_id' => $pergunta->id,
                        'nome'        => $nome
                    ]);
                }

            } else if ($request->formato === 'radio') {
                foreach($request->radiovalue as $nome) {
                    LabelOption::create([
                        'pergunta_id' => $pergunta->id,
                        'nome'        => $nome
                    ]);
                }

            } else if ($request->formato === 'dropdown') {
                foreach($request->dropdownvalue as $nome) {
                    LabelOption::create([
                        'pergunta_id' => $pergunta->id,
                        'nome'        => $nome
                    ]);
                }
            }

            $unidades = explode(',', $request->unidades_id);
            foreach ($unidades as $unidade_id) {
                PerguntaUnidade::create([
                    'pergunta_id' => $pergunta->id,
                    'unidade_id'  => $unidade_id
                ]);
            }

            DB::commit();
            return redirect()->route('pergunta.index')->with('sucesso', 'Pergunta criada com sucesso');

        } catch (Throwable $th) {
            DB::rollback();
            dd($th);
            return back()->withErrors('Houve um erro ao tentar criar uma pergunta.');
        }
    }

    public function is_enabled(Request $request)
    {
        DB::beginTransaction();

        $pergunta = Pergunta::where('id', $request->pergunta_id)->first();
        $pergunta->is_enabled = $request->is_enabled;
        $pergunta->update();

        DB::commit();
        return redirect()->back()->with('sucesso', 'Pergunta editada com sucesso.');
    }

    public function setIndex() {

    }
}