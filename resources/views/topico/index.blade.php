@extends('gentelella.layouts.app')

@section('content')

<div class="x_panel modal-content">
    <div class="x_title">
         <h2>Tópicos</h2>
      <ul class="nav navbar-right panel_toolbox">
         <a href="{{route('topico.create')}}" class="btn-circulo btn  btn-success btn-md  pull-right " data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Novo topico"> Novo Tópico </a>
      </ul>
      <div class="clearfix"></div>
    </div>
    <div class="x_panel">
       <div class="x_content">
          <table id="tb_topicos" class="table table-hover table-striped compact" style="width:100%">
            <thead>
               <tr>
                  <th>Nome do Tópico</th>
                  <th>Setor</th>
                  <th>Criado por</th>
                  <th>Ações</th>
               </tr>
            </thead>
            <tbody>
               @foreach ($topicos as $topico)
                  @if (in_array($topico->setor->id, $setores_usuario_logado, false))
                     @if (($topico->is_enabled AND Auth::user()->nivel === "Admin") OR Auth::user()->nivel === "Super-Admin")
                        <tr>
                           <td>{{$topico->nome}}</td>
                           <td>{{$topico->setor->nome}}</td>
                           <td>{{$topico->criador->name}}</td>
                           <td>
                              <div style="display: flex;">
                                 @if ($topico->is_enabled === 1)
                                    <form style="display: inline-block;" class="desabilitar" method="POST" class="excluir" action="{{route("topico.is_enabled")}}">
                                       @csrf
                                       <input type="hidden" value="{{$topico->id}}" name="topico_id">
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
                                       <form class="habilitar" method="POST" class="excluir" action="{{route("topico.is_enabled")}}">
                                          @csrf
                                          <input type="hidden" value="{{$topico->id}}" name="topico_id">
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
                              </div>
                           </td>
                        </tr>
                     @endif
                  @endif
               @endforeach
            </tbody>
            <tfoot>
               <tr>
                   <th><input class="filter-input" data-column="0" type="text" placeholder="Filtro por Tópico"></th>
                   <th><input class="filter-input" data-column="1" type="text" placeholder="Filtro por Setor"></th>
                   <th><input class="filter-input" data-column="2" type="text" placeholder="Filtro por Criador"></th>
                   <th>{{-- <input class="filter-input" data-column="3" type="text" placeholder="Filtro por Ações"> --}}</th>
               </tr>
           </tfoot>
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
               text: "Você está prestes a desabilitar um tópico",
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
               text: "Você está prestes a habilitar um tópico",
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
        var tb_topicos = $("#tb_topicos").DataTable({
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
        $('.filter-input').keyup(function() {
            tb_topicos.column( $(this).data('column') )
            .search( $(this).val() )
            .draw();
         });
    });
  </script>
  @if (session()->has('success'))
  <script defer>
    funcoes.notificationRight("top", "right", "primary", "{{ session()->get("success") }}");
  </script>
  @endif
@endpush