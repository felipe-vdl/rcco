<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use PDF;
use App\Models\Setor;
use App\Models\Unidade;
use App\Models\Pergunta;
use App\Models\Resposta;
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
        // 1. foreach topico { foreach (resposta) {new Resposta} };
        // 2. data, unidade_id, pergunta_id, topico_id, user_id
        // 3.1 if(formato === checkbox) { save(), foreach(checkboxvalues) {create LabelValor} }
        // 3.2 else if(text/textarea/radio/dropdown) { valor, save() }
        // dd($request->all());
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
                            $resposta->valor = $radio["valor"];
                            $resposta->status = 0;
                            $resposta->pergunta_id = $radio["pergunta_id"];
                            $resposta->topico_id = $radio["topico_id"];
                            $resposta->user_id = Auth::user()->id;
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
            return redirect()->route('resposta.index')->with('sucesso', 'Formulário enviado com sucesso.');

        } catch (Throwable $th) {
            DB::rollback();
            dd($th);
            return back()->withErrors('Ocorreu um erro ao tentar enviar o formulário.');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function enviar(Request $request)
    {
        //
    }

    public function GerarPDF (Request $request)
    {
        //
    }
}
