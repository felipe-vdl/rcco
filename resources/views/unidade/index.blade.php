@extends('gentelella.layouts.app')

@section('content')

<div class="x_panel modal-content">
    <div class="x_title">
       <h2>Unidades</h2>
       <ul class="nav navbar-right panel_toolbox">
          <a href="{{route('unidade.create')}}" class="btn-circulo btn  btn-success btn-md  pull-right " data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Novo Unidade"> Nova Unidade </a>
      </ul>
      <div class="clearfix"></div>
    </div>
    <div class="x_panel">
       <div class="x_content">
          <table id="tb_unidades" class="table table-hover table-striped compact">
            <thead>
               <tr>
                  <th>Nome da Unidade</th>
                  <th>Setor</th>
                  <th>Criado por</th>
                  <th>Ações</th>
               </tr>
            </thead>
            <tbody>
               @foreach ($unidades as $unidade)
                  <tr>
                     <td>{{$unidade->nome}}</td>
                     <td>{{$unidade->setor->nome}}</td>
                     <td>{{$unidade->criador->name}}</td>
                     <td></td>
                  </tr>
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
        var tb_user = $("#tb_unidades").DataTable({
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