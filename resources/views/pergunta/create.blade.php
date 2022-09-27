@extends('gentelella.layouts.app')

@section('content')
   <div class="x_panel modal-content">
      <div class="x_title">
         <h2>Nova Pergunta</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_panel">
         <div class="x_content">
            <form action="{{route('pergunta.store')}}" method="post">
               {{ csrf_field()}}
              <div class="form-group row">
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label">Setor</label>
                  <select onchange="getUnidadesAndTopicos()" id="setor_id" name="setor_id" class="form-control" required>
                    <option value="" disabled selected>Selecione o seu setor</option>
                    @foreach ($setores_usuario_logado as $setor)
                      <option value="{{$setor->id}}">{{$setor->nome}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label">Tópico</label>
                  <select id="topico_id" name="topico_id" class="form-control" disabled required>
                    <option value="" disabled selected>Selecione um setor para receber os tópicos</option>
                    {{-- API Tópicos --}}
                  </select>
                </div>
                <div id="unidade_div" style="display: none;" class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label">Unidades</label>
                  <input required id="unidades_id" name="unidades_id">
                </div>
              </div>
              <div class="form-group row">
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label">Formato da Pergunta</label>
                  <select id="formato" name="formato" class="form-control" required>
                    <option value="" disabled selected>Selecione o formato da pergunta</option>
                    <option value="text">Texto Simples</option>
                    <option value="textarea">Texto Grande</option>
                    <option value="checkbox">CheckBox</option>
                    <option value="radio">Radio</option>
                    <option value="dropdown">Dropdown</option>
                  </select>
						    </div>
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label">Tipo de Dado</label>
                  <select id="tipo" name="tipo" class="form-control" required>
                    <option value="" disabled selected>Selecione o tipo de dado</option>
                    <option value="string">Texto</option>
                    <option value="number">Número</option>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label">Título da Pergunta</label>
							    <input type="text" id="nome" class="form-control" name="nome" minlength="4" maxlength="100" required >	
                </div>
              </div>
              <div class="form-group row">
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label">Campo obrigatório?</label>
                  <select id="is_required" name="is_required" class="form-control" minlength="2" required>
                    <option value="0">Não</option>
                    <option value="1">Sim</option>
							    </select>
                </div>
                <div class="form-group col-md-6 col-sm-6 col-xs-12 newlabel"  >
                  <div id="checkbox" class="checuboxu" style="display:none;">
                    <label class="control-label">Nome da opção CheckBox</label>
                    <button type="button" class="btn btn-primary clonador">+</button>
                    <div class="input-group checksboxs">
                        <input type="text" id="checkboxvalue" name="checkboxvalue[]"  class="form-control checkboxvalue">
                        <input type="button" class="btn btn-primary  btn_remove" value="Remover" style="margin: 1.375rem 0 0 1.5625rem; "/>
                    </div>
                    <div class="input-group novadiv">
                    </div>
                  </div>
                  <div id="radio" class="radioboxu" style="display:none;">
                    <label class="control-label">Nome da opção Radio</label>
                    <button type="button" class="btn btn-primary clonador-radio">+</button>
                    <div class="input-group radios">
                        <input type="text" id="radiovalue" name="radiovalue[]"  class="form-control radiovalue">
                        <input type="button" class="btn btn-primary  btn_remove" value="Remover" style="margin: 1.375rem 0 0 1.5625rem; "/>
                    </div>
                    <div class="input-group novadiv-radio">
                    </div>
                  </div>
                  <div id="dropdown" class="dropdownboxu" style="display:none;">
                    <label class="control-label">Nome da opção Dropdown</label>
                    <button type="button" class="btn btn-primary clonador-dropdown">+</button>
                    <div class="input-group dropdowns">
                        <input type="text" id="dropdownvalue" name="dropdownvalue[]"  class="form-control dropdownvalue">
                        <input type="button" class="btn btn-primary  btn_remove" value="Remover" style="margin: 1.375rem 0 0 1.5625rem; "/>
                    </div>
                    <div class="input-group novadiv-dropdown">
                    </div>
                  </div>
                </div>
              </div>
              <div class="clearfix"></div>
				      <div class="ln_solid"> </div>
              <div class="footer text-right"> {{-- col-md-3 col-md-offset-9 --}}
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
      let settings = {
         plugins: ['remove_button'],
         options: [],
         sortField: {
            field: 'text',
            direction: 'asc'
         }
      };
      const unidadeSelect = new TomSelect('#unidades_id', settings);
   </script>
   <script>
      $("#formato").on('change', function(){
         $('.checuboxu').hide();
         $('.radioboxu').hide();
         $('.dropdownboxu').hide();
         $('#' + this.value).show();
         if(this.value == 'checkbox'){
            $('#checkboxvalue').attr('required', 'required');
            $('#radiovalue').removeAttr('required', 'required');
            $('#dropdownvalue').removeAttr('required', 'required');

         }else if (this.value == 'radio'){
            $('#radiovalue').attr('required', 'required');
            $('#checkboxvalue').removeAttr('required', 'required');
            $('#dropdownvalue').removeAttr('required', 'required');

         }else if (this.value == 'dropdown'){
            $('#dropdownvalue').attr('required', 'required');
            $('#checkboxvalue').removeAttr('required', 'required');
            $('#radiovalue').removeAttr('required', 'required');

         } else {
            $('#radiovalue').removeAttr('required', 'required');
            $('#checkboxvalue').removeAttr('required', 'required');
            $('#dropdownvalue').removeAttr('required', 'required');
         }
      });
      $('.clonador').click(function(e){
         e.preventDefault();
         $('.checksboxs:first').clone().appendTo($('.novadiv'));
         $('.checksboxs').last().find('input[type="text"]').val('');
         $('#checkboxvalue').attr('required', 'required');
      });
      
      $('.clonador-radio').click(function(e){
         e.preventDefault();
         $('.radios:first').clone().appendTo($('.novadiv-radio'));
         $('.radios').last().find('input[type="text"]').val('');
         $('#radiovalue').attr('required', 'required');
      });

      $('.clonador-dropdown').click(function(e){
         e.preventDefault();
         $('.dropdowns:first').clone().appendTo($('.novadiv-dropdown'));
         $('.dropdowns').last().find('input[type="text"]').val('');
         $('#dropdownvalue').attr('required', 'required');
      });

      $('form').on('click', '.btn_remove', function(e){
         e.preventDefault();
         if ($('.checksboxs').length > 1) {
            $(this).parents('.checksboxs').remove();

         } else if ($('.radios').length > 1) {
            $(this).parents('.radios').remove();
            
         } else if ($('.dropdowns').length > 1) {
            $(this).parents('.dropdowns').remove();
         }
      });

     
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
   <script defer>
      const setorSelect = document.getElementById('setor_id');
      const topicoSelect = document.getElementById('topico_id');
      const btnSalvar = document.getElementById('btn_salvar');
      const unidadeDiv = document.getElementById('unidade_div');

      const getUnidades = async () => {
         // Desabilitar inputs durante o carregamento do request.
         btnSalvar.setAttribute('disabled', 'disabled');
         setorSelect.setAttribute('disabled', 'disabled');

         try {
            // Envio do request para API.
            const response = await axios.get('/api/unidades', {
               params: {
                  setor_id: setorSelect.value
               }
            });

            const unidades = response.data;

            unidadeSelect.clear();
            unidadeSelect.clearOptions();

            for(let unidade of unidades) {
               unidadeSelect.addOption({
                  value: unidade.id,
                  text: unidade.nome
               });
            };

            // Liberar inputs
            unidadeDiv.style.display = "";
            setorSelect.removeAttribute('disabled');
            btnSalvar.removeAttribute('disabled');

         } catch (error) {
            console.log(error);
            setorSelect.removeAttribute('disabled');
            btnSalvar.removeAttribute('disabled');
         }
      }

      const getTopicos = async () => {
        try {
          btnSalvar.setAttribute('disabled', 'disabled');
          setorSelect.setAttribute('disabled', 'disabled');
          
          const response = await axios.get('/api/topicos', {
            params: {
              setor_id: setorSelect.value
            }
          });

          const topicos = response.data;

          topicoSelect.innerHTML = "";

          const selecione = document.createElement('option');
          selecione.value = "";
          selecione.innerText = "Selecione o tópico";
          selecione.setAttribute('selected', 'selected');
          topicoSelect.append(selecione);

          for(let topico of topicos) {
            const option = document.createElement('option');
            option.value = topico.id;
            option.innerText = topico.nome;
            topicoSelect.append(option);
          }

          setorSelect.removeAttribute('disabled');
          topicoSelect.removeAttribute('disabled');
          btnSalvar.removeAttribute('disabled');
          
        } catch (error) {
          console.log(error);
          setorSelect.removeAttribute('disabled');
          btnSalvar.removeAttribute('disabled');
        }
      }

      const getUnidadesAndTopicos = () => {
        getUnidades();
        getTopicos();
      }
   </script>
@endpush