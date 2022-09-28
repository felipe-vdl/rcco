@extends('gentelella.layouts.app')
@section('content')
<div class="x_panel modal-content">
	<div class="x_title">
		 <h2>Novo Relatório</h2>
		<div class="clearfix"></div>
	</div>
	<form action="{{route('resposta.store')}}" method="post">
	{{ csrf_field() }}
		<div class="x_panel">
			<div class="x_content">
					<div class="form-group row">
						<div class="form-group col-md-6 col-sm-6 col-xs-12">
							<label class="control-label">Setor</label>
							<select onchange="getUnidades()" id="setor_id" name="setor_id" class="form-control" minlength="2" required>
									<option value="" disabled selected>Selecione o setor</option>
                  @foreach ($setores_usuario_logado as $setor)
                    <option value="{{$setor->id}}">{{$setor->nome}}</option>
                  @endforeach
							</select>
						</div>
						<div class="form-group col-md-6 col-sm-6 col-xs-12">
							<label class="control-label">Unidade</label>
							<select onchange="getFormulario()" id="unidade_id" name="unidade_id" class="form-control" minlength="2" required disabled>
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
					</div>
			</div>
		</div>
		<div class="x_panel">
			<div class="x_content">
				<div id="campos" class="form-group container">
				</div>
			</div>
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
	<script>
		const userID = "{{ Auth::user()->id }}";
    const setorSelect = document.getElementById('setor_id');
    const unidadeSelect = document.getElementById('unidade_id');

		const getUnidades = async () => {
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
				selecione.setAttribute('disabled', 'disabled');
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

		const getFormulario = async () => {
			setorSelect.setAttribute('disabled', 'disabled');
			unidadeSelect.setAttribute('disabled', 'disabled');

			try {
				const response = await axios.get('/api/formulario', {
					params: {
						setor_id: setorSelect.value,
						unidade_id: unidadeSelect.value
					},
				});
        
				const dados = response.data;
        console.log(dados);
        /* TODO: Renderizar tópicos + perguntas */
				// renderFormulario(dados);
				unidadeSelect.removeAttribute('disabled');
        setorSelect.removeAttribute('disabled');

			} catch (error) {
				console.log(error);
				unidadeSelect.removeAttribute('disabled');
        setorSelect.removeAttribute('disabled');
			}
		}

		const renderFormulario = data => {
			const camposContainer = document.getElementById('campos');
			camposContainer.innerHTML = "";
			let i = 0;
			let j = 0
			let k = 0;
			let m = 0;
			console.log(data);
			
			// Textos Simples
			for (let campo of data) {
				if (campo.tipo === 'text') {
					const formGroup = document.createElement('div');
					formGroup.classList.add('form-group', 'row');

					const formDiv = document.createElement('div');
					formDiv.classList.add('form-group','col-12', 'col-md-6');

					const label = document.createElement('label');
					label.classList.add('control-label');
					label.innerText = campo.nome;

					const input = document.createElement('input');
					input.type = 'text';
					input.classList.add('form-control');
					input.required = campo.is_required ? true : false;
					input.name = `textos_simples[${i}][valor]`;

					const hidden = document.createElement('input');
					hidden.type = 'hidden';
					hidden.name = `textos_simples[${i}][pergunta_id]`;
					hidden.value = campo.id;

					formDiv.append(label, input, hidden);
					formGroup.append(formDiv);
					camposContainer.append(formGroup);

					i++;

				} else if (campo.tipo === 'textarea') {
					const formGroup = document.createElement('div');
					formGroup.classList.add('form-group', 'row');

					const formDiv = document.createElement('div');
					formDiv.classList.add('form-group','col-12');

					const label = document.createElement('label');
					label.classList.add('control-label');
					label.innerText = campo.nome;

					const textArea = document.createElement('textarea');
					textArea.classList.add('form-control');
					textArea.required = campo.is_required ? true : false;
					textArea.name = `textos_grandes[${j}][valor]`;

					const hidden = document.createElement('input');
					hidden.type = 'hidden';
					hidden.name = `textos_grandes[${j}][pergunta_id]`;
					hidden.value = campo.id;

					formDiv.append(label, textArea, hidden);
					formGroup.append(formDiv);
					camposContainer.append(formGroup);

					j++;

				} else if (campo.tipo === 'checkbox') {
					const formGroup1 = document.createElement('div');
					formGroup1.classList.add('form-group', 'row');

					const formDiv = document.createElement('div');
					formDiv.classList.add('form-group','col-12');

					const title = document.createElement('h2');
					title.innerText = campo.nome;
					title.classList.add('col-12');
					formDiv.append(title);
					
					let l = 0;
					for (let label of campo.labels) {
						const formCheck = document.createElement('div');
						formCheck.classList.add('col-md-6', 'col-sm-6', 'col-xs-12');
						formCheck.style = "display: flex; align-items: center;";

						const hidden = document.createElement('input');
						hidden.type = "hidden";
						hidden.value = "0";
						hidden.name = `checkboxes[${k}][${l}][valor]`;
						
						const hidden2 = document.createElement('input');
						hidden2.type = "hidden";
						hidden2.value = label.pergunta_id;
						hidden2.name = `checkboxes[${k}][${l}][pergunta_id]`;

						const hidden3 = document.createElement('input');
						hidden3.type = "hidden";
						hidden3.value = label.id;
						hidden3.name = `checkboxes[${k}][${l}][label_option_id]`;

						const ckbox = document.createElement('input');
						ckbox.type = "checkbox";
						ckbox.name = `checkboxes[${k}][${l}][valor]`;
						ckbox.classList.add('form-check-input');
						ckbox.value = "1";

						const ckLabel = document.createElement('label');
						ckLabel.classList.add('form-check-label');
						ckLabel.style = "margin: 0;";
						ckLabel.innerText = label.nome;

						formCheck.append(hidden, hidden2, hidden3, ckbox, ckLabel);
						formDiv.append(formCheck);
						formGroup1.append(formDiv);
						camposContainer.append(formGroup1);

						l++;
					}
					k++

				} else if (campo.tipo === 'radio') {
					const formGroup2 = document.createElement('div');
					formGroup2.classList.add('form-group', 'row');

					const formDiv = document.createElement('div');
					formDiv.classList.add('form-group','col-12');

					const title = document.createElement('h2');
					title.innerText = campo.nome;
					title.classList.add('col-12');
					
					const hidden1 = document.createElement('input');
					hidden1.type = 'hidden';
					hidden1.value = campo.id;
					hidden1.name = `radios[${m}][pergunta_id]`;
					
					formDiv.append(title, hidden1);

					for (let label of campo.labels) {
						const formRadio = document.createElement('div');
						formRadio.classList.add('col-md-6', 'col-sm-6', 'col-xs-12');
						formRadio.style = 'display: flex; align-items: center';

						const rdBox = document.createElement('input');
						rdBox.type = 'radio';
						rdBox.name = `radios[${m}][valor]`;
						rdBox.classList.add('form-check-input');
						rdBox.value = label.nome;
						rdBox.required = campo.is_required ? true : false;

						const rdLabel = document.createElement('label');
						rdLabel.classList.add('form-check-label');
						rdLabel.style = "margin: 0;";
						rdLabel.innerText = label.nome;

						formRadio.append(rdBox, rdLabel);
						formDiv.append(formRadio);
						formGroup2.append(formDiv);
						camposContainer.append(formGroup2);
					}
					m++;
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
@endpush