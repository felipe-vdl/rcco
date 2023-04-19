<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Carbon\Carbon;
use App\Models\Setor;
use App\Models\Unidade;
use App\Models\Pergunta;
use App\Models\Resposta;
use App\Models\Marcador;
use App\Models\Topico;
use App\Models\LabelOption;
use App\Models\LabelValor;
use App\Models\Comentario;

class ComentarioController extends Controller
{
    public function create(Request $request, $id)
    {
        $data = $request->query('data');
        $unidade = Unidade::find($id);

        $topicos = Topico::with(['respostas' => function($query) use ($id, $data, $request) {
            $query->whereHas('unidade', function($q) use($id) {
                $q->where('id', $id);
            })->where([['data', $data], ['user_id', $request->user_id]]);
        }, 'respostas.pergunta', 'respostas.label_valors', 'respostas.marcador'])->get();

        foreach ($topicos as $topico) {
            $topico->respostas = $topico->respostas->sortBy('pergunta.created_at')->sortByDesc('pergunta.index')->values();
        }

        $resposta = Resposta::with('marcador')->where([['data', $data], ['user_id', $request->user_id], ['unidade_id', $id]])->first();
        $marcador;
        $criador = $resposta->criador;

        if(isset($resposta->marcador)) {
            $marcador = $resposta->marcador;
        } else {
            $marcador = "";
        }

        foreach ($topicos as $topico) {
            if ($topico->respostas->count() > 0) {
                $respostaSample = $topico->respostas[0];
            }
        }

        $comentarios = Comentario::with('criador')->where(['data' => $data, 'relator_id' => $request->user_id, 'unidade_id' => $id])->get();

        return view('comentario.create', compact('data', 'respostaSample', 'unidade', 'topicos', 'marcador', 'criador', 'comentarios'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $comentario = Comentario::create([
                'content' => $request->content,
                'data' => $request->data,
                'unidade_id' => $request->unidade_id,
                'relator_id' => $request->relator_id,
                'user_id' => Auth::user()->id
            ]);

            DB::commit();
            return redirect()->route('comentario.create', ['id' => $request->unidade_id, 'data' => $request->data, 'user_id' => $request->relator_id]);
            
        } catch (Throwable $th) {
            DB::rollback();
            dd($th);
            return back()->withErrors('Ocorreu um erro ao tentar salvar o comentário.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $comentario = Comentario::find($id);

            if (Auth::user()->id === $comentario->user_id) {
                $comentario->delete();
            } else {
                return back()->withErrors('Desculpe, apenas o autor deste comentário pode excluí-lo.');
            };

            DB::commit();
            return back()->with('sucesso', 'Comentário criado com sucesso.');
            
        } catch (Throwable $th) {
            DB::rollback();
            dd($th);
            return back()->withErrors('Ocorreu um erro ao tentar deletar o comentário.');
        }
    }
}
