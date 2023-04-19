@extends('gentelella.layouts.app')
@section('content')
<div class="x_panel modal-content">
	<div class="x_title">
		 <h2>Comentar Relatório</h2>
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
  <div class="x_panel" style="padding: 0;">
    <div class="x_content">
      <form style="display: flex; flex-direction: column; gap: 6px; align-items: flex-start;" method="POST" action="{{ route('comentario.store') }}">
        @csrf
        <input type="hidden" name="data" value="{{ $data }}">
        <input type="hidden" name="unidade_id" value="{{ $unidade->id }}">
        <input type="hidden" name="relator_id" value="{{ $criador->id }}">
        <textarea required rows="3" style="padding: 6px; outline: none; resize: vertical; width: 100%;" name="content" placeholder="Adicione um comentário..."></textarea>
        <button title="Enviar comentário" style="margin: 0;" class="btn btn-success">Comentar</button>
      </form>
    </div>
  </div>
  @if($comentarios->count() > 0)
    <div class="x_panel">
      <div class="x_content">
        <h1 class="text-center">COMENTÁRIOS</h1>
        <div style="display: flex; flex-direction: column; gap: 16px; align-items: flex-start;">
          @foreach ($comentarios as $comentario)
            <div style="border: 1px solid rgb(220, 220, 220); padding: 0.5rem 1rem; width: 100%; display: flex; justify-content: space-between; align-items:center;">
              <div style="flex: 1; display: flex; flex-direction: column;">
                <span>
                  <b>{{ $comentario->criador->name }}</b> — {{ date('d/m/Y à\s H:i', strtotime($comentario->created_at)) }}
                </span>
                <p style="padding: 0; margin: 0; padding-left: 6px;">
                  {{ $comentario->content }}
                </p>
              </div>
              @if (Auth::user()->id === $comentario->user_id)
                <form class="deletar_comentario" action="{{ route('comentario.delete', $comentario->id) }}" method="post">
                  @csrf
                  <button title="Remover comentário." class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i></button>
                </form>
              @endif
            </div>
          @endforeach
        </div>
      </div>
    </div>
  @endif
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
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script defer>
    const deleteForms = document.querySelectorAll('.deletar_comentario');

    for (let deleteForm of deleteForms) {
      deleteForm.addEventListener('submit', evt => {
        evt.preventDefault();
        swal({
					title: "Atenção!",
					text: "Deseja excluir o comentário?",
					icon: "warning",
					buttons: {
							cancel: {
								text: "Cancelar",
								value: "cancelar",
								visible: true,
								closeModal: true,
							},
							ok: {
								text: "Sim, Confirmar!",
								value: 'excluir',
								visible: true,
								closeModal: true,
							}
					}
				}).then(function(resultado) {
					if (resultado === 'excluir') {
            deleteForm.submit();
					}
				});
      });
    }
  </script>
@endpush