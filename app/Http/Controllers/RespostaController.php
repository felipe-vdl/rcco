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
                        if(!Resposta::where('data', $request->data)
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
                        if(!Resposta::where('data', $request->data)
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
                        if(!Resposta::where('data', $request->data)
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
                        if(!Resposta::where('data', $request->data)
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
                        if(!Resposta::where('data', $request->data)
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
            return redirect()->route('resposta.index')->with('sucesso', 'Formulário criado com sucesso.');

        } catch (Throwable $th) {
            DB::rollback();
            dd($th);
            return back()->withErrors('Ocorreu um erro ao tentar enviar o formulário.');
        }
    }

    public function show(Request $request, $id)
    {
        $data = $request->query('data');
        $unidade = Unidade::find($id);

        $topicos = Topico::with(['respostas' => function($query) use ($id, $data) {
            $query->whereHas('unidade', function($q) use($id) {
                $q->where('id', $id);
            })->where('data', $data);
        }, 'respostas.pergunta', 'respostas.label_valors', 'respostas.marcador'])->get();

        foreach ($topicos as $topico) {
            $topico->respostas = $topico->respostas->sortBy('pergunta.created_at')->sortByDesc('pergunta.index')->values();
        }

        $resposta = Resposta::with('marcador')->where('data', $data)->where('unidade_id', $id)->first();
        $marcador;

        if(isset($resposta->marcador)) {
            $marcador = $resposta->marcador;
        } else {
            $marcador = "";
        }

        //dd($id, $data, $topicos);
        return view('resposta.show', compact('data', 'unidade', 'topicos', 'marcador'));
    }

    public function edit(Request $request, $id)
    {
        $data = $request->query('data');
        $unidade = Unidade::find($id);

        $topicos = Topico::with(['respostas' => function($query) use ($id, $data) {
            $query->whereHas('unidade', function($q) use($id) {
                $q->where('id', $id);
            })->where('data', $data);
        }, 'respostas.pergunta', 'respostas.label_valors', 'respostas.marcador'])->get();

        foreach ($topicos as $topico) {
            $topico->respostas = $topico->respostas->sortBy('pergunta.created_at')->sortByDesc('pergunta.index')->values();
        }

        $marcadores = Marcador::where('setor_id', $unidade->setor_id)->get();
        $resposta = Resposta::with('marcador')->where('data', $data)->where('unidade_id', $id)->first();
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
        // dd($request->all());
        DB::beginTransaction();
        try {
            foreach($request->topicos as $chave => $topico) {
                if(isset($topico["textos_simples"])) {
                    foreach($topico["textos_simples"] as $chave => $input) {
                        $resposta = Resposta::find($input["resposta_id"]);
                        $resposta->valor = $input["valor"];
                        if(isset($request->marcador_id)) {
                            $resposta->marcador_id = $request->marcador_id;
                        } else {
                            $resposta->marcador_id = null;
                        }
                        $resposta->save();
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
                        $resposta->save();
                    }
                }
                if(isset($topico["checkboxes"])) {
                    foreach($topico["checkboxes"] as $chave => $checkboxList) {
                        
                        $resposta = Resposta::find($input["resposta_id"]);
                        if(isset($request->marcador_id)) {
                            $resposta->marcador_id = $request->marcador_id;
                        } else {
                            $resposta->marcador_id = null;
                        }
                        $resposta->save();

                        foreach($checkboxList as $chave => $input) {
                            $label_valor = LabelValor::find($input["label_valor_id"]);
                            $label_valor->valor = $input["valor"];
                            $label_valor->save();
                        }
                    }
                }
            }
            DB::commit();
            return redirect()->route('resposta.index')->with('sucesso', 'Formulário editado com sucesso.');

        } catch (Throwable $th) {
            dd($th);
            DB::rollback();
            return back()->withErrors('Ocorreu um erro ao tentar atualizar as respostas.');
        }
    }

    public function enviar(Request $request)
    {
        DB::beginTransaction();
        $respostas = Resposta::where('unidade_id', $request->unidade_id)->where('data', $request->data)->get();
        foreach($respostas as $resposta) {
            $resposta->status = $request->envio_status;
            $resposta->data_envio = Carbon::now('America/Sao_Paulo')->format('Y-m-d 12:00:00');
            $resposta->update();
        }

        DB::commit();
        return redirect()->back()->with('sucesso', 'Operação concluída com sucesso.');
    }

    public function GerarPDF (Request $request)
    {
        $data = $request->data;
        $id = $request->unidade_id;
        $unidade = Unidade::find($id);

        $topicos = Topico::with(['respostas' => function($query) use ($id, $data) {
            $query->whereHas('unidade', function($q) use($id) {
                $q->where('id', $id);
            })->where('data', $data);
        }, 'respostas.pergunta', 'respostas.label_valors', 'respostas.marcador'])->get();

        foreach ($topicos as $topico) {
            $topico->respostas = $topico->respostas->sortBy('pergunta.created_at')->sortByDesc('pergunta.index')->values();
        }

        $resposta = Resposta::with('marcador')->where('data', $data)->where('unidade_id', $id)->first();
        $marcador;

        if(isset($resposta->marcador)) {
            $marcador = $resposta->marcador;
        } else {
            $marcador = "";
        }

        $pdf = PDF::loadView('resposta.pdf', compact('topicos', 'data', 'unidade', 'marcador'));
        return $pdf->stream('Relatório');
    }
}
