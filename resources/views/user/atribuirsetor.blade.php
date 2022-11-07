@extends('gentelella.layouts.app')

@section('content')

<div class="x_panel modal-content ">
	<div class="x_title">
	   <h2>Atribuir Setor</h2>
	   <div class="clearfix"></div>
	</div>
	<div class="x_panel ">
	  <div class="x_content">
	   	<form id="formulario_user" class="form-horizontal form-label-left" method="post" action="{{ route('user.atribuirsetor', $usuario->id) }}">
				{{ csrf_field()}}
				<div id="desabilita">
					<div class="form-group row">
						<div class=" form-group col-md-6 col-sm-6 col-xs-12">
							<label class="control-label" >Nome</label>
							<input type="text" id="name" class="form-control" minlength="4" maxlength="100" disabled value="{{$usuario->name}}" >	
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
				<div class="clearfix"></div>
				<div class="ln_solid"> </div>
				<div class="form-group row">
					<h2>Setor do Usuário</h2>
					{{-- @if (Auth::user()->nivel === "Super-Admin") --}}
						@foreach ($setores as $setor)
							<div class="form-check col-md-6 col-sm-6 col-xs-12" style="display: flex; align-items: center;">
								<input name="atribuicoes[{{$setor->id}}]" type="hidden" value="0">
								<input
									name="atribuicoes[{{$setor->id}}]"
									class="form-check-input"
									type="checkbox"
									@foreach ($relacoes as $relacao)
										@if ($setor->id === $relacao->setor_id)
											checked
										@endif
									@endforeach
									value="{{$setor->id}}">
								<label class="form-check-label" style="margin: 0;">{{ $setor->nome }}</label>
							</div>
						@endforeach
					{{-- @else
						@foreach ($setores_usuario_logado as $setor)
							<div class="form-check col-md-6 col-sm-6 col-xs-12" style="display: flex; align-items: center;">
								<input name="atribuicoes[{{$setor->id}}]" type="hidden" value="0" >
								<input 
									name="atribuicoes[{{$setor->id}}]"
									class="form-check-input"
									type="checkbox"
									@foreach ($relacoes as $relacao)
										@if ($setor->nome_setor === $relacao->nome_setor)
											checked
										@endif
									@endforeach
									value="{{$setor->id}}">
								<label class="form-check-label" style="margin: 0;">{{ $setor->nome_setor }}</label>
							</div>
						@endforeach
					@endif --}}
				</div>
				{{-- <div class="clearfix"></div>
				<div class="ln_solid"> </div>
					<div class="form-group row">
						<label class="control-label">Setores</label>
						<input id="setor-select" name="setores">
					</div> --}}
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
{{-- <script>
	const setores = @json($setores);
	const atuais = @json($atuais);
	const todos = [...setores, ...atuais];

	const options = todos.filter((item, pos) => {
		return todos.indexOf(item) == pos;
	});

	const selecionados = atuais.map(item => item.value);

	let settings = {
		plugins: ['remove_button'],
		options: options,
		items: selecionados,
		sortField: {
			field: 'text',
			direction: 'asc'
		}
	}
	const setorSelect = new TomSelect('#setor-select', settings);
</script> --}}
<script type="text/javascript">
	$(document).ready(function(){
		$("#btn_voltar").click(function(){
			event.preventDefault();
			window.location.href = "{{ URL::route('user.index') }}"; 
		});
	});
</script>
@endpush