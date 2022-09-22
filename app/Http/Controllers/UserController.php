<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;



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
			$user->senha_padrao = 'pmm123456';
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
}