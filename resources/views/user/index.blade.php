@extends('gentelella.layouts.app')

@section('content')

<div class="x_panel modal-content">
    <div class="x_title">
       <h2>Usuários</h2>
       <ul class="nav navbar-right panel_toolbox">
          <a href="{{url('user/create')}}" class="btn-circulo btn  btn-success btn-md  pull-right " data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Novo Funcionario"> Novo Funcionario </a>
      </ul>
      <div class="clearfix"></div>
    </div>
    <div class="x_panel">
       <div class="x_content">
         <table id="tb_user" class="table table-hover table-striped compact" style="width: 100%;">
            <thead>
               <tr>
                  <th>Nome do Usuário</th>
                  <th>E-mail</th>
                  <th>Permissão</th>
                  <th>Setores</th>
                  <th>Unidades</th>
                  <th>Ações</th>
               </tr>
            </thead>   
            <tbody>
               @foreach ($usuarios as $usuario)
                  <tr>
                     <td>{{$usuario->name}}</td>
                     <td>{{$usuario->email}}</td>
                     <td
                        data-order=
                           @if ($usuario->nivel === "Super-Admin")
                              1
                           @elseif ($usuario->nivel === "Admin")
                              2
                           @else
                              3
                           @endif
                     >{{$usuario->nivel}}</td>
                     {{-- <td>{{$usuario->pivot}}</td> --}}
                     <td>
                        @foreach ($usuario->setores as $setor)
                           {{$setor->nome}}<br>
                        @endforeach
                     </td>
                     @if ($usuario->unidades->count() === 0)
                        <td></td>
                     @else
                        <td title="@foreach($usuario->unidades as $unidade) @if($loop->last){{$unidade->nome}}@else {{$unidade->nome}} / @endif @endforeach">{{$usuario->unidades->count()}}</td>
                     @endif
                     <td class="actions text-right">
                        @if($usuario->nivel != 'Super-Admin')
                        <a
                           id="btn_atribui_setor"
                           class="btn btn-success btn-xs action botao_acao " 
                           data-toggle="tooltip" 
                           data-valor=
                              @if(Auth::user()->nivel === "Super-Admin" OR Auth::user()->nivel  === "Admin")
                                 ativo
                              @else
                                 desabilitado
                              @endif
                           data-placement="bottom" 
                           data-info="{{$usuario->id}}" 
                           title="Atribuir Setor">  
                           <i class="glyphicon glyphicon-list-alt"></i>
                        </a>
                        @endif
                        @if($usuario->nivel === 'User')
                        <a
                           id="btn_atribui_unidade"
                           class="btn btn-info btn-xs action botao_acao " 
                           data-toggle="tooltip"
                           data-valor=
                              @if(Auth::user()->nivel === "Super-Admin" OR Auth::user()->nivel  === "Admin")
                                 ativo
                              @else
                                 desabilitado
                              @endif
                           data-placement="bottom" 
                           data-info="{{$usuario->id}}" 
                           title="Atribuir Unidade">
                           <i class="glyphicon glyphicon-list-alt"></i>
                        </a>
                        @endif
                        <a
                           href="#"
                           id="btn_edita_usuario"
                           class="btn btn-warning btn-xs action botao_acao btn_editar" 
                           data-valor = 
                              @if(Auth::user()->nivel  == 'Admin') 
                                 @foreach ($usuario->setores as $setor) 
                                    @if(in_array($setor->nome, $setores_usuario_logado))
                                       ativo
                                    @else
                                       desabilitado
                                    @endif 
                                 @endforeach 
                              @endif
                              @if (Auth::user()->nivel  == 'Admin')
                                 desabilitado
                              @elseif(Auth::user()->nivel  == 'Super-Admin')
                                 ativo
                              @endif
                           data-toggle="tooltip" 
                           data-placement="bottom" 
                           data-info="{{$usuario->id}}" 
                           title="Editar Funcionario">  
                           <i class="glyphicon glyphicon-pencil "></i>
                        </a>
                        <a
                           id="btn_resta_usuario"
                           class="btn btn-primary btn-xs action botao_acao btn_email_senha"
                           data-valor = 
                              @if(Auth::user()->nivel  == 'Admin') 
                                 @foreach ($usuario->setores as $setor) 
                                    @if(in_array($setor->nome, $setores_usuario_logado)) 
                                       ativo
                                    @else
                                       desabilitado
                                    @endif 
                                 @endforeach 
                              @endif
                              @if (Auth::user()->nivel  == 'Admin')
                                 desabilitado
                              @elseif(Auth::user()->nivel  == 'Super-Admin')
                                 ativo
                              @endif
                           data-info="{{$usuario->id}}"
                           data-toggle="tooltip" 
                           data-placement="bottom"
                           title="Resetar Senha">  
                           <i class='glyphicon glyphicon-envelope '></i>
                        </a>
                        <a
                           id="btn_exclui_funcionario"
                           class="btn btn-danger btn-xs action botao_acao btn_excluir"
                           data-valor = 
                              @if(Auth::user()->nivel  == 'Admin') 
                                 @foreach ($usuario->setores as $setor) 
                                    @if(in_array($setor->nome, $setores_usuario_logado)) 
                                       ativo
                                    @else
                                       desabilitado
                                    @endif 
                                 @endforeach 
                              @endif
                              @if (Auth::user()->nivel  == 'Admin')
                                 desabilitado
                              @elseif(Auth::user()->nivel  == 'Super-Admin')
                                 ativo
                              @endif
                           data-toggle="tooltip" 
                           data-funcionario = {{$usuario->id}}
                           data-placement="bottom" 
                           title="Excluir Funcionario"> 
                           <i class="glyphicon glyphicon-remove "></i>
                        </a>
                  </td>
                  </tr>
               @endforeach
            </tbody>
          </table>
       </div>
    </div>
 </div>

