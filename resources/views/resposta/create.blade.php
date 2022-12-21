@extends('gentelella.layouts.app')
@section('content')
<div class="x_panel modal-content">
	<div class="x_title">
		 <h2>Novo Relatório</h2>
		<div class="clearfix"></div>
	</div>
	@if(Auth::user()->nivel === "User")
		<form action="{{route('resposta.store')}}" method="post" enctype="multipart/form-data">
		{{ csrf_field() }}
	@endif
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
						<div class="form-group col-md-6 col-sm-6 col-xs-12" id="marcador-div" style="display: none;">
							<label class="control-label">Marcador</label>
							<select id="marcador-select" name="marcador_id" class="form-control" minlength="2" disabled>
								<option value="" selected>Selecione a unidade para carregar as opções</option>
								{{-- API --}}
							</select>
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
			<input type="submit" hidden>
			<button @if(Auth::user()->nivel !== "User") disabled @endif id="btn_cancelar" class="botoes-acao btn btn-round btn-primary" >
				<span class="icone-botoes-acao mdi mdi-backburger"></span>   
				<span class="texto-botoes-acao" > CANCELAR </span>
				<div class="ripple-container"></div>
			</button>
			<button @if(Auth::user()->nivel !== "User") disabled @endif type="submit" id="btn_salvar" class="botoes-acao btn btn-round btn-success ">
				<span class="icone-botoes-acao mdi mdi-send"></span>
				<span class="texto-botoes-acao" > SALVAR </span>
				<div class="ripple-container"></div>
			</button>
		</div>
	@if(Auth::user()->nivel === "User")
		</form>
	@endif
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
					
					const topicos = response.data[0];
					const marcadores = response.data[1];
					renderFormulario(topicos, marcadores);

					unidadeSelect.removeAttribute('disabled');
					setorSelect.removeAttribute('disabled');

				} catch (error) {
					console.log(error);
					unidadeSelect.removeAttribute('disabled');
					setorSelect.removeAttribute('disabled');
				}
			}
		}

		const renderFormulario = (topicos, marcadores) => {
			const marcadorDiv = document.getElementById('marcador-div');
			const marcadorSelect = document.getElementById('marcador-select');

			marcadorDiv.style.display = "none";
			marcadorSelect.innerHTML = "";
			marcadorSelect.removeAttribute('required');

			if (marcadores.length > 0) {
				marcadorDiv.style.display = "";

				const selecione = document.createElement('option');
					selecione.innerText = "Selecione um marcador";
					selecione.value = "";
					marcadorSelect.append(selecione);

				for (let marcador of marcadores) {
					const option = document.createElement('option');
					option.innerText = marcador.nome;
					option.value = marcador.id;
					option.style.color = marcador.color;
					marcadorSelect.append(option);
				}

				// marcadorSelect.setAttribute('required', 'required');
				marcadorSelect.removeAttribute('disabled');

			}

			const topicosDiv = document.getElementById('topicos');
			topicosDiv.innerHTML = "";

			let t = 0;
			for(let topico of topicos) {
				if(topico.perguntas.length == 0) {
					continue
				}

				let i = 0;
				let j = 0;
				let k = 0;
				let l = 0;
				let n = 0;
				let o = 0;

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
					} else if (pergunta.tipo === 'image') {
						const row = document.createElement('div');
						row.classList.add('row');
						row.style = "margin-top: 1rem";

						const formGroup = document.createElement('div');
						formGroup.classList.add('form-group', 'col-12');
						formGroup.style = "padding: 0;"

						formGroup.innerHTML = `<h2 class="col-12">${pergunta.nome}</h2>
							<p class="mb-2">
								<label for="topicos-${t}-imagens-${o}-arquivos">
									<a class="btn btn-primary text-light" type="button" role="button" aria-disabled="false">Adicionar Arquivo</a>
								</label>
								<input
									id="topicos-${t}-imagens-${o}-arquivos"
									type="file"
									class="form-control"
									multiple="multiple"
									name="topicos[${t}][imagens][${o}][arquivos][]"
									style="visibility: hidden; position: absolute;"
									accept="image/*"
								>
								<input type="hidden" name="topicos[${t}][imagens][${o}][pergunta_id]" value="${pergunta.id}">
								<input type="hidden" name="topicos[${t}][imagens][${o}][topico_id]" value="${topico.id}">
							</p>
							<div id="erro-${t}-${o}" class="alert alert-danger mb-2" style="display: none;">
									<p class="m-0">Tipo de arquivo inválido, insira apenas imagens: <span id="${t}-${o}-invalido"></span></p>
							</div>
							<div id="erro-${t}-${o}-grande" class="alert alert-danger mb-2" style="display: none;">
									<p class="m-0">Arquivo ultrapassa o limite de tamanho permitido: <span id="${t}-${o}-grande"></span></p>
							</div>
							<p id="${t}-${o}-vermelho" style="font-size: 13px; color: red; display: none;" class="mb-2">* Atenção: Os arquivos destacados em vermelho não serão enviados.</p>
							<p id="${t}-${o}-area">
									<span id="${t}-${o}-list">
											<span id="${t}-${o}-names"></span>
									</span>
							</p>`;

						row.append(formGroup);
						topicoContainer.append(row);

						const fileTypes = ['image', 'png', 'jpg', 'jpeg'];
						const tamanhoMaximo = 10000000; // 10 MB

						const dtImage = new DataTransfer();
						const imageInput = document.getElementById(`topicos-${t}-imagens-${o}-arquivos`);
						const imagesArea = document.getElementById(`${t}-${o}-area`);
						const imageInvalido = document.getElementById(`${t}-${o}-invalido`);
						const erroImage = document.getElementById(`erro-${t}-${o}`);
						const imageVermelho = document.getElementById(`${t}-${o}-vermelho`);
						const erroImageGrande = document.getElementById(`erro-${t}-${o}-grande`);
						const imageGrandeSpan = document.getElementById(`${t}-${o}-grande`);

						imageInput.addEventListener('change', function(e) {
							// Limpa os nomes de arquivo do último input feito pelo usuário.
							let imagesInvalidos = [];
							let verifyImages = null;
							imageInvalido.innerHTML = '';
							let imagesGrandes = [];
							let imageGrande = null;
							imageGrandeSpan.innerHTML = '';

							// Nome do arquivo e botão de deletar.
							for(let i = 0; i < this.files.length; i++) {
									let fileBlock = document.createElement('span');
									fileBlock.classList.add('file-block');
									
									let fileName = document.createElement('span');
									fileName.classList.add('name');
									fileName.innerHTML = `${this.files.item(i).name}`;
									
									let fileDelete = document.createElement('span');
									fileDelete.classList.add('file-delete');
									fileDelete.innerHTML = '<span>X</span>';
									// Checa a validez do tipo do arquivo inserido.
									if (!fileTypes.some(el => this.files[i].type.includes(el))) {
											// Caso exista um arquivo inválido, insere nome dos arquivos inválidos na array e atribui true para a presença de images inválidos.
											imagesInvalidos.push(this.files[i].name);
											fileName.classList.add('text-danger');
											fileDelete.classList.add('text-danger');
											verifyImages = true;
											imageVermelho.style.display = 'block';
									} else if (this.files[i].size > tamanhoMaximo) {
											imagesGrandes.push(this.files[i].name);
											fileName.classList.add('text-danger');
											fileDelete.classList.add('text-danger');
											imageGrande = true;
											imageVermelho.style.display = 'block';
									}

									fileBlock.append(fileDelete, fileName);
									imagesArea.append(fileBlock);
							}

							// Checa a existência de images inválidos.
							if (imagesInvalidos.length === 0) {
									// Caso todos os arquivos sejam válidos, esconde a mensagem de erro e atribui false para presença de images inválidos.
									erroImage.style.display = 'none';
									verifyImages = false;
							}

							// Checa a existência de images com tamanho maior que o permitido.
							if (imagesGrandes.length === 0) {
									// Caso todos os arquivos sejam válidos, esconde a mensagem de erro e atribui false para presença de images inválidos.
									erroImageGrande.style.display = 'none';
									imageGrande = false;
							}

							// Guarda os arquivos no objeto de DataTransfer.
							for (let file of this.files) {
									// Checa validez do tipo de arquivo antes de inserir.
									if (fileTypes.some(el => file.type.includes(el))) {
											if (file.size < tamanhoMaximo) {
													dtImage.items.add(file);
											}
									}
							}

							// Checa o status de presença de arquivos inválidos.
							let i = 1; // Variável de controle da formatação.
							if (verifyImages) {
									// Caso existam arquivos inválidos, insere o nome de cada arquivo inválido no alerta de erro da view.
									for (let image of imagesInvalidos) {
											if (i < imagesInvalidos.length) {
													imageInvalido.append(`${image}, `);
											} else {
													imageInvalido.append(`${image}.`)
											}
											i++;
									}
									erroImage.style.display = 'block';
									this.value = '';
							}

							// Checa o status de presença de arquivos maiores que o tamanho máximo.
							let j = 1; // Variável de controle da formatação.
							if (imageGrande) {
									// Caso existam arquivos inválidos, insere o nome de cada arquivo inválido no alerta de erro da view.
									for (let image of imagesGrandes) {
											if (j < imagesGrandes.length) {
													imageGrandeSpan.append(`${image}, `);
											} else {
													imageGrandeSpan.append(`${image}.`)
											}
											j++;
									}
									erroImageGrande.style.display = 'block';
									this.value = '';
							}

							// Atualiza os arquivos do input.
							imageInput.files = dtImage.files;
							// Atribui evento no botão de deletar arquivo.
							let deleteButtons = document.querySelectorAll('.file-delete');
							for (let button of deleteButtons) {
									button.addEventListener('click', function (e) {
											let name = this.nextElementSibling.innerHTML;
											// Remove o nome do arquivo da página.
											this.parentElement.remove();
											
											for(let i = 0; i < dtImage.items.length; i++) {
													if (name === dtImage.items[i].getAsFile().name) {
															// Delete file on DataTransfer Object.
															dtImage.items.remove(i);
															continue;
													}
											}
											imageInput.files = dtImage.files;
									});
							}
						});

						o++
					} else if (pergunta.tipo === 'document') {
						const row = document.createElement('div');
						row.classList.add('row');
						row.style = "margin-top: 1rem";

						const formGroup = document.createElement('div');
						formGroup.classList.add('form-group', 'col-12');
						formGroup.style = "padding: 0;"

						formGroup.innerHTML = `<h2 class="col-12">${pergunta.nome}</h2>
							<p class="mb-2">
								<label for="topicos-${t}-documentos-${o}-arquivos">
									<a class="btn btn-primary text-light" type="button" role="button" aria-disabled="false">Adicionar Arquivo</a>
								</label>
								<input
									id="topicos-${t}-documentos-${o}-arquivos"
									type="file"
									class="form-control"
									multiple="multiple"
									name="topicos[${t}][documentos][${o}][arquivos][]"
									style="visibility: hidden; position: absolute;"
									accept=".doc,.docx,.xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.pdf"
								>
								<input type="hidden" name="topicos[${t}][documentos][${o}][pergunta_id]" value="${pergunta.id}">
								<input type="hidden" name="topicos[${t}][documentos][${o}][topico_id]" value="${topico.id}">
							</p>
							<div id="erro-${t}-${o}" class="alert alert-danger mb-2" style="display: none;">
									<p class="m-0">Tipo de arquivo inválido, insira apenas documentos: <span id="${t}-${o}-invalido"></span></p>
							</div>
							<div id="erro-${t}-${o}-grande" class="alert alert-danger mb-2" style="display: none;">
									<p class="m-0">Arquivo ultrapassa o limite de tamanho permitido: <span id="${t}-${o}-grande"></span></p>
							</div>
							<p id="${t}-${o}-vermelho" style="font-size: 13px; color: red; display: none;" class="mb-2">* Atenção: Os arquivos destacados em vermelho não serão enviados.</p>
							<p id="${t}-${o}-area">
									<span id="${t}-${o}-list">
											<span id="${t}-${o}-names"></span>
									</span>
							</p>`;

						row.append(formGroup);
						topicoContainer.append(row);

						const fileTypes = ['doc', 'docx', 'xml', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'pdf'];
						const tamanhoMaximo = 10000000; // 10 MB

						const dtDocument = new DataTransfer();
						const documentInput = document.getElementById(`topicos-${t}-documentos-${o}-arquivos`);
						const documentsArea = document.getElementById(`${t}-${o}-area`);
						const documentInvalido = document.getElementById(`${t}-${o}-invalido`);
						const erroDocument = document.getElementById(`erro-${t}-${o}`);
						const documentVermelho = document.getElementById(`${t}-${o}-vermelho`);
						const erroDocumentGrande = document.getElementById(`erro-${t}-${o}-grande`);
						const documentGrandeSpan = document.getElementById(`${t}-${o}-grande`);

						documentInput.addEventListener('change', function(e) {
							// Limpa os nomes de arquivo do último input feito pelo usuário.
							let documentsInvalidos = [];
							let verifyDocuments = null;
							documentInvalido.innerHTML = '';
							let documentsGrandes = [];
							let documentGrande = null;
							documentGrandeSpan.innerHTML = '';

							// Nome do arquivo e botão de deletar.
							for(let i = 0; i < this.files.length; i++) {
									let fileBlock = document.createElement('span');
									fileBlock.classList.add('file-block');
									
									let fileName = document.createElement('span');
									fileName.classList.add('name');
									fileName.innerHTML = `${this.files.item(i).name}`;
									
									let fileDelete = document.createElement('span');
									fileDelete.classList.add('file-delete');
									fileDelete.innerHTML = '<span>X</span>';
									// Checa a validez do tipo do arquivo inserido.
									if (!fileTypes.some(el => this.files[i].type.includes(el))) {
											// Caso exista um arquivo inválido, insere nome dos arquivos inválidos na array e atribui true para a presença de documents inválidos.
											documentsInvalidos.push(this.files[i].name);
											fileName.classList.add('text-danger');
											fileDelete.classList.add('text-danger');
											verifyDocuments = true;
											documentVermelho.style.display = 'block';
									} else if (this.files[i].size > tamanhoMaximo) {
											documentsGrandes.push(this.files[i].name);
											fileName.classList.add('text-danger');
											fileDelete.classList.add('text-danger');
											documentGrande = true;
											documentVermelho.style.display = 'block';
									}

									fileBlock.append(fileDelete, fileName);
									documentsArea.append(fileBlock);
							}

							// Checa a existência de documents inválidos.
							if (documentsInvalidos.length === 0) {
									// Caso todos os arquivos sejam válidos, esconde a mensagem de erro e atribui false para presença de documents inválidos.
									erroDocument.style.display = 'none';
									verifyDocuments = false;
							}

							// Checa a existência de documents com tamanho maior que o permitido.
							if (documentsGrandes.length === 0) {
									// Caso todos os arquivos sejam válidos, esconde a mensagem de erro e atribui false para presença de documents inválidos.
									erroDocumentGrande.style.display = 'none';
									documentGrande = false;
							}

							// Guarda os arquivos no objeto de DataTransfer.
							for (let file of this.files) {
									// Checa validez do tipo de arquivo antes de inserir.
									if (fileTypes.some(el => file.type.includes(el))) {
											if (file.size < tamanhoMaximo) {
													dtDocument.items.add(file);
											}
									}
							}

							// Checa o status de presença de arquivos inválidos.
							let i = 1; // Variável de controle da formatação.
							if (verifyDocuments) {
									// Caso existam arquivos inválidos, insere o nome de cada arquivo inválido no alerta de erro da view.
									for (let document of documentsInvalidos) {
											if (i < documentsInvalidos.length) {
													documentInvalido.append(`${document}, `);
											} else {
													documentInvalido.append(`${document}.`)
											}
											i++;
									}
									erroDocument.style.display = 'block';
									this.value = '';
							}

							// Checa o status de presença de arquivos maiores que o tamanho máximo.
							let j = 1; // Variável de controle da formatação.
							if (documentGrande) {
									// Caso existam arquivos inválidos, insere o nome de cada arquivo inválido no alerta de erro da view.
									for (let document of documentsGrandes) {
											if (j < documentsGrandes.length) {
													documentGrandeSpan.append(`${document}, `);
											} else {
													documentGrandeSpan.append(`${document}.`)
											}
											j++;
									}
									erroDocumentGrande.style.display = 'block';
									this.value = '';
							}

							// Atualiza os arquivos do input.
							documentInput.files = dtDocument.files;
							// Atribui evento no botão de deletar arquivo.
							let deleteButtons = document.querySelectorAll('.file-delete');
							for (let button of deleteButtons) {
									button.addEventListener('click', function (e) {
											let name = this.nextElementSibling.innerHTML;
											// Remove o nome do arquivo da página.
											this.parentElement.remove();
											
											for(let i = 0; i < dtDocument.items.length; i++) {
													if (name === dtDocument.items[i].getAsFile().name) {
															// Delete file on DataTransfer Object.
															dtDocument.items.remove(i);
															continue;
													}
											}
											documentInput.files = dtDocument.files;
									});
							}
						});

						o++
					} else if (pergunta.tipo === 'video') {
						const row = document.createElement('div');
						row.classList.add('row');
						row.style = "margin-top: 1rem";

						const formGroup = document.createElement('div');
						formGroup.classList.add('form-group', 'col-12');
						formGroup.style = "padding: 0;"

						formGroup.innerHTML = `<h2 class="col-12">${pergunta.nome}</h2>
							<p class="mb-2">
								<label for="topicos-${t}-videos-${o}-arquivos">
									<a class="btn btn-primary text-light" type="button" role="button" aria-disabled="false">Adicionar Arquivo</a>
								</label>
								<input
									id="topicos-${t}-videos-${o}-arquivos"
									type="file"
									class="form-control"
									multiple="multiple"
									name="topicos[${t}][videos][${o}][arquivos][]"
									style="visibility: hidden; position: absolute;"
									accept="video/mp4,video/x-m4v,video/*"
								>
								<input type="hidden" name="topicos[${t}][videos][${o}][pergunta_id]" value="${pergunta.id}">
								<input type="hidden" name="topicos[${t}][videos][${o}][topico_id]" value="${topico.id}">
							</p>
							<div id="erro-${t}-${o}" class="alert alert-danger mb-2" style="display: none;">
									<p class="m-0">Tipo de arquivo inválido, insira apenas vídeos: <span id="${t}-${o}-invalido"></span></p>
							</div>
							<div id="erro-${t}-${o}-grande" class="alert alert-danger mb-2" style="display: none;">
									<p class="m-0">Arquivo ultrapassa o limite de tamanho permitido: <span id="${t}-${o}-grande"></span></p>
							</div>
							<p id="${t}-${o}-vermelho" style="font-size: 13px; color: red; display: none;" class="mb-2">* Atenção: Os arquivos destacados em vermelho não serão enviados.</p>
							<p id="${t}-${o}-area">
									<span id="${t}-${o}-list">
											<span id="${t}-${o}-names"></span>
									</span>
							</p>`;

						row.append(formGroup);
						topicoContainer.append(row);

						const fileTypes = ['video/mp4', 'video/x-m4v', 'video/*'];
						const tamanhoMaximo = 10000000; // 10 MB

						const dtImage = new DataTransfer();
						const videoInput = document.getElementById(`topicos-${t}-videos-${o}-arquivos`);
						const videosArea = document.getElementById(`${t}-${o}-area`);
						const videoInvalido = document.getElementById(`${t}-${o}-invalido`);
						const erroImage = document.getElementById(`erro-${t}-${o}`);
						const videoVermelho = document.getElementById(`${t}-${o}-vermelho`);
						const erroImageGrande = document.getElementById(`erro-${t}-${o}-grande`);
						const videoGrandeSpan = document.getElementById(`${t}-${o}-grande`);

						videoInput.addEventListener('change', function(e) {
							// Limpa os nomes de arquivo do último input feito pelo usuário.
							let videosInvalidos = [];
							let verifyVideos = null;
							videoInvalido.innerHTML = '';
							let videosGrandes = [];
							let videoGrande = null;
							videoGrandeSpan.innerHTML = '';

							// Nome do arquivo e botão de deletar.
							for(let i = 0; i < this.files.length; i++) {
									let fileBlock = document.createElement('span');
									fileBlock.classList.add('file-block');
									
									let fileName = document.createElement('span');
									fileName.classList.add('name');
									fileName.innerHTML = `${this.files.item(i).name}`;
									
									let fileDelete = document.createElement('span');
									fileDelete.classList.add('file-delete');
									fileDelete.innerHTML = '<span>X</span>';
									// Checa a validez do tipo do arquivo inserido.
									if (!fileTypes.some(el => this.files[i].type.includes(el))) {
											// Caso exista um arquivo inválido, insere nome dos arquivos inválidos na array e atribui true para a presença de videos inválidos.
											videosInvalidos.push(this.files[i].name);
											fileName.classList.add('text-danger');
											fileDelete.classList.add('text-danger');
											verifyVideos = true;
											videoVermelho.style.display = 'block';
									} else if (this.files[i].size > tamanhoMaximo) {
											videosGrandes.push(this.files[i].name);
											fileName.classList.add('text-danger');
											fileDelete.classList.add('text-danger');
											videoGrande = true;
											videoVermelho.style.display = 'block';
									}

									fileBlock.append(fileDelete, fileName);
									videosArea.append(fileBlock);
							}

							// Checa a existência de videos inválidos.
							if (videosInvalidos.length === 0) {
									// Caso todos os arquivos sejam válidos, esconde a mensagem de erro e atribui false para presença de videos inválidos.
									erroImage.style.display = 'none';
									verifyVideos = false;
							}

							// Checa a existência de videos com tamanho maior que o permitido.
							if (videosGrandes.length === 0) {
									// Caso todos os arquivos sejam válidos, esconde a mensagem de erro e atribui false para presença de videos inválidos.
									erroImageGrande.style.display = 'none';
									videoGrande = false;
							}

							// Guarda os arquivos no objeto de DataTransfer.
							for (let file of this.files) {
									// Checa validez do tipo de arquivo antes de inserir.
									if (fileTypes.some(el => file.type.includes(el))) {
											if (file.size < tamanhoMaximo) {
													dtImage.items.add(file);
											}
									}
							}

							// Checa o status de presença de arquivos inválidos.
							let i = 1; // Variável de controle da formatação.
							if (verifyVideos) {
									// Caso existam arquivos inválidos, insere o nome de cada arquivo inválido no alerta de erro da view.
									for (let video of videosInvalidos) {
											if (i < videosInvalidos.length) {
													videoInvalido.append(`${video}, `);
											} else {
													videoInvalido.append(`${video}.`)
											}
											i++;
									}
									erroImage.style.display = 'block';
									this.value = '';
							}

							// Checa o status de presença de arquivos maiores que o tamanho máximo.
							let j = 1; // Variável de controle da formatação.
							if (videoGrande) {
									// Caso existam arquivos inválidos, insere o nome de cada arquivo inválido no alerta de erro da view.
									for (let video of videosGrandes) {
											if (j < videosGrandes.length) {
													videoGrandeSpan.append(`${video}, `);
											} else {
													videoGrandeSpan.append(`${video}.`)
											}
											j++;
									}
									erroImageGrande.style.display = 'block';
									this.value = '';
							}

							// Atualiza os arquivos do input.
							videoInput.files = dtImage.files;
							// Atribui evento no botão de deletar arquivo.
							let deleteButtons = document.querySelectorAll('.file-delete');
							for (let button of deleteButtons) {
									button.addEventListener('click', function (e) {
											let name = this.nextElementSibling.innerHTML;
											// Remove o nome do arquivo da página.
											this.parentElement.remove();
											
											for(let i = 0; i < dtImage.items.length; i++) {
													if (name === dtImage.items[i].getAsFile().name) {
															// Delete file on DataTransfer Object.
															dtImage.items.remove(i);
															continue;
													}
											}
											videoInput.files = dtImage.files;
									});
							}
						});

						o++
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