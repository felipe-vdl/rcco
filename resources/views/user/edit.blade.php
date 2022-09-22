@extends('gentelella.layouts.app')

@section('content')

<div class="x_panel modal-content ">

	<div class="x_title">
	   <h2>Editar Funcionário </h2>
	   <div class="clearfix"></div>
	</div>

	<div class="x_panel ">
	   <div class="x_content">
	   		<form id="formulario_user" class="form-horizontal form-label-left" method="post" action="{{ route('user.update', $usuario->id) }}">
        {!! method_field('PATCH') !!}
				{{ csrf_field()}}
				<div id="desabilita">
					<div class="form-group row">
						<div class=" form-group col-md-6 col-sm-6 col-xs-12">
							<label class="control-label" >Nome</label>
							<input type="text" id="name" class="form-control " name="name" minlength="4" maxlength="100" required value="{{$usuario->name}}" >	
						</div>
            <div class="form-group col-md-6 col-sm-6 col-xs-12">
              <label class="control-label">Permissão do Usuário</label>
              <select id="nivel" class="form-control" name="nivel" required>
                  <option value="">Selecione...</option>                                 
                  @if (Auth::user()->nivel  === "Super-Admin")
                    <option @if($usuario->nivel === "Super-Admin") selected @endif value="Super-Admin">Super Administrador</option>
                  @endif
                  <option @if($usuario->nivel === "Admin") selected @endif value="Admin">Administrador</option>
                  <option @if($usuario->nivel === "User") selected @endif value="User">Usuário</option>
              </select>
            </div>
					</div>
					<div class="form-group row">
						<div class="form-group col-md-6 col-sm-6 col-xs-12 ">
							<label class="control-label" for="email">Email</label>
							<input type="email" id="email" class="form-control" name="email" required value="{{$usuario->email}}">	
						</div>
					</div>
				</div>
				{{-- BOTÕES --}}
				<div class="clearfix"></div>
				<div class="ln_solid"> </div>
					<div class="footer text-right">
            {{-- Voltar --}}
						<button id="btn_voltar" class="botoes-acao btn btn-round btn-primary" >
							<span class="icone-botoes-acao mdi mdi-backburger"></span>   
							<span class="texto-botoes-acao"> CANCELAR </span>
							<div class="ripple-container"></div>
						</button>
            {{-- Salvar --}}
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
	<script type="text/javascript">
	$(document).ready(function(){
    $("#btn_voltar").click(function(){
      event.preventDefault();
      window.location.href = "{{ URL::route('user.index') }}"; 
    });
  });
	</script>
@endpush