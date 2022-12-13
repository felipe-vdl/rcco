@extends('gentelella.layouts.app')
@section('content')
<div class="x_panel modal-content">
	<div class="x_title">
		 <h2>Exportar Relatório</h2>
		<div class="clearfix"></div>
	</div>
	<form action="{{route('resposta.pdf')}}" method="post">
	  {{ csrf_field() }}
    <input type="hidden" name="user_id" value="{{$criador->id}}">
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
            @if (count($marcadores) > 0)
              <div class="form-group col-md-6 col-sm-6 col-xs-12" id="marcador-div">
                <label class="control-label">Marcador</label>
                <select id="marcador-select" name="marcador_id" class="form-control" minlength="2" disabled>
                  <option value="">Selecione o marcador</option>
                  @foreach ($marcadores as $marcador)
                    <option @if($marcador->id == $marcador_atual_id) style="color:{{$marcador->color}};" selected @endif value="{{$marcador->id}}">{{$marcador->nome}}</option>
                  @endforeach
                </select>
              </div>
            @endif
					</div>
			</div>
		</div>
		<div id="topicos">
      @foreach($topicos as $topico)
        @if($topico->respostas->count() > 0)
          <div class="x_panel">
            <div class="x_content">
              <div class="container">
                <h1 class="text-center">{{$topico->nome}}</h1>
                @foreach($topico->respostas as $resposta)
                  <div class="row" style="margin: 2rem 0;">
                    @if($resposta->pergunta->formato === "text")
                  <input type="checkbox" value="{{$resposta->pergunta->id}}" name="perguntas_ids[]">
                      <div style="padding: 0;" class="col-md-6 col-xs-12">
                        <h4 style="margin: 0;">{{$resposta->pergunta->nome}}</h4>
                        <p style="margin: 0; padding-left: 1rem;">@if($resposta->valor){{$resposta->valor}}@else <span style="color:red;">Não respondido</span> @endif</p>
                      </div>
                    @endif
                    @if($resposta->pergunta->formato === "textarea")
                    <input type="checkbox" value="{{$resposta->pergunta->id}}" name="perguntas_ids[]">
                      <div style="padding: 0;" class="col-12">
                        <h4 style="margin: 0;">{{$resposta->pergunta->nome}}</h4>
                        <p style="margin: 0; padding-left: 1rem;">@if($resposta->valor){{$resposta->valor}}@else <span style="color:red;">Não respondido</span> @endif</p>
                      </div>
                    @endif
                    @if($resposta->pergunta->formato === "checkbox")
                    <input type="checkbox" value="{{$resposta->pergunta->id}}" name="perguntas_ids[]">
                      <h4 style="margin: 0; margin-bottom: 0.5rem;" class="col-12">{{$resposta->pergunta->nome}}</h4>
                      @foreach($resposta->label_valors as $label_valor)
                        <div style="padding: 0; padding-left: 1rem;" class="col-12 col-md-6 col-sm-6 col-xs-12">
                          <p style="margin: 0; margin-bottom: 0.5rem; font-weight: bold;">
                            {{$label_valor->label_option->nome}}:
                            <span style="font-weight: normal;">{{$label_valor->valor ? 'Sim' : 'Não'}}</span>
                          </p>
                        </div>
                      @endforeach
                    @endif
                    @if($resposta->pergunta->formato === "radio")
                      <input type="checkbox" value="{{$resposta->pergunta->id}}" name="perguntas_ids[]">
                      <div style="padding: 0;" class="col-12">
                        <h4 style="margin: 0;">{{$resposta->pergunta->nome}}</h4>
                        <p style="margin: 0; padding-left: 1rem;">@if($resposta->valor){{$resposta->valor}}@else <span style="color:red;">Não respondido</span> @endif</p>
                      </div>
                    @endif
                    @if($resposta->pergunta->formato === "dropdown")
                    <input type="checkbox" value="{{$resposta->pergunta->id}}" name="perguntas_ids[]">
                      <div style="padding: 0;" class="col-12">
                        <h4 style="margin: 0;">{{$resposta->pergunta->nome}}</h4>
                        <p style="margin: 0; padding-left: 1rem;">@if($resposta->valor){{$resposta->valor}}@else <span style="color:red;">Não respondido</span> @endif</p>
                      </div>
                    @endif
                  </div>
                @endforeach
              </div>
            </div>
          </div>
      @endif
      @endforeach
		</div>
		<div class="footer text-right">
      <input type="submit" hidden>
			<button id="btn_cancelar" class="botoes-acao btn btn-round btn-primary" >
				<span class="icone-botoes-acao mdi mdi-backburger"></span>   
				<span class="texto-botoes-acao"> CANCELAR </span>
				<div class="ripple-container"></div>
			</button>
			<button type="submit" id="btn_salvar" class="botoes-acao btn btn-round btn-success ">
				<span class="icone-botoes-acao mdi mdi-send"></span>
				<span class="texto-botoes-acao"> EXPORTAR </span>
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