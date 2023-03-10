<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Models\Setor;
use App\Models\RelatorioExterno;
use App\Models\Unidade;

class RelatorioExternoController extends Controller
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

        return view('relatorio_externo.index', compact('setores_usuario_logado', 'setores'));
    }

    public function create()
    {
        if (Auth::user()->nivel === "User" OR Auth::user()->nivel === "Read-Only")
        {
            return redirect()->back()->withErrors("O usuário não tem permissão para acessar este recurso.");
        }

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

        return view('relatorio_externo.create', compact('setores_usuario_logado'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $arquivosEnviados = [];
            foreach($request->arquivo as $key => $arquivo) {
                $filename = $arquivo->store('public/relatorio_externo');
                array_push($arquivosEnviados, substr($filename, 25));
                RelatorioExterno::create([
                    'user_id' => Auth::user()->id,
                    'unidade_id' => $request->unidade_id,
                    'nome' => $request->nome.' ('.$key.')',
                    'data' => $request->data,
                    'filename' => substr($filename, 25),
                    'extensao' => $arquivo->extension(),
                ]);
            }

            DB::commit();
            return redirect()->route('relatorio_externo.index', ['unidade_id' => $request->unidade_id])->with('sucesso', 'Relatório enviado com sucesso.');
        } catch (Throwable $th) {
            DB::rollback();
            // Deletar arquivos.
            foreach($arquivosEnviados as $arquivo) {
                unlink(storage_path('app/public/relatorio_externo/'.$arquivo));
            }
            return redirect()->back()->withErrors("Ocorreu um erro ao enviar o relatório.");
        }
    }

    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();
            $arquivo = RelatorioExterno::find($request->relatorio_id);
            unlink(storage_path('app/public/relatorio_externo/'.$arquivo->filename));
            $arquivo->delete();
            
            DB::commit();
            return redirect()->route('relatorio_externo.index', ['unidade_id' => $request->unidade_id])->with('sucesso', 'Arquivo removido com sucesso.');
        } catch (Throwable $th) {
            DB::rollback();
            return redirect()->back()->withErrors("Ocorreu um erro ao enviar o relatório.");
        }

    }
}