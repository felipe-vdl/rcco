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
									<option value="" selected>Selecione o setor</option>
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
		<div id="topicos">
			{{-- For each tópico: --}}
			{{-- <div class="x_panel">
				<div class="x_content"></div>
			</div> --}}
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

		const getFormulario = async () => {
			if (unidadeSelect.value) {
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
					renderFormulario(dados);

					unidadeSelect.removeAttribute('disabled');
					setorSelect.removeAttribute('disabled');

				} catch (error) {
					console.log(error);
					unidadeSelect.removeAttribute('disabled');
					setorSelect.removeAttribute('disabled');
				}
			}
		}

		const renderFormulario = topicos => {
			const topicosDiv = document.getElementById('topicos');
			topicosDiv.innerHTML = "";

			console.log(topicos);

			let t = 0;
			for(let topico of topicos) {

				let i = 0;
				let j = 0;
				let k = 0;
				let l = 0;
				let n = 0;

				/* x_panel */
				const xPanel = document.createElement('div');
				xPanel.classList.add('x_panel');
				const xContent = document.createElement('div');
				xContent.classList.add('x_content');
				const topicoContainer = document.createElement('div');
				topicoContainer.classList.add('container');

				/* Tópico Title */
				const titleRow = document.createElement('div');
				titleRow.classList.add('row');
				const topicoTitle = document.createElement('h1');
				topicoTitle.classList.add('text-center');
				topicoTitle.innerText = topico.nome;

				titleRow.append(topicoTitle);
				topicoContainer.append(titleRow);
				xContent.append(topicoContainer);
				xPanel.append(xContent);
				topicosDiv.append(xPanel);

				/* Perguntas */
				for (let pergunta of topico.perguntas) {
					if(pergunta.formato === 'text') {
						const row = document.createElement('div');
						row.classList.add('row');
						row.style = "margin-top: 1rem";

						const formGroup = document.createElement('div');
						formGroup.classList.add('form-group', 'col-12', 'col-md-6');
						formGroup.style = "padding: 0;"

						const label = document.createElement('h2');
						label.classList.add('col-12');
						label.innerText = pergunta.nome;

						const input = document.createElement('input');
						input.type = pergunta.tipo === 'string' ? 'text' : 'number';
						input.required = pergunta.is_required ? true : false;
						input.style = "width: 100%;"
						input.name = `topicos[${t}][textos_simples][${i}][valor]`;

						const hidden = document.createElement('input');
						hidden.type = 'hidden';
						hidden.name = `topicos[${t}][textos_simples][${i}][pergunta_id]`;
						hidden.value = pergunta.id;

						const hidden2 = document.createElement('input');
						hidden2.type = 'hidden';
						hidden2.name = `topicos[${t}][textos_simples][${i}][topico_id]`;
						hidden2.value = topico.id;

						formGroup.append(label, hidden, hidden2, input);
						row.append(formGroup);
						topicoContainer.append(row);

						i++;

					} else if (pergunta.formato === 'textarea') {
						const row = document.createElement('div');
						row.classList.add('row');
						row.style = "margin-top: 1rem";

						const formGroup = document.createElement('div');
						formGroup.classList.add('form-group', 'col-12');
						formGroup.style = "padding: 0;"

						const label = document.createElement('h2');
						label.classList.add('col-12');
						label.innerText = pergunta.nome;

						const textArea = document.createElement('textarea');
						textArea.classList.add('form-control');
						textArea.required = pergunta.is_required ? true : false;
						textArea.name = `topicos[${t}][textos_grandes][${j}][valor]`;

						const hidden = document.createElement('input');
						hidden.type = 'hidden';
						hidden.name = `topicos[${t}][textos_grandes][${j}][pergunta_id]`;
						hidden.value = pergunta.id;

						const hidden2 = document.createElement('input');
						hidden2.type = 'hidden';
						hidden2.name = `topicos[${t}][textos_grandes][${j}][topico_id]`;
						hidden2.value = topico.id;

						formGroup.append(label, hidden, hidden2, textArea);
						row.append(formGroup);
						topicoContainer.append(row);

						j++

					} else if (pergunta.formato === 'radio') {
						const row = document.createElement('div');
						row.classList.add('row');
						row.style = "margin-top: 1rem";

						const formGroup = document.createElement('div');
						formGroup.classList.add('form-group', 'col-12');

						const radioTitle = document.createElement('h2');
						radioTitle.classList.add('col-12');
						radioTitle.innerText = pergunta.nome;

						const hidden = document.createElement('input');
						hidden.type = 'hidden';
						hidden.name = `topicos[${t}][radios][${k}][pergunta_id]`;
						hidden.value = pergunta.id;

						const hidden2 = document.createElement('input');
						hidden2.type = 'hidden';
						hidden2.name = `topicos[${t}][radios][${k}][topico_id]`;
						hidden2.value = topico.id;

						formGroup.append(radioTitle, hidden, hidden2);

						for (let label of pergunta.label_options) {
							const formRadio = document.createElement('div');
							formRadio.classList.add('col-md-6', 'col-sm-6', 'col-xs-12');
							formRadio.style = 'display: flex; align-items: center';

							const rdBox = document.createElement('input');
							rdBox.type = 'radio';
							rdBox.name = `topicos[${t}][radios][${k}][valor]`;
							rdBox.classList.add('form-check-input');
							rdBox.value = label.nome;
							rdBox.required = pergunta.is_required ? true : false;

							const rdLabel = document.createElement('label');
							rdLabel.classList.add('form-check-label');
							rdLabel.style = "margin: 0;"
							rdLabel.innerText = label.nome;

							formRadio.append(rdBox, rdLabel);
							formGroup.append(formRadio);
							row.append(formGroup);
							topicoContainer.append(row);
						}
						k++;

					} else if (pergunta.formato === 'checkbox') {
						const row = document.createElement('div');
						row.classList.add('row');
						row.style = "margin-top: 1rem";

						const formGroup = document.createElement('div');
						formGroup.classList.add('form-group', 'col-12');

						const checkboxTitle = document.createElement('h2');
						checkboxTitle.classList.add('col-12');
						checkboxTitle.innerText = pergunta.nome;
						formGroup.append(checkboxTitle);

						let m = 0;
						for (let label of pergunta.label_options) {
							const formCheck = document.createElement('div');
							formCheck.classList.add('col-md-6', 'col-sm-6', 'col-xs-12');
							formCheck.style = "display: flex; align-items: center;";

							const hidden = document.createElement("input");
							hidden.type = "hidden";
							hidden.value = "0";
							hidden.name = `topicos[${t}][checkboxes][${l}][${m}][valor]`;

							const hidden2 = document.createElement("input");
							hidden2.type = "hidden";
							hidden2.value = pergunta.id;
							hidden2.name = `topicos[${t}][checkboxes][${l}][${m}][pergunta_id]`;

							const hidden3 = document.createElement("input");
							hidden3.type = "hidden";
							hidden3.value = topico.id;
							hidden3.name = `topicos[${t}][checkboxes][${l}][${m}][topico_id]`;

							const hidden4 = document.createElement("input");
							hidden4.type = "hidden";
							hidden4.value = label.id;
							hidden4.name = `topicos[${t}][checkboxes][${l}][${m}][label_option_id]`;

							ckbox = document.createElement('input');
							ckbox.type = "checkbox";
							ckbox.name = `topicos[${t}][checkboxes][${l}][${m}][valor]`;
							ckbox.classList.add('form-check-input');
							ckbox.value = "1";

							const ckLabel = document.createElement('label');
							ckLabel.classList.add('form-check-label');
							ckLabel.style = "margin: 0;";
							ckLabel.innerText = label.nome;

							formCheck.append(hidden, hidden2, hidden3, hidden4, ckbox, ckLabel);
							formGroup.append(formCheck);
							row.append(formGroup);
							topicoContainer.append(row);

							m++;
						}
						l++;

					} else if (pergunta.formato === 'dropdown') {
						const row = document.createElement('div');
						row.classList.add('row');
						row.style = "margin-top: 1rem";

						const formGroup = document.createElement('div');
						formGroup.classList.add('form-group', 'col-12');

						const label = document.createElement('h2');
						label.classList.add('col-12');
						label.innerText = pergunta.nome;

						const input = document.createElement('input');
						input.required = pergunta.is_required ? true : false;
						input.name = `topicos[${t}][dropdowns][${n}][valor]`;

						const hidden = document.createElement('input');
						hidden.type = 'hidden';
						hidden.name = `topicos[${t}][dropdowns][${n}][pergunta_id]`;
						hidden.value = pergunta.id;

						const hidden2 = document.createElement('input');
						hidden2.type = 'hidden';
						hidden2.name = `topicos[${t}][dropdowns][${n}][topico_id]`;
						hidden2.value = topico.id;

						formGroup.append(label, hidden, hidden2, input);
						row.append(formGroup);
						topicoContainer.append(row);

						let options = pergunta.label_options.map(lb => {
							return { text: lb.nome, value: lb.nome };
						});

						let settings = {
							maxItems: 1,
							plugins: [],
							options: options,
							sortField: {
									field: 'text',
									direction: 'asc'
							}
						};

						new TomSelect(`input[name='topicos[${t}][dropdowns][${n}][valor]']`, settings);

						n++;
					}
				}
				t++;
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