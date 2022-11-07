@extends('gentelella.layouts.app')

@section('content')

   <div class="x_panel modal-content">
      <div class="x_title">
         <h2>Novo Marcador</h2>
      <div class="clearfix"></div>
   </div>
   <div class="x_panel">
      <div class="x_content">
         <form id="formulario_sala" class="form-horizontal form-label-left" method="post" action="{{ route('marcador.store') }}">
            {{ csrf_field()}}
            <div  class="form-group row">
              <div class=" form-group col-md-4 col-sm-4 col-xs-12">
                <label class="control-label" >Nome do Marcador</label>
                <input type="text" id="nome_marcador" class="form-control " name="nome" minlength="4" maxlength="100" required>	
              </div>
              <div class=" form-group col-md-4 col-sm-4 col-xs-12">
                <label class="control-label" >Cor do Marcador</label>
                <input type="color" id="color" class="form-control" name="color" required>	
              </div>
              <div class="form-group col-md-4 col-sm-4 col-xs-12">
                <label class="control-label">Setor</label>
                <select id="setor" class="form-control" name="setor_id" required>
                  <option value="">Selecione...</option>
                  @if(Auth::user()->nivel === "Admin")
                    @foreach (Auth::user()->setores as $setor)
                      <option value="{{$setor->id}}">{{$setor->nome}}</option>
                    @endforeach
                  @elseif(Auth::user()->nivel === "Super-Admin")
                    @foreach ($setores as $setor)
                      <option value="{{$setor->id}}">{{$setor->nome}}</option>
                    @endforeach
                  @endif
                </select>
              </div>
            </div>
        {{-- BOTÕES --}}
				<div class="clearfix"></div>
				<div class="ln_solid"></div>
				<div class="footer text-right">
          <input type="submit" hidden>
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
 </div>
@endsection
@push('scripts')
	<script type="text/javascript">
    $(document).ready(function(){
      $("#btn_voltar").click(function(){
        event.preventDefault();
        window.location.href = "{{ URL::route('unidade.index') }}"; 
      });
    });
	</script>
@endpush