@extends('gentelella.layouts.app')
@section('content')
<div class="x_panel modal-content">
	<div class="x_title">
		 <h2>Novo Relatório Externo</h2>
		<div class="clearfix"></div>
	</div>
	<form action="{{route('relatorio_externo.store')}}" method="post" enctype="multipart/form-data">
		{{ csrf_field() }}
		<div class="x_panel">
			<div class="x_content">
					<div class="form-group row">
						<div class="form-group col-md-6 col-sm-6 col-xs-12">
							<label class="control-label">Setor</label>
							<select onchange="getUnidades()" id="setor_id" name="setor_id" class="form-control" minlength="2" required>
									<option value="" selected>Selecione o setor</option>
                  @foreach ($setores_usuario_logado as $setor)
                    <option value="{{$setor->id}}">{{$setor->nome}}</option>
                  @endforeach
							</select>
						</div>
						<div class="form-group col-md-6 col-sm-6 col-xs-12">
							<label class="control-label">Unidade</label>
							<select onchange="" id="unidade_id" name="unidade_id" class="form-control" minlength="2" required disabled>
								<option value="" selected>Selecione o setor para carregar as opções</option>
								{{-- API --}}
							</select>
						</div>
					</div>
					<div class="form-group row">
						<div class="form-group col-md-6 col-sm-6 col-xs-12">
							<label class="control-label">Data Correspondente</label>
							<input type="hidden" id="datecontainer" class="form-control" name="data" required autocomplete="off">
							<input type="text" id="data" class="form-control" name="" required placeholder="dd/mm/aaaa" minlength="10" maxlength="10" required autocomplete="off" >	
						</div>
						<div class="form-group col-md-6 col-sm-6 col-xs-12">
							<label class="control-label">Nome do Relatório</label>
							<input type="text" id="nome" class="form-control" name="nome" required placeholder="Nome para o relatório externo" required autocomplete="off">
						</div>
					</div>
          <div class="form-group col-md-12 col-xs-12 row">
            <div class="col-12 mt-3 border border-secondary pt-2">
              <label class="form-label font-weight-bold">Adicione os arquivos:</label>
              <p class="mb-2">
                <label for="arquivo">
                  <a class="btn btn-primary text-light" type="button" role="button" aria-disabled="false">Adicionar Arquivo</a>
                </label>
                <input id="arquivo" class="form-control" name="arquivo[]" type="file" required multiple="multiple" style="visibility: hidden; position: absolute;" accept=".doc,.docx,.xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.pdf">
              </p>
              <div id="erro-arquivo" class="alert alert-danger mb-2" style="display: none;">
                <p class="m-0">Tipo de arquivo inválido, insira apenas documentos: <span id="arquivo-invalido"></span></p>
              </div>
              <div id="erro-arquivo-grande" class="alert alert-danger mb-2" style="display: none;">
                <p class="m-0">Arquivo ultrapassa o limite de tamanho permitido: <span id="arquivo-grande"></span></p>
              </div>
              <p id="arquivo-vermelho" style="font-size: 13px; color: red; display: none;" class="mb-2">* Atenção: Os arquivos destacados em vermelho não serão enviados.</p>
              <p id="arquivo-area">
                <span id="arquivo-list">
                  <span id="arquivo-names"></span>
                </span>
              </p>
            </div>
          </div>
			</div>
		</div>
		<div class="footer text-right">
			<input type="submit" hidden>
			<button id="btn_cancelar" class="botoes-acao btn btn-round btn-primary" >
				<span class="icone-botoes-acao mdi mdi-backburger"></span>   
				<span class="texto-botoes-acao" > CANCELAR </span>
				<div class="ripple-container"></div>
			</button>
			<button type="submit" id="btn_salvar" class="botoes-acao btn btn-round btn-success ">
				<span class="icone-botoes-acao mdi mdi-send"></span>
				<span class="texto-botoes-acao" > ENVIAR </span>
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
	<script>
		const userID = "{{ Auth::user()->id }}";
    const setorSelect = document.getElementById('setor_id');
    const unidadeSelect = document.getElementById('unidade_id');
		const getUnidades = async () => {
			if (setorSelect.value) {
				// Desabilitar inputs durante o carregamento do request.
				setorSelect.setAttribute('disabled', 'disabled');
				unidadeSelect.setAttribute('disabled', 'disabled');
				unidadeSelect.innerHTML = "";
				const loading = document.createElement('option');
				loading.value = "";
				loading.innerText = "Carregando...";
				unidadeSelect.append(loading);

				try {
					// Envio do request para API.
					const response = await axios.get('/api/unidades', {
						params: {
								setor_id: setorSelect.value
						}
					});

					const unidades = response.data;
					unidadeSelect.innerHTML = "";

					// Atribuir as opções
					const selecione = document.createElement('option');
					selecione.value = "";
					selecione.innerText = "Selecione a unidade";
					selecione.setAttribute('selected', 'selected');
					unidadeSelect.append(selecione);
					for (let unidade of unidades) {
						const newOption = document.createElement('option');
						newOption.value = unidade.id;
						newOption.innerText = unidade.nome;
						unidadeSelect.append(newOption);
					}

					// Liberar inputs
					unidadeSelect.removeAttribute('disabled');
					setorSelect.removeAttribute('disabled');

				} catch (error) {
					console.log(error);
					unidadeSelect.removeAttribute('disabled');
					setorSelect.removeAttribute('disabled');
				}
			}
    }
	</script>
	<script>
		$(function() {
    	$("#data").datepicker({
				maxDate: 0,
				dateFormat: 'dd/mm/yy',
				altFormat: 'yy-mm-dd 12:00:00',
				altField: '#datecontainer',
			});
  	});
	</script>
  <script src="{{ asset('js/fileInput.js') }}" defer></script>
@endpush