@endsection

@push('scripts')

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

   <script>
      $(document).ready(function(){
         var tb_user = $("#tb_user").DataTable({
            language: {
                  'url' : '{{ asset('js/portugues.json') }}',
            "decimal": ",",
            "thousands": "."
            },
            stateSave: true,
            stateDuration: -1,
            responsive: true,
            order: [[2, "asc"]],
         })
      });

      // $("table#tb_user").on("click", ".btn_excluir", function() {
				
		// 	});


         $("table#tb_user").on("click", "#btn_atribui_setor",function(){
			
				let valor = $(this).data('valor');
            let id = $(this).data('info');
				let btn = $(this);
				
				if( valor == 'desabilitado' )
				{ 
					event.preventDefault();
					funcoes.notificationRight("top", "right", "danger", "Este usuário não tem permissão para executar esta ação!");
					return
				} else {
               $(location).attr('href', "{{ url('/user') }}/" + id + "/setor");
            }
			});

         $("table#tb_user").on("click", "#btn_atribui_unidade",function(){
			
				let valor = $(this).data('valor');
            let id = $(this).data('info');
				let btn = $(this);
				
				if( valor == 'desabilitado' )
				{
					event.preventDefault();
					funcoes.notificationRight("top", "right", "danger", "Este usuário não tem permissão para executar esta ação!");
					return
				} else {
               $(location).attr('href', "{{ url('/user') }}/" + id + "/unidade");
            }
			});

         $("table#tb_user").on("click", "#btn_edita_usuario",function(){
			
         let valor = $(this).data('valor');
         let id = $(this).data('info');
         let btn = $(this);
         
         if( valor == 'desabilitado' )
         { 
            event.preventDefault();
            funcoes.notificationRight("top", "right", "danger", "Este usuário não tem permissão para executar esta ação!");
            return
         } else {
            // $.get("{{ url('/user') }}/" + id + "/edit");
            $(location).attr('href',"{{ url('/user') }}/" + id + "/edit");
         }
      });

      $("table#tb_user").on("click", "#btn_resta_usuario",function(){
			
         let valor = $(this).data('valor');
         let btn = $(this);
         
         if( valor == 'desabilitado' )
         { 
            event.preventDefault();
            funcoes.notificationRight("top", "right", "danger", "Este usuário não tem permissão para executar esta ação!");
            return
         } else {
            let id = $(this).data('info');
            let btn = $(this);
            
            swal({
               title: "Atenção!",
               text: "Você está prestes a resetar a senha de um usuário",
               icon: "warning",
               buttons: {
                  cancel: {
                     text: "Cancelar",
                     value: "cancelar",
                     visible: true,
                     closeModal: true,
                  },
                  ok: {
                     text: "Confirmar",
                     value: 'resetar',
                     visible: true,
                     closeModal: true,
                  }
					}
            }).then(function(resultado) {
					if (resultado === 'resetar') {
                  $.post("{{ route('user.resetarsenha') }}", {
                     _token: "{{ csrf_token() }}",
                     _method: "POST",
                     id: id,
                  }).done(function() {
                     // location.reload();
                     funcoes.notificationRight("top", "right", "success", "Senha resetada com sucesso.")
                  }).fail(function(err) {
                     console.log(err);
                  });
					}
				});
         }
      });

      $("table#tb_user").on("click", "#btn_exclui_funcionario",function(){
			
         let valor = $(this).data('valor');
         let btn = $(this);
         
         if( valor == 'desabilitado' )
         { 
            event.preventDefault();
            funcoes.notificationRight("top", "right", "danger", "Este usuário não tem permissão para executar esta ação!");
            return
         }else{
            let id = $(this).data('funcionario');
				let btn = $(this);
				swal({
					title: "Atenção!",
					text: "Excluir permanentemente um usuário",
					icon: "warning",
					buttons: {
							cancel: {
								text: "Cancelar",
								value: "cancelar",
								visible: true,
								closeModal: true,
							},
							ok: {
								text: "Sim, Confirmar!",
								value: 'excluir',
								visible: true,
								closeModal: true,
							}
					}
				}).then(function(resultado) {
					if (resultado === 'excluir') {
							$.post("{{ url('/user/') }}/" + id, {
								id: id,
								_method: "DELETE",
								_token: "{{ csrf_token() }}",
							}).done(function() {
								location.reload();
							}).fail(function(err) {
                        console.log(err);
                     });
					}
				});
         }
      });

   </script>

   @if (session()->has('success'))
      <script defer>
         funcoes.notificationRight("top", "right", "success", "{{ session()->get("success") }}");
      </script>
   @endif


@endpush