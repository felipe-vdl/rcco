<?php

Route::get ('/', 					"AuthController@login");
Route::get ("/login", 		"AuthController@login")->name('login');
Route::post('/login', 		"AuthController@entrar");
Route::get ('/logout', 		'AuthController@logout')->name('logout');

Route::group(['middleware' => ['auth']], function () {

	Route::get ('/alterasenha',							'UserController@AlteraSenha');
	Route::post('/salvasenha',   						'UserController@SalvarSenha');
	Route::post('/enviarsenhausuario',			'UserController@EnviarSenhaUsuario');
	Route::post('/resetarsenha', 						'UserController@ResetarSenha')->name('user.resetarsenha');
	
	Route::get('/user/{id}/setor', 			'UserController@AtribuirSetorForm');
	Route::post('/user/{id}/setor', 			'UserController@AtribuirSetor')->name('user.atribuirsetor');
	
	Route::get('/user/{id}/unidade', 			'UserController@AtribuirUnidadeForm');
	Route::post('/user/{id}/unidade', 			'UserController@AtribuirUnidade')->name('user.atribuirunidade');
	
	Route::get('/home', 'HomeController@index')->name('home');
	Route::get('/embreve', 'HomeController@embreve')->name('embreve');
	
	Route::get('/api/unidades',			'APIController@unidades');
	Route::get('/api/topicos',			'APIController@topicos');
	Route::get('/api/tabela',			  'APIController@gerarTabela');
	Route::get('/api/formulario',   'APIController@formulario');
	
	Route::post('pergunta/is_enabled',		'PerguntaController@is_enabled')->name('pergunta.is_enabled');
	Route::post('/pergunta/{id}/set_index', 			'PerguntaController@set_index')->name('pergunta.set_index');

	Route::post('/resposta/enviar', 'RespostaController@enviar')->name('resposta.enviar');
	Route::post('/resposta/pdf', 'RespostaController@GerarPDF')->name('resposta.pdf');
	
	Route::resource('setor', 'SetorController');
	Route::resource('unidade', 'UnidadeController');
	Route::resource('topico', 'TopicoController');
	Route::resource('pergunta', 'PerguntaController');
	Route::resource('resposta', 'RespostaController');
	Route::resource('user', 'UserController');
});