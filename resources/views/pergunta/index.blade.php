@extends('gentelella.layouts.app')

@section('content')

<div class="x_panel modal-content">
    <div class="x_title">
         <h2>Perguntas</h2>
      <ul class="nav navbar-right panel_toolbox">
         <a href="{{route('pergunta.create')}}" class="btn-circulo btn  btn-success btn-md  pull-right " data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Novo Pergunta"> Nova Pergunta </a>
      </ul>
      <div class="clearfix"></div>
    </div>
    <div class="x_panel">
       <div class="x_content">
          <table id="tb_perguntas" class="table table-hover table-striped compact">
            <thead>
               <tr>
                  <th>Título da Pergunta</th>
                  <th>Tópico</th>
                  <th>Criado por</th>
                  <th>Ações</th>
               </tr>
            </thead>
            <tbody>
               @foreach ($perguntas as $pergunta)
                  @if (in_array($pergunta->topico->setor->id, $setores_usuario_logado))
                     <tr>
                        <td>{{$pergunta->nome}}</td>
                        <td>{{$pergunta->topico->nome}}</td>
                        <td>{{$pergunta->criador->name}}</td>
                        <td></td>
                     </tr>
                  @endif
               @endforeach
            </tbody>
          </table>
       </div>
    </div>
 </div>
@endsection
@push('scripts')
  <script>
    $(document).ready(function(){
        var tb_user = $("#tb_perguntas").DataTable({
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