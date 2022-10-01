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
                      <input type="hidden" value="{{$resposta->id}}" name="{{'topicos['.$loop->parent->index.'][textos_simples]['.$loop->index.'][resposta_id]'}}">
                      <input style="width: 50%;" @if ($resposta->pergunta->is_required) required @endif value="{{$resposta->valor}}" type="{{$resposta->pergunta->tipo === "string" ? "text" : "number"}}" name="{{'topicos['.$loop->parent->index.'][textos_simples]['.$loop->index.'][valor]'}}">
                    @elseif($resposta->pergunta->formato === "textarea")
                      <input type="hidden" value="{{$resposta->id}}" name="{{'topicos['.$loop->parent->index.'][textos_grandes]['.$loop->index.'][resposta_id]'}}">
                      <textarea style="width: 100%;" @if ($resposta->pergunta->is_required) required @endif name="{{'topicos['.$loop->parent->index.'][textos_grandes]['.$loop->index.'][valor]'}}">{{$resposta->valor}}</textarea>
                    @elseif($resposta->pergunta->formato === "radio")
                      <input type="hidden" value="{{$resposta->id}}" name="{{'topicos['.$loop->parent->index.'][radios]['.$loop->index.'][resposta_id]'}}" >
                      @foreach($resposta->pergunta->label_options as $label)
                        <div class="col-md-6 col-sm-6 col-xs-12" style="display: flex; align-items: center;">
                          <input @if($label->nome === $resposta->valor) checked @endif type="radio" value="{{$label->nome}}"  @if ($resposta->pergunta->is_required) required @endif name="{{'topicos['.$loop->parent->parent->index.'][radios]['.$loop->parent->index.'][valor]'}}" >
                          <label class="form-check-label" style="margin: 0;">{{$label->nome}}</label>
                        </div>
                      @endforeach
                    @elseif($resposta->pergunta->formato === "checkbox")
                      <input type="hidden" value="{{$resposta->id}}" name="{{'topicos['.$loop->parent->index.'][checkboxes]['.$loop->index.'][resposta_id]'}}" >
                      @foreach($resposta->label_valors as $label)
                        <div class="col-md-6 col-sm-6 col-xs-12" style="display: flex; align-items: center;">
                          <input type="hidden" name="{{'topicos['.$loop->parent->parent->index.'][checkboxes]['.$loop->parent->index.']['.$loop->index.'][label_valor_id]'}}" value="{{$label->id}}">
                          <input type="hidden" value="0" name="{{'topicos['.$loop->parent->parent->index.'][checkboxes]['.$loop->parent->index.']['.$loop->index.'][valor]'}}" >
                          <input @if($label->valor == 1) checked @endif type="checkbox" value="1" name="{{'topicos['.$loop->parent->parent->index.'][checkboxes]['.$loop->parent->index.']['.$loop->index.'][valor]'}}" >
                          <label class="form-check-label" style="margin: 0;">{{$label->label_option->nome}}</label>
                        </div>
                      @endforeach
                    @elseif($resposta->pergunta->formato === "dropdown")
                      <input type="hidden" name="{{'topicos['.$loop->parent->index.'][dropdowns]['.$loop->index.'][resposta_id]'}}" value="{{$resposta->id}}">
                      <input name="{{'topicos['.$loop->parent->index.'][dropdowns]['.$loop->index.'][valor]'}}">
                      @push('scripts')
                        <script>
                          new TomSelect(`input[name="{{'topicos['.$loop->parent->index.'][dropdowns]['.$loop->index.'][valor]'}}"]`, {
                            maxItems: 1,
                            plugins: [],
                            options: [
                              @foreach($resposta->pergunta->label_options as $label) {text: '{{$label->nome}}', value: '{{$label->nome}}'}, @endforeach
                            ],
                            items: [
                              '{{$resposta->valor}}',
                            ],
                            sortField: {
                              field: 'text',
                              direction: 'asc'
                            }
                          });
                        </script>
                      @endpush
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