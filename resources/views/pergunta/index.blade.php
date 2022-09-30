@extends('gentelella.layouts.app')

@section('content')

<div class="x_panel modal-content">
    <div class="x_title">
         <h2>Perguntas</h2>
      <ul class="nav navbar-right panel_toolbox">
         <a href="{{route('pergunta.create')}}" class="btn-circulo btn  btn-success btn-md  pull-right " data-toggle="tooltip" data-placement="bottom" title="Nova Pergunta" data-original-title="Nova Pergunta"> Nova Pergunta </a>
      </ul>
      <div class="clearfix"></div>
    </div>
    <div class="x_panel">
       <div class="x_content">
          <table id="tb_perguntas" class="table table-hover table-striped compact">
            <thead>
               <tr>
                  <th class="text-center">Index</th>
                  <th class="text-center">Título da Pergunta</th>
                  <th class="text-center">Formato</th>
                  <th class="text-center">Tópico</th>
                  <th class="text-center">Unidades</th>
                  <th class="text-center">Criado por</th>
                  <th class="text-center">Ações</th>
               </tr>
            </thead>
            <tbody>
               @foreach ($perguntas as $pergunta)
                  @if (in_array($pergunta->topico->setor->id, $setores_usuario_logado))
                     @if (($pergunta->is_enabled AND Auth::user()->nivel === "Admin") OR Auth::user()->nivel === "Super-Admin")
                        <tr>
                           <td>{{$pergunta->index}}</td>
                           <td>{{$pergunta->nome}}</td>
                           <td>
                              @switch($pergunta->formato)
                                 @case('text')
                                    Texto Simples
                                    @break
                                 @case('textarea')
                                    Texto Grande
                                    @break
                                 @case('checkbox')
                                    Checkbox
                                    @break
                                 @case('radio')
                                    Radio
                                    @break
                                 @case('dropdown')
                                    Dropdown
                                    @break
                              @endswitch
                           </td>
                           <td>{{$pergunta->topico->nome}}</td>
                           @if ($pergunta->unidades->count() === 0)
                              <td></td>
                           @else
                              <td title="@foreach($pergunta->unidades as $unidade) @if($loop->last){{$unidade->nome}}@else {{$unidade->nome}} / @endif @endforeach">{{$pergunta->unidades->count()}}</td>
                           @endif
                           <td>{{$pergunta->criador->name}}</td>
                           <td>
                              <form style="display: inline-block;" class="set_index" method="POST" action="{{route("pergunta.set_index", $pergunta->id)}}">
                                 @csrf
                                 <input type="hidden" name="pergunta_id" value="{{$pergunta->id}}">
                                 <input type="hidden" name="index_atual" value="{{$pergunta->index}}">
                                 <input type="hidden" name="index" value="">
                                 <button
                                       title="Atribuir index."
                                       class="btn btn-primary btn-xs action botao_acao btn_excluir"
                                    >
                                    <i class="glyphicon glyphicon-edit"></i>
                                 </button>
                              </form>
                              @if ($pergunta->is_enabled === 1)
                                 <form style="display: inline-block;" class="desabilitar" method="POST" class="excluir" action="{{route("pergunta.is_enabled")}}">
                                    @csrf
                                    <input type="hidden" value="{{$pergunta->id}}" name="pergunta_id">
                                    <input type="hidden" value="0" name="is_enabled">
                                    <button
                                       title="Desabilitar pergunta."
                                       class="btn btn-danger btn-xs action botao_acao btn_excluir"
                                    >
                                       <i class="glyphicon glyphicon-remove"></i>
                                    </button>
                                 </form>
                              @else
                                 @if (Auth::user()->nivel === "Super-Admin")
                                    <form class="habilitar" method="POST" class="excluir" action="{{route("pergunta.is_enabled")}}">
                                       @csrf
                                       <input type="hidden" value="{{$pergunta->id}}" name="pergunta_id">
                                       <input type="hidden" value="1" name="is_enabled">
                                       <button
                                          title="Habilitar pergunta."
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
            <tfoot>
               <tr>
                   <th></th>
                   <th></th>
                   <th></th>
                   <th></th>
                   <th></th>
                   <th></th>
                   <th></th>
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
                  text: "Você está prestes a desabilitar uma pergunta",
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
                  text: "Você está prestes a habilitar uma pergunta",
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

      const setIndexForms = document.querySelectorAll('.set_index');
      // if (habilitarForms.length > 0) {
      //    for(let form of setIndexForms) {
      //       form.addEventListener('submit', evt => {
      //          swal({
      //          text: 'Número do Index',
      //          content: "input",
      //          button: {
      //             text: "Enviar",
      //             closeModal: false,
      //          },
      //          });
      //    }
      // }
      if(setIndexForms.length > 0) {
         for(let form of setIndexForms) {
            form.addEventListener('submit', evt => {
               evt.preventDefault();
               swal({
                  text: "Número do Index",
                  content: {
                     element: "input",
                     attributes: {
                        placeholder: "0",
                        value: form.elements["index_atual"].value,
                        type: "number",
                        min: 0
                     }
                  },
                  button: {
                     text: 'Confirmar',
                     closeModal: true
                  }
               }).then((result) => {
                  if(result) {
                     form.elements["index"].value = result;
                     form.submit();
                  }
               });
            });
         }
      }
  </script>
  <script>
    $(document).ready(function(){
        var myTable = $("#tb_perguntas").DataTable({
          language: {
            'url' : '{{ asset('js/portugues.json') }}',
            "decimal": ",",
            "thousands": "."
          },
          "order": [[3, "asc"], [0, "desc"]],
          stateSave: true,
          stateDuration: -1,
          responsive: true,
          initComplete: function () {
            this.api()
                .columns()
                .every(function () {
                  var column = this;
                  var select = $('<select><option value=""></option></select>')
                     .appendTo($(column.header()))
                     .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                     });
                  $( select ).click( function(e) {
                        e.stopPropagation();
                  });
 
                  column
                     .data()
                     .unique()
                     .sort()
                     .each(function (d) {
                        if(column.search() === '^'+d+'$'){
                           select.append( '<option value="'+d+'" selected="selected">'+d+'</option>' )
                        } else {
                           select.append( '<option value="'+d+'">'+d+'</option>' )
                        }
                     });
                });
         }
        });
        $('.filter-input').keyup(function() {
         myTable.column( $(this).data('column') )
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