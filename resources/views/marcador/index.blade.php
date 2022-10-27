@extends('gentelella.layouts.app')

@section('content')

<div class="x_panel modal-content">
    <div class="x_title">
         <h2>Marcadores</h2>
      <ul class="nav navbar-right panel_toolbox">
         <a href="{{route('marcador.create')}}" class="btn-circulo btn  btn-success btn-md  pull-right " data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Novo marcador"> Novo Marcador </a>
      </ul>
      <div class="clearfix"></div>
    </div>
    <div class="x_panel">
       <div class="x_content">
          <table id="tb_marcadors" class="table table-hover table-striped compact">
            <thead>
               <tr>
                  <th>Nome do Marcador</th>
                  <th>Setor</th>
                  <th>Criado por</th>
                  <th>Ações</th>
               </tr>
            </thead>
            <tbody>
               @foreach ($marcadores as $marcador)
                  @if (in_array($marcador->setor->id, $setores_usuario_logado, false))
                     @if (($marcador->is_enabled AND Auth::user()->nivel === "Admin") OR Auth::user()->nivel === "Super-Admin")
                        <tr>
                           <td>{{$marcador->nome}}</td>
                           <td>{{$marcador->setor->nome}}</td>
                           <td>{{$marcador->criador->name}}</td>
                           <td>
                              @if ($marcador->is_enabled === 1)
                                 <form style="display: inline-block;" class="desabilitar" method="POST" class="excluir" action="{{route("marcador.is_enabled")}}">
                                    @csrf
                                    <input type="hidden" value="{{$marcador->id}}" name="marcador_id">
                                    <input type="hidden" value="0" name="is_enabled">
                                    <button
                                       title="Desabilitar tópico."
                                       class="btn btn-danger btn-xs action botao_acao btn_excluir"
                                    >
                                       <i class="glyphicon glyphicon-remove"></i>
                                    </button>
                                 </form>
                              @else
                                 @if (Auth::user()->nivel === "Super-Admin")
                                    <form class="habilitar" method="POST" class="excluir" action="{{route("marcador.is_enabled")}}">
                                       @csrf
                                       <input type="hidden" value="{{$marcador->id}}" name="marcador_id">
                                       <input type="hidden" value="1" name="is_enabled">
                                       <button
                                          title="Habilitar tópico."
                                          class="btn btn-success btn-xs action botao_acao btn_excluir"
                                       >
                                          <i class="glyphicon glyphicon-ok"></i>
                                       </button>
                                    </form>
                                 @endif
                              @endif
                           </td>
                        </tr>
                     @endif
                  @endif
               @endforeach
            </tbody>
          </table>
       </div>
    </div>
 </div>
@endsection
@push('scripts')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script defer>
   const desabilitarForms = document.querySelectorAll('.desabilitar');

   if (desabilitarForms.length > 0) {
      for(let form of desabilitarForms) {
         form.addEventListener('submit', evt => {
            evt.preventDefault();

            swal({
               title: "Atenção!",
               text: "Você está prestes a desabilitar um marcador",
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
                     value: 'desabilitar',
                     visible: true,
                     closeModal: true,
                  }
               }
            }).then(function(resultado) {
               if (resultado === 'desabilitar') {
                  form.submit();
               }
            });
         });
      }
   }

   const habilitarForms = document.querySelectorAll('.habilitar');
   if (habilitarForms.length > 0) {
      for(let form of habilitarForms) {
         form.addEventListener('submit', evt => {
            evt.preventDefault();

            swal({
               title: "Atenção!",
               text: "Você está prestes a habilitar um marcador",
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
                     value: 'habilitar',
                     visible: true,
                     closeModal: true,
                  }
               }
            }).then(function(resultado) {
               if (resultado === 'habilitar') {
                  form.submit();
               }
            });
         });
      }
   }
</script>
  <script>
    $(document).ready(function(){
        var tb_user = $("#tb_marcadors").DataTable({
          language: {
                'url' : '{{ asset('js/portugues.json') }}',
          "decimal": ",",
          "thousands": "."
          },
          "order": [[1, "asc"], [0, "asc"]],
          stateSave: true,
          stateDuration: -1,
          responsive: true,
        })
    });
  </script>
  @if (session()->has('success'))
  <script defer>
    funcoes.notificationRight("top", "right", "primary", "{{ session()->get("success") }}");
  </script>
  @endif
@endpush