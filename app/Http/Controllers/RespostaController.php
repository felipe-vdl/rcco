<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use PDF;
use Carbon\Carbon;
use App\Models\Setor;
use App\Models\Unidade;
use App\Models\Pergunta;
use App\Models\Resposta;
use App\Models\Marcador;
use App\Models\Topico;
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
        DB::beginTransaction();
        try {
            foreach($request->topicos as $chave => $topico) {
                if (isset($topico["textos_simples"])) {
                    foreach($topico["textos_simples"] as $chave => $texto) {
                        if(!Resposta::where([['data', $request->data], ['user_id', Auth::user()->id]])
                        ->where('unidade_id', $request->unidade_id)
                        ->where('pergunta_id', $texto["pergunta_id"])
                        ->exists()) {
                            $resposta = new Resposta;
                            $resposta->data = $request->data;
                            $resposta->unidade_id = $request->unidade_id;
                            $resposta->valor = $texto["valor"];
                            $resposta->status = 0;
                            $resposta->pergunta_id = $texto["pergunta_id"];
                            $resposta->topico_id = $texto["topico_id"];
                            $resposta->user_id = Auth::user()->id;
                            if(isset($request->marcador_id)) {
                                $resposta->marcador_id = $request->marcador_id;
                            }
                            $resposta->save();
                        }
                    }
                }
                if (isset($topico["textos_grandes"])) {
                    foreach($topico["textos_grandes"] as $chave => $texto) {
                        if(!Resposta::where([['data', $request->data], ['user_id', Auth::user()->id]])
                        ->where('unidade_id', $request->unidade_id)
                        ->where('pergunta_id', $texto["pergunta_id"])
                        ->exists()) {
                            $resposta = new Resposta;
                            $resposta->data = $request->data;
                            $resposta->unidade_id = $request->unidade_id;
                            $resposta->valor = $texto["valor"];
                            $resposta->status = 0;
                            $resposta->pergunta_id = $texto["pergunta_id"];
                            $resposta->topico_id = $texto["topico_id"];
                            $resposta->user_id = Auth::user()->id;
                            if(isset($request->marcador_id)) {
                                $resposta->marcador_id = $request->marcador_id;
                            }
                            $resposta->save();
                        }
                    }
                }
                if (isset($topico["dropdowns"])) {
                    foreach($topico["dropdowns"] as $chave => $dropdown) {
                        if(!Resposta::where([['data', $request->data], ['user_id', Auth::user()->id]])
                        ->where('unidade_id', $request->unidade_id)
                        ->where('pergunta_id', $dropdown["pergunta_id"])
                        ->exists()) {
                            $resposta = new Resposta;
                            $resposta->data = $request->data;
                            $resposta->unidade_id = $request->unidade_id;
                            $resposta->valor = $dropdown["valor"];
                            $resposta->status = 0;
                            $resposta->pergunta_id = $dropdown["pergunta_id"];
                            $resposta->topico_id = $dropdown["topico_id"];
                            $resposta->user_id = Auth::user()->id;
                            if(isset($request->marcador_id)) {
                                $resposta->marcador_id = $request->marcador_id;
                            }
                            $resposta->save();
                        }
                    }
                }
                if (isset($topico["radios"])) {
                    foreach($topico["radios"] as $chave => $radio) {
                        if(!Resposta::where([['data', $request->data], ['user_id', Auth::user()->id]])
                        ->where('unidade_id', $request->unidade_id)
                        ->where('pergunta_id', $radio["pergunta_id"])
                        ->exists()) {
                            $resposta = new Resposta;
                            $resposta->data = $request->data;
                            $resposta->unidade_id = $request->unidade_id;
                            if (isset($radio["valor"])) {
                                $resposta->valor = $radio["valor"];
                            } else {
                                $resposta->valor = '';
                            }
                            $resposta->status = 0;
                            $resposta->pergunta_id = $radio["pergunta_id"];
                            $resposta->topico_id = $radio["topico_id"];
                            $resposta->user_id = Auth::user()->id;
                            if(isset($request->marcador_id)) {
                                $resposta->marcador_id = $request->marcador_id;
                            }
                            $resposta->save();
                        }
                    }
                }
                if (isset($topico["checkboxes"])) {
                    foreach($topico["checkboxes"] as $chave => $checkbox) {
                        if(!Resposta::where([['data', $request->data], ['user_id', Auth::user()->id]])
                        ->where('unidade_id', $request->unidade_id)
                        ->where('pergunta_id', $checkbox[0]["pergunta_id"])
                        ->exists()) {
                            $resposta = new Resposta;
                            $resposta->data = $request->data;
                            $resposta->unidade_id = $request->unidade_id;
                            $resposta->valor = "";
                            $resposta->status = 0;
                            $resposta->pergunta_id = $checkbox[0]["pergunta_id"];
                            $resposta->topico_id = $checkbox[0]["topico_id"];
                            $resposta->user_id = Auth::user()->id;
                            if(isset($request->marcador_id)) {
                                $resposta->marcador_id = $request->marcador_id;
                            }
                            $resposta->save();

                            foreach($checkbox as $chave => $option) {
                                $cbxValor = new LabelValor;
                                $cbxValor->valor             = $option["valor"];
                                $cbxValor->pergunta_id       = $option["pergunta_id"];
                                $cbxValor->resposta_id       = $resposta->id;
                                $cbxValor->label_option_id   = $option["label_option_id"];
                                $cbxValor->save();
                            }
                        }
                    }
                }
            }
            DB::commit();
            return redirect()->route('resposta.index', ['unidade_id' => $request->unidade_id])->with('sucesso', 'Formulário criado com sucesso.');

        } catch (Throwable $th) {
            DB::rollback();
            return back()->withErrors('Ocorreu um erro ao tentar enviar o formulário.');
        }
    }

    public function show(Request $request, $id)
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

        return view('resposta.show', compact('respostaSample', 'unidade', 'topicos', 'marcador', 'criador'));
    }

    public function edit(Request $request, $id)
    {
        $data = $request->query('data');
        $unidade = Unidade::find($id);
        $user_id = Auth::user()->id;

        $topicos = Topico::with(['respostas' => function($query) use ($id, $data, $user_id) {
            $query->whereHas('unidade', function($q) use($id) {
                $q->where('id', $id);
            })->where([['data', $data], ['user_id', $user_id]]);
        }, 'respostas.pergunta', 'respostas.label_valors', 'respostas.marcador'])->get();

        foreach ($topicos as $topico) {
            $topico->respostas = $topico->respostas->sortBy('pergunta.created_at')->sortByDesc('pergunta.index')->values();
        }

        $marcadores = Marcador::where([['setor_id', $unidade->setor_id], ['is_enabled', 1]])->get();
        $resposta = Resposta::with('marcador')->where([['data', $data], ['user_id', $user_id], ['unidade_id', $id]])->first();
        $marcador_atual_id;

        if(isset($resposta->marcador)) {
            $marcador_atual_id = $resposta->marcador->id;
        } else {
            $marcador_atual_id = "";
        }

        return view('resposta.edit', compact('data', 'unidade', 'topicos', 'marcadores', 'marcador_atual_id'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            foreach($request->topicos as $chave => $topico) {
                if(isset($topico["textos_simples"])) {
                    foreach($topico["textos_simples"] as $chave => $input) {
                        if ($resposta->status === 1) {
                            DB::rollback();
                            return back()->withErrors('Não é permitido editar formulários enviados.');
                        } else {
                            $resposta->valor = $input["valor"];
                            $resposta->modified_at = Carbon::now('America/Sao_Paulo')->format('Y-m-d H:i:s');
                            if(isset($request->marcador_id)) {
                                $resposta->marcador_id = $request->marcador_id;
                            } else {
                                $resposta->marcador_id = null;
                            }
                            $resposta = Resposta::find($input["resposta_id"]);
                            $resposta->save();
                        }
                    }
                }
                if(isset($topico["textos_grandes"])) {
                    foreach($topico["textos_grandes"] as $chave => $input) {
                        $resposta = Resposta::find($input["resposta_id"]);
                        $resposta->valor = $input["valor"];
                        if(isset($request->marcador_id)) {
                            $resposta->marcador_id = $request->marcador_id;
                        } else {
                            $resposta->marcador_id = null;
                        }
                        $resposta->modified_at = Carbon::now('America/Sao_Paulo')->format('Y-m-d H:i:s');
                        $resposta->save();
                    }
                }
                if(isset($topico["radios"])) {
                    foreach($topico["radios"] as $chave => $input) {
                        $resposta = Resposta::find($input["resposta_id"]);
                        if (isset($input["valor"])) {
                            $resposta->valor = $input["valor"];
                        } else {
                            $resposta->valor = '';
                        }
                        if(isset($request->marcador_id)) {
                            $resposta->marcador_id = $request->marcador_id;
                        } else {
                            $resposta->marcador_id = null;
                        }
                        $resposta->modified_at = Carbon::now('America/Sao_Paulo')->format('Y-m-d H:i:s');
                        $resposta->save();
                    }
                }
                if(isset($topico["dropdowns"])) {
                    foreach($topico["dropdowns"] as $chave => $input) {
                        $resposta = Resposta::find($input["resposta_id"]);
                        $resposta->valor = $input["valor"];
                        if(isset($request->marcador_id)) {
                            $resposta->marcador_id = $request->marcador_id;
                        } else {
                            $resposta->marcador_id = null;
                        }
                        $resposta->modified_at = Carbon::now('America/Sao_Paulo')->format('Y-m-d H:i:s');
                        $resposta->save();
                    }
                }
                if(isset($topico["checkboxes"])) {
                    foreach($topico["checkboxes"] as $chave => $checkboxList) {
                        $resposta = Resposta::find($checkboxList[0]["resposta_id"]);
                        if(isset($request->marcador_id)) {
                            $resposta->marcador_id = $request->marcador_id;
                        } else {
                            $resposta->marcador_id = null;
                        }
                        
                        foreach($checkboxList as $chave => $input) {
                            $resposta->modified_at = Carbon::now('America/Sao_Paulo')->format('Y-m-d H:i:s');
                            $resposta->save();
                            $label_valor = LabelValor::find($input["label_valor_id"]);
                            $label_valor->valor = $input["valor"];
                            $label_valor->save();
                        }
                    }
                }
            }
            DB::commit();
            return redirect()->route('resposta.index', ['unidade_id' => $request->unidade_id])->with('sucesso', 'Formulário alterado com sucesso.');

        } catch (Throwable $th) {
            DB::rollback();
            return back()->withErrors('Ocorreu um erro ao tentar atualizar as respostas.');
        }
    }

    public function enviar(Request $request)
    {
        DB::beginTransaction();
        $respostas = Resposta::where([['unidade_id', $request->unidade_id], ['user_id', $request->user_id], ['data', $request->data]])->get();
        foreach($respostas as $resposta) {
            if($request->envio_status === 0 AND Auth::user()->nivel === 'Super-Admin') {
                $resposta->status = $request->envio_status;
                $resposta->data_envio = "";
                $resposta->update();

            } else if ($request->envio_status === 0 AND Auth::user()->nivel !== 'Super-Admin') {
                return back()->withErrors('O usuário não pode devolver formulários.');

            } else {
                $resposta->status = $request->envio_status;
                $resposta->data_envio = Carbon::now('America/Sao_Paulo')->format('Y-m-d H:i:s');
                $resposta->update();
            }
        }

        DB::commit();
        return redirect()->route('resposta.index', ['unidade_id' => $request->unidade_id])->with('sucesso', 'Operação concluída com sucesso.');
    }

    public function export (Request $request, $id)
    {
        $data = $request->query('data');
        $user_id = $request->query('user_id');
        $unidade = Unidade::find($id);
        
        $topicos = Topico::with(['respostas' => function($query) use ($id, $data, $user_id) {
            $query->whereHas('unidade', function($q) use($id) {
                $q->where('id', $id);
            })->where([['data', $data], ['user_id', $user_id]]);
        }, 'respostas.pergunta', 'respostas.label_valors', 'respostas.marcador'])->get();

        foreach ($topicos as $topico) {
            $topico->respostas = $topico->respostas->sortBy('pergunta.created_at')->sortByDesc('pergunta.index')->values();
        }

        $marcadores = Marcador::where([['setor_id', $unidade->setor_id], ['is_enabled', 1]])->get();
        $resposta = Resposta::with('marcador')->where([['data', $data], ['user_id', $user_id], ['unidade_id', $id]])->first();
        $marcador_atual_id;
        $criador = $resposta->criador;
        
        if(isset($resposta->marcador)) {
            $marcador_atual_id = $resposta->marcador->id;
        } else {
            $marcador_atual_id = "";
        }

        return view('resposta.export', compact('data', 'unidade', 'topicos', 'marcadores', 'criador', 'marcador_atual_id'));
    }

    public function GerarPDF (Request $request)
    {
        try {
            $inicio = $request->data_ini;
            $fim = $request->data_fim;
            $id = $request->unidade_id;
            $user_id = $request->user_id;
            $perguntas_ids = $request->perguntas_ids;
            $unidade = Unidade::find($id);
    
            $topicos = Topico::with(['respostas' => function($query) use ($id, $inicio, $fim, $user_id, $perguntas_ids) {
                $query->whereHas('unidade', function($q) use($id) {
                    $q->where('id', $id);
                })->whereIn('pergunta_id', $perguntas_ids)->whereBetween('data', [$inicio, $fim])->orderBy('data', 'ASC');
            }, 'respostas.pergunta', 'respostas.label_valors', 'respostas.marcador'])->get();
    
            foreach ($topicos as $topico) {
                $topico->respostas = $topico->respostas->sortBy('data')->values();
            }
    
            $relatores = Resposta::with('marcador')->where('unidade_id', $id)->whereIn('pergunta_id', $perguntas_ids)->whereBetween('data', [$inicio, $fim])->groupBy('user_id')->get();
            $usuario = Auth::user();

            $fileName;
            if (substr($inicio, 0, 10)===substr($fim, 0, 10)) {
                $fileName = $unidade->setor->nome.' - '.$unidade->nome.' - '.date('d-m-Y', strtotime($inicio)).'.pdf';
            } else {
                $fileName = $unidade->setor->nome.' - '.$unidade->nome.' - '.date('d-m-Y', strtotime($inicio)).'--'.date('d-m-Y', strtotime($fim)).'.pdf';
            }

            $total = Resposta::where('unidade_id', $id)
            ->whereBetween('data', [$inicio, $fim])
            ->groupBy('data', 'user_id')
            ->get();

            $totalDeRelatorios = count($total);

            $pdf = PDF::loadView('resposta.pdf', compact('totalDeRelatorios', 'topicos', 'inicio', 'fim', 'unidade', 'relatores', 'usuario'));
            return $pdf->stream($fileName);

        } catch (Throwable $th) {
            return back()->withErrors('Ocorreu um erro ao tentar exportar o relatório.');
        }
    }
}