@extends('gentelella.layouts.app')

@section('content')
   <div class="x_panel modal-content">
      <div class="x_title">
         <h2>Editar Pergunta</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_panel">
         <div class="x_content">
            <form action="{{route('pergunta.update', $pergunta->id)}}" method="post">
              {{ csrf_field()}}
              @method('PATCH')
              <div class="form-group row">
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label">Setor</label>
                  <select id="setor_id" class="form-control" disabled>
                    <option disabled selected>{{$pergunta->topico->setor->nome}}</option>
                  </select>
                </div>
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label">Tópico</label>
                  <select id="topico_id" class="form-control" disabled>
                    <option value="" disabled selected>{{$pergunta->topico->nome}}</option>
                  </select>
                </div>
                <div id="unidade_div" class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label">Unidades</label>
                  <input required id="unidades_id" name="unidades_id">
                </div>
              </div>
              <div class="form-group row">
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label">Formato da Pergunta</label>
                  <select id="formato" name="formato" class="form-control" disabled>
                    <option disabled selected>
                      @switch($pergunta->formato)
                        @case('text')
                          Texto Simples
                        @break
                        @case('textarea')
                          Texto Grande
                          @break
                        @case('checkbox')
                          Checkbox
                          @break
                        @case('radio')
                          Radio
                          @break
                        @case('dropdown')
                          Dropdown
                          @break
                        @default
                      @endswitch
                    </option>
                  </select>
						    </div>
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label">Tipo de Dado</label>
                  <select id="tipo" name="tipo" class="form-control" disabled>
                    <option>
                      @switch($pergunta->tipo)
                          @case('string')
                              Texto
                              @break
                          @case('number')
                              Número
                              @break
                          @default
                      @endswitch
                    </option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label">Título da Pergunta</label>
							    <input disabled value="{{$pergunta->nome}}" type="text" id="nome" class="form-control">	
                </div>
              </div>
              <div class="form-group row">
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label">Campo obrigatório?</label>
                  <select id="is_required" name="is_required" class="form-control" minlength="2" required>
                    <option @if($pergunta->is_required === 0) selected @endif value="0">Não</option>
                    <option @if($pergunta->is_required === 1) selected @endif value="1">Sim</option>
							    </select>
                </div>
                {{-- Opções --}}
                @if($pergunta->formato === 'checkbox' OR $pergunta->formato === 'radio' OR $pergunta->formato === 'dropdown')
                  <div class="form-group col-md-6 col-sm-6 col-xs-12 newlabel">
                    <div id="checkbox" class="checuboxu">
                      <label class="control-label">Nome da Opção CheckBox</label>
                      <button type="button" class="btn btn-primary clonador">+</button>
                      <div class="input-group checksboxs" style="display:none;">
                        <input type="text" id="checkboxvalue" class="form-control checkboxvalue">
                        <input type="button" class="btn btn-primary  btn_remove" value="Remover" style="margin: 1.375rem 0 0 1.5625rem;"/>
                      </div>
                      @foreach($pergunta->label_options as $option)
                        <div class="input-group checksboxs">
                          <input type="hidden" value="{{$option->id}}" name="checkboxids[]">
                          <input type="text" disabled value="{{$option->nome}}" class="form-control checkboxvalue">
                          <input type="button" class="btn btn-primary  btn_remove" value="Remover" style="margin: 1.375rem 0 0 1.5625rem;"/>
                        </div>
                      @endforeach
                      <div class="input-group novadiv">
                      </div>
                    </div>
                  </div>
                  @push('scripts')
                    <script>
                      $('.clonador').click(function(e){
                          e.preventDefault();
                          $('.checksboxs:first').clone().appendTo($('.novadiv'));
                          $('.checksboxs').last().find('input[type="text"]').val('');
                          $('.checksboxs').last().find('input[type="text"]').attr('required', 'required');
                          $('.checksboxs').last().find('input[type="text"]').attr('name', 'checkboxvalue[]');
                          $('.checksboxs').last().show();
                      });

                      $('form').on('click', '.btn_remove', function(e){
                          e.preventDefault();
                          if ($('.checksboxs').length > 1) {
                            $(this).parents('.checksboxs').remove();
                          }
                      });
                    </script>
                  @endpush
                @endif
              </div>
              {{-- Actions --}}
              <div class="clearfix"></div>
				      <div class="ln_solid"> </div>
              <div class="footer text-right">
                <input type="submit" hidden>
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
      </div>
   </div>
@endsection

@push('scripts')
   <script src="{{ asset('js/tom-select.complete.min.js') }}"></script>
   <script>
      let phpSetorUnidades = @json($pergunta->topico->setor->unidades);
      let phpUnidadesAtuais = @json($pergunta->unidades);
      
      let options = phpSetorUnidades.map(unidade => ({value: unidade.id, text: unidade.nome}));
      let items = phpUnidadesAtuais.map(unidade => unidade.id);

      let settings = {
         plugins: ['remove_button'],
         options: options,
         items: items,
         sortField: {
            field: 'text',
            direction: 'asc'
         }
      };
      const unidadeSelect = new TomSelect('#unidades_id', settings);
   </script>
   <script defer>
      $(function(){
        $('body').submit(function(event){
          if ($(this).hasClass('btn_salvar')) {
            event.preventDefault();
          }
          else {
            $(this).find(':submit').html('<i class="fa fa-spinner fa-spin"></i>');
            $(this).addClass('btn_salvar');
          }
        });
        $("#btn_cancelar").click(function(){
          event.preventDefault();
          window.location.href="{{ URL::route('pergunta.index') }}";
        });
      });
   </script>
@endpush