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
          <table id="tb_topicos" class="table table-hover table-striped compact">
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
                     <tr>
                        <td>{{$topico->nome}}</td>
                        <td>{{$topico->setor->nome}}</td>
                        <td>{{$topico->criador->name}}</td>
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
        var tb_user = $("#tb_topicos").DataTable({
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