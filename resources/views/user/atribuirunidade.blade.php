@extends('gentelella.layouts.app')

@section('content')

<div class="x_panel modal-content ">
	<div class="x_title">
	   <h2>Atribuir Setor</h2>
	   <div class="clearfix"></div>
	</div>
	<div class="x_panel ">
	  <div class="x_content">
	   	<form id="formulario_user" class="form-horizontal form-label-left" method="post" action="{{ route('user.atribuirunidade', $usuario->id) }}">
				{{ csrf_field()}}
        {{-- USER INFO --}}
				<div id="desabilita">
					<div class="form-group row">
						<div class=" form-group col-md-6 col-sm-6 col-xs-12">
							<label class="control-label">Nome</label>
							<input type="text" id="name" class="form-control" minlength="4" maxlength="100" disabled value="{{$usuario->name}}">
						</div>
            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                <label class="control-label">Permissão do Usuário</label>
                <select id="nivel" class="form-control" disabled>
                  <option value="{{$usuario->nivel}}" selected>{{$usuario->nivel}}</option>
                </select>
            </div>
					</div>
					<div class="form-group row">
						<div class="form-group col-md-6 col-sm-6 col-xs-12">
							<label class="control-label" for="email">Email</label>
							<input type="email" id="email" class="form-control" disabled value="{{$usuario->email}}">	
						</div>
					</div>
				</div>
        {{-- UNIDADE SELECT --}}
				<div class="clearfix"></div>
				<div class="ln_solid"> </div>
        @foreach($setores as $setor)
          @if(in_array($setor->id, $setores_admin, false))
            <div class="form-group row">
              <label class="control-label">Unidades {{$setor->nome}}</label>
              <input id="{{"unidade-select-".$setor->id}}" name="{{"unidades[".$setor->id."]"}}">
            </div>
          @endif
        @endforeach
				{{-- BOTÕES --}}
				<div class="clearfix"></div>
				<div class="ln_solid"> </div>
					<div class="footer text-right">
						<input type="submit" hidden>
						<button id="btn_voltar" class="botoes-acao btn btn-round btn-primary" >
							<span class="icone-botoes-acao mdi mdi-backburger"></span>   
							<span class="texto-botoes-acao"> CANCELAR </span>
							<div class="ripple-container"></div>
						</button>
						<button type="submit" id="btn_salvar" class="botoes-acao btn btn-round btn-success ">
							<span class="icone-botoes-acao mdi mdi-send"></span>
							<span class="texto-botoes-acao"> SALVAR </span>
							<div class="ripple-container"></div>
						</button>
					</div>
			</form>
	  </div>
  </div>
@endsection
@push('scripts')
<script>
@foreach($setores as $setor)
  @if(in_array($setor->id, $setores_admin, false))
      new TomSelect("{{'#unidade-select-'.$setor->id}}", {
        plugins: ['remove_button'],
        options: [
          @foreach($setor->unidades as $unidade)
            {value: "{{$unidade->id}}", text: "{{$unidade->nome}}"},
          @endforeach
        ],
        items: [
          @foreach($usuario->unidades as $unidade)
            @if($unidade->setor_id == $setor->id)
              "{{$unidade->id}}",
            @endif
          @endforeach
        ],
        sortField: {
          field: 'text',
          direction: 'asc'
        }
      });
    @endif
  @endforeach
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#btn_voltar").click(function(){
			event.preventDefault();
			window.location.href = "{{ URL::route('user.index') }}"; 
		});
	});
</script>
@endpush