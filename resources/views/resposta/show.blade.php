@extends('gentelella.layouts.app')
@section('content')
<div class="x_panel modal-content">
	<div class="x_title">
		 <h2>Relatório</h2>
		<div class="clearfix"></div>
	</div>
  <div class="x_panel">
    <div class="x_content">
        <div class="row">
          <div class="col-md-6 col-sm-6 col-xs-12">
            <h4>Setor</h4>
            <p>{{$unidade->setor->nome}}</p>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <h4>Unidade</h4>
            <p>{{$unidade->nome}}</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 col-sm-6 col-xs-12">
            <h4>Data Correspondente</h4>
            <p>{{date('d/m/Y', strtotime($respostaSample->data))}}</p>
          </div>
          @if($marcador)
          <div class="col-md-6 col-sm-6 col-xs-12">
            <h4>Marcador</h4>
            <p><span style="color:{{$marcador->color}};">{{$marcador->nome}}</span></p>
          </div>
          @endif
          <div class="col-md-6 col-sm-6 col-xs-12">
            <h4>Preenchido por</h4>
            <p>{{$criador->name}}</p>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-6 col-sm-6 col-xs-12">
            <h4>Data de Criação</h4>
            <p>{{date('d/m/Y H:i:s', strtotime($respostaSample->created_at))}}</p>
          </div>
          @if ($respostaSample->modified_at)
          <div class="col-md-6 col-sm-6 col-xs-12">
            <h4>Última Modificação:</h4>
            <p>{{date('d/m/Y H:i:s', strtotime($respostaSample->modified_at))}}</p>
          </div>
          @endif
          @if ($respostaSample->data_envio)
          <div class="col-md-6 col-sm-6 col-xs-12">
            <h4>Data de Envio</h4>
            <p>{{date('d/m/Y H:i:s', strtotime($respostaSample->data_envio))}}</p>
          </div>
          @endif
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
                  <div style="padding: 0;" class="col-md-6 col-xs-12">
                    <h4 style="margin: 0;">{{$resposta->pergunta->nome}}</h4>
                    <p style="margin: 0; padding-left: 1rem;">@if($resposta->valor){{$resposta->valor}}@else <span style="color:red;">Não respondido</span> @endif</p>
                  </div>
                @endif
                @if($resposta->pergunta->formato === "textarea")
                  <div style="padding: 0;" class="col-12">
                    <h4 style="margin: 0;">{{$resposta->pergunta->nome}}</h4>
                    <p style="margin: 0; padding-left: 1rem;">@if($resposta->valor){{$resposta->valor}}@else <span style="color:red;">Não respondido</span> @endif</p>
                  </div>
                @endif
                @if($resposta->pergunta->formato === "checkbox")
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
                  <div style="padding: 0;" class="col-12">
                    <h4 style="margin: 0;">{{$resposta->pergunta->nome}}</h4>
                    <p style="margin: 0; padding-left: 1rem;">@if($resposta->valor){{$resposta->valor}}@else <span style="color:red;">Não respondido</span> @endif</p>
                  </div>
                @endif
                @if($resposta->pergunta->formato === "dropdown")
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
@endsection
@push('scripts')

@endpush