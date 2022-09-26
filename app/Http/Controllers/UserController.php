<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Setor;
use App\Models\SetorUser;
use App\Models\Unidade;
use App\Models\UnidadeUser;



class UserController extends Controller

{
	
	public function index()
	{
		$usuarios = User::with('setores')->get();

		$setores_usuario_logado = [];

		$checa_setor = Auth::user()->setores;

		foreach($checa_setor as $setor) {
			$nome = $setor->nome;
			array_push($setores_usuario_logado, $nome);
		}
		
		return view('user.index', compact('usuarios', 'setores_usuario_logado'));
	}

	public function create()
	{
		return view('user.create');
	}

	public function store(Request $request)
	{
		DB::beginTransaction();
		try {
			$user = new User;
			$user->name = $request->name;
			$user->email = $request->email;
			$user->nivel = $request->nivel;
			$senha_padrao = 'pmm123456';
			$user->password = bcrypt($senha_padrao);
			$user->save();
	
			DB::commit();
			return redirect()->route('user.index')->with('sucesso', 'Usuário criado com sucesso.');

		} catch (Throwable $th) {
			DB::rollback();
			dd($th);
			return redirect()->route('user.index')->with('erro', 'Houve um erro ao tentar criar um usuário.');
		}
	}

	public function edit($id)
	{
		$usuario = User::find($id);

		return view('user.edit', compact('usuario'));
	}

	public function update(Request $request, $id)
	{
		DB::beginTransaction();
		try {
			$usuario = User::find($id);
	
			$usuario->name = $request->name;
			$usuario->email = $request->email;
			$usuario->nivel = $request->nivel;
	
			$usuario->update();
			DB::commit();
			return redirect()->route('user.index')->with('sucesso', 'Usuário editado com sucesso.');

		} catch (Throwable $th) {
			DB::rollback();
			dd($th);
			return back()->with('erro', 'Houve um erro ao tentar editar o usuário.');
		}
	}

	public function destroy($id)
	{
		DB::beginTransaction();
		try {
			$usuario = User::find($id);
			
			$usuario->delete();

			DB::commit();
		} catch (Throwable $th) {
			DB::rollback();
			dd($th);
			return back()->with('erro', 'Houve um erro ao tentar excluir o usuário.');
		}
	}
	
	public function AlteraSenha()
	{
		$usuario = Auth::user();
	
		return view('auth.altera_senha',compact('usuario'));    
	}

	public function SalvarSenha(Request $request)
	{
		//não deixa usar o cpf como senha
		if ( retiraMascaraCPF(Auth()->user()->cpf)  == $request->password)
		{
			return back()->withErrors('Essa senha não pode ser utilizada. Tente outra!');
		}


		// Validar
		$this->validate($request, [
			'password_atual'        => 'required',
			'password'              => 'required|min:6|confirmed',
			'password_confirmation' => 'required|min:6'
		]);

		// Obter o usuário
		$usuario = User::find(Auth::user()->id);

		if (Hash::check($request->password_atual, $usuario->password))
		{

			$usuario->update(['password' => bcrypt($request->password)]);            

			return redirect('/home')->with('sucesso','Senha alterada com sucesso.');
		}else{

			return back()->withErrors('Senha atual não confere');
		}

	}

	public function ResetarSenha(Request $request)
	{
		DB::beginTransaction();
		try {
			$usuario = User::find($request->id);
			
			$senha_padrao = 'pmm123456';
			$usuario->password = bcrypt($senha_padrao);
			$usuario->update();

			DB::commit();

		} catch (Throwable $th) {
			DB::rollback();
			dd($th);
			return back()->with('erro', 'Houve um erro ao tentar resetar a senha.');
		}
	}

	public function AtribuirSetorForm($id)
	{
		$usuario = User::find($id);
		$relacoes = SetorUser::where('user_id', $id)->get();
		
		if(Auth::user()->nivel === "Admin") {
			$checa_setor = Auth::user()->setores;
		} else if (Auth::user()->nivel === "Super-Admin") {
			$checa_setor = Setor::all();
		}
		
		$setores = [];
		foreach($checa_setor as $setor) {
			$str = $setor;
			array_push($setores, $str);
		}

		return view('user.atribuirsetor', compact('usuario', 'setores', 'relacoes'));
	}

	public function AtribuirSetor(Request $request, $id)
	{
		try {
			DB::beginTransaction();
			$usuario = User::find($id);
			$setores = Setor::all();
			
			foreach($setores as $setor) {
				if(isset($request->atribuicoes[$setor->id])) {
					if ($request->atribuicoes[$setor->id] == $setor->id) {
						// Se tem o valor do id, criar/manter relação.
						if(SetorUser::where('setor_id', $setor->id)->where('user_id', $id)->exists()) {
							// Se a relação já existe, não fazer nada
							continue;
						} else {
							// Se não existe, criar.
							$relacao = new SetorUser;
							$relacao->user_id = $id;
							$relacao->setor_id = $setor->id;
							$relacao->save();
						}

					} else if ($request->atribuicoes[$setor->id] == "0") {
						// Se o valor for 0, remover relação.
						if(SetorUser::where('setor_id', $setor->id)->where('user_id', $id)->exists()) {
							// Se existe, deletar relação.
							SetorUser::where('setor_id', $setor->id)->where('user_id', $id)->delete();
						} else {
							// Se não existe, não fazer nada.
							continue;
						};
					}
				}
			}

			DB::commit();

			return redirect()->route('user.index')->with('sucesso', 'Setores atribuídos com sucesso.');

		} catch (Throwable $th) {
			DB::rollback();
			dd($th);
			return back()->with('erro', 'Houve um erro ao tentar atribuir setores.');
		}
	}

	public function AtribuirUnidadeForm($id) {
		$usuario = User::find($id);
		$setores = $usuario->setores;
		$relacoes = UnidadeUser::where('user_id', $id)->get();

		if(Auth::user()->nivel === "Admin") {
			$checa_setor = Auth::user()->setores;
		} else if (Auth::user()->nivel === "Super-Admin") {
			$checa_setor = Setor::all();
		}
		
		$setores_admin = [];
		foreach($checa_setor as $setor) {
			$str = $setor->id;
			array_push($setores_admin, $str);
		}

		return view('user.atribuirunidade', compact('usuario', 'setores', 'setores_admin', 'relacoes'));
	}

	public function AtribuirUnidade(Request $request, $id) {
		// Função semelhante à AtribuirSetor: comparar setor_id com index da array request->unidades (checar sua existência com isset, e criar/excluir as relações baseado nos IDs das unidades selecionadas pelo admin no formulário, que estarão contidas no respectivo index do request).
		// Objetivo do algoritmo: Garantir que os admins apenas possam remover/criar relações de unidade caso tenham o setor parente dessas unidades atribuído à eles.
		DB::beginTransaction();
		try {
			$setores = Setor::all();
			foreach($setores as $setor) {
				/* if(isset($request->unidades[$setor->id]) OR $request->unidades[$setor->id] === null) { */
				if(array_key_exists($setor->id, $request->unidades)) {
					UnidadeUser::where('user_id', $id)->whereHas('unidade', function ($query) use ($setor) {
						$query->where('setor_id', $setor->id);
					})->delete();
					
					if (isset($request->unidades[$setor->id])) {
						$unidades = explode(',', $request->unidades[$setor->id]);
		
						foreach($unidades as $unidade) {
							$relacao = new UnidadeUser;
							$relacao->user_id = $id;
							$relacao->unidade_id = $unidade;
							$relacao->save();
						}
					}
				}
			}

			DB::commit();
			return redirect()->route('user.index')->with('sucesso', 'Unidades atribuídos com sucesso.');

		} catch (Throwable $th) {
			DB::rollback();
			dd($th);
			return back()->with('erro', 'Houve um erro ao tentar atribuir unidades.');
		}
	}
}