<?php

Route::get ('/', 					"AuthController@login")->name('login');
Route::get ("/login", 		"AuthController@login")->name('login');
Route::post('/login', 		"AuthController@entrar");
Route::get ('/logout', 		'AuthController@logout')->name('logout');

Route::group(['middleware' => ['auth']], function () {

	Route::get ('/alterasenha',							'UserController@AlteraSenha');
	Route::post('/salvasenha',   						'UserController@SalvarSenha');
	Route::post('/enviarsenhausuario',			'UserController@EnviarSenhaUsuario');
	Route::post('/resetarsenha', 'UserController@ResetarSenha')->name('user.resetarsenha');
	Route::get('/home', 'HomeController@index')->name('home');
	
	Route::resource('setor', 'SetorController');
	Route::resource('unidade', 'UnidadeController');
	Route::resource('user', 'UserController');
});