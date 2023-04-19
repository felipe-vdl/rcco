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
              <label class="control-label col-12">Data do Relatório (Início / Fim)</label>
            <div class="row">
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="hidden" id="datecontainer_ini" class="form-control" value="{{substr($data, 0, 10)}} 00:00:01" name="data_ini" required autocomplete="off">
                <input type="text" value="{{date('d/m/Y', strtotime($data))}}" id="data_ini" class="form-control" required placeholder="dd/mm/aaaa" minlength="10" maxlength="10" required autocomplete="off">
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="hidden" id="datecontainer_fim" class="form-control" value="{{substr($data, 0, 10)}} 23:59:59" name="data_fim" required autocomplete="off">
                <input type="text" value="{{date('d/m/Y', strtotime($data))}}" id="data_fim" class="form-control" required placeholder="dd/mm/aaaa" minlength="10" maxlength="10" required autocomplete="off">
              </div>
            </div>
            </div>
					</div>
			</div>
		</div>
		<div id="topicos">
      <div class="x_panel">
        <div class="x_content">
          <div class="container">
            <h1 class="text-center">COMENTÁRIOS</h1>
            <div class="row">
              <div style="padding: 0.5rem; padding-left: 1rem; border: 1px solid grey; gap: 1rem; display:flex; align-items: center;" class="col-12">
                <input type="hidden" value="0" name="incluir_comentarios">
                <input type="checkbox" value="1" name="incluir_comentarios" checked>
                <h4 style="display:inline-block; margin: 0;">Incluir comentários</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
      @foreach($topicos as $topico)
        @if($topico->respostas->count() > 0)
          <div class="x_panel">
            <div class="x_content">
              <div class="container">
                <h1 class="text-center">{{$topico->nome}}</h1>
                @foreach($topico->respostas as $resposta)
                  <div class="row" style="margin: 2rem 0;">
                    @if($resposta->pergunta->formato === "text")
                      <div style="padding: 0.5rem; padding-left: 1rem; border: 1px solid grey; gap: 1rem; display:flex; align-items: center;" class="col-12">
                        <input type="checkbox" checked value="{{$resposta->pergunta->id}}" name="perguntas_ids[]">
                        <h4 style="display:inline-block; margin: 0;">{{$resposta->pergunta->nome}}</h4>
                      </div>
                    @endif
                    @if($resposta->pergunta->formato === "textarea")
                      <div style="padding: 0.5rem; padding-left: 1rem; border: 1px solid grey; gap: 1rem; display:flex; align-items: center;" class="col-12">
                        <input type="checkbox" checked value="{{$resposta->pergunta->id}}" name="perguntas_ids[]">
                        <h4 style="display:inline-block; margin: 0;">{{$resposta->pergunta->nome}}</h4>
                      </div>
                    @endif
                    @if($resposta->pergunta->formato === "checkbox")
                      <div style="padding: 0.5rem; padding-left: 1rem; border: 1px solid grey; gap: 1rem; display:flex; align-items: center;" class="col-12">
                        <input type="checkbox" checked value="{{$resposta->pergunta->id}}" name="perguntas_ids[]">
                        <h4 style="display:inline-block; margin: 0;" class="col-12">{{$resposta->pergunta->nome}}</h4>
                      </div>
                    @endif
                    @if($resposta->pergunta->formato === "radio")
                      <div style="padding: 0.5rem; padding-left: 1rem; border: 1px solid grey; gap: 1rem; display:flex; align-items: center;" class="col-12">
                        <input type="checkbox" checked value="{{$resposta->pergunta->id}}" name="perguntas_ids[]">
                        <div style="padding: 0;" class="col-12">
                          <h4 style="display:inline-block; margin: 0;">{{$resposta->pergunta->nome}}</h4>
                        </div>
                      </div>
                    @endif
                    @if($resposta->pergunta->formato === "dropdown")
                      <div style="padding: 0.5rem; padding-left: 1rem; border: 1px solid grey; gap: 1rem; display:flex; align-items: center;" class="col-12">
                        <input type="checkbox" checked value="{{$resposta->pergunta->id}}" name="perguntas_ids[]">
                        <div style="padding: 0;" class="col-12">
                          <h4 style="display:inline-block; margin: 0;">{{$resposta->pergunta->nome}}</h4>
                        </div>
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
  <script>
    $(function() {
      $("#data_ini").datepicker({
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        altFormat: 'yy-mm-dd 00:00:01',
        altField: '#datecontainer_ini',
      });
    });
    $(function() {
      $("#data_fim").datepicker({
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        altFormat: 'yy-mm-dd 23:59:59',
        altField: '#datecontainer_fim',
      });
    });
  </script>
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