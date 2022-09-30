@extends('gentelella.layouts.app')
@section('content')
<div class="x_panel modal-content">
	<div class="x_title">
		 <h2>Novo Relatório</h2>
		<div class="clearfix"></div>
	</div>
	<form action="{{route('resposta.update', $unidade->id)}}" method="post">
    @method("PATCH")
	  {{ csrf_field() }}
		<div class="x_panel">
			<div class="x_content">
					<div class="form-group row">
						<div class="form-group col-md-6 col-sm-6 col-xs-12">
							<label class="control-label">Setor</label>
              <input type="hidden" value="{{$unidade->setor->id}}" name="setor_id">
							<select id="setor_id" class="form-control" disabled>
                <option selected>{{$unidade->setor->nome}}</option>
							</select>
						</div>
						<div class="form-group col-md-6 col-sm-6 col-xs-12">
              <label class="control-label">Unidade</label>
              <input type="hidden" value="{{$unidade->id}}" name="unidade_id">
							<select id="unidade_id" class="form-control" disabled>
								<option selected>{{$unidade->nome}}</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<div class="form-group col-md-6 col-sm-6 col-xs-12">
							<label class="control-label">Data Correspondente</label>
							<input type="hidden" id="datecontainer" class="form-control" value="{{$data}}" name="data" required autocomplete="off">
							<input type="text" value="{{date('d/m/Y', strtotime($data))}}" disabled id="data" class="form-control" required placeholder="dd/mm/aaaa" minlength="10" maxlength="10" required autocomplete="off">
						</div>
					</div>
			</div>
		</div>
		<div id="topicos">
      @foreach($topicos as $topico)
        <div class="x_panel">
          <div class="x_content">
            <div class="container">
              <div class="row">
                <h1 class="text-center">{{$topico->nome}}</h1>
              </div>
              @foreach ($topico->respostas as $resposta)
                <div class="row" style="margin-top: 1rem;">
                  <div class="form-group col-12" style="padding: 0;">
                    <h2 class="col-12">{{$resposta->pergunta->nome}}</h2>
                    @if($resposta->pergunta->formato === "text")
                      <input style="width: 50%;" @if ($resposta->pergunta->is_required) required @endif value="{{$resposta->valor}}" type="{{$resposta->pergunta->tipo === "string" ? "text" : "number"}}" name="{{'topicos['.$loop->parent->index.'][textos_simples]['.$loop->index.']'}}">
                    @elseif($resposta->pergunta->formato === "textarea")
                      <textarea style="width: 100%;" @if ($resposta->pergunta->is_required) required @endif name="{{'topicos['.$loop->parent->index.'][textos_grandes]['.$loop->index.']'}}">{{$resposta->valor}}</textarea>
                    @elseif($resposta->pergunta->formato === "radio")

                    @elseif($resposta->pergunta->formato === "checkbox")
  
                    @elseif($resposta->pergunta->formato === "dropdown")
  
                    @endif
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      @endforeach
		</div>
		<div class="footer text-right">
			<button id="btn_cancelar" class="botoes-acao btn btn-round btn-primary" >
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
@endsection
@push('scripts')
	<script type="text/javascript">
    $(document).ready(function(){
      //botão de voltar
      $("#btn_cancelar").click(function(){
        event.preventDefault();
        window.location.href = "{{ URL::route('resposta.index') }}"; 
      });
    });
	</script>
@endpush