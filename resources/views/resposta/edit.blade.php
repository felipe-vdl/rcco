@extends('gentelella.layouts.app')
@section('content')
<div class="x_panel modal-content">
	<div class="x_title">
		 <h2>Novo Relatório</h2>
		<div class="clearfix"></div>
	</div>
	<form action="{{route('resposta.update', $unidade->id)}}" method="post" enctype="multipart/form-data">
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
            @if (count($marcadores) > 0)
              <div class="form-group col-md-6 col-sm-6 col-xs-12" id="marcador-div">
                <label class="control-label">Marcador</label>
                <select id="marcador-select" name="marcador_id" class="form-control" minlength="2">
                  <option value="">Selecione o marcador</option>
                  @foreach ($marcadores as $marcador)
                    <option @if($marcador->id == $marcador_atual_id) style="color:{{$marcador->color}};" selected @endif value="{{$marcador->id}}">{{$marcador->nome}}</option>
                  @endforeach
                </select>
              </div>
            @endif
					</div>
			</div>
		</div>
		<div id="topicos">
      @foreach($topicos as $topico)
        @if(count($topico->respostas) > 0)
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
                        @foreach($resposta->label_valors as $label)
                          <div class="col-md-6 col-sm-6 col-xs-12" style="display: flex; align-items: center;">
                            <input type="hidden" name="{{'topicos['.$loop->parent->parent->index.'][checkboxes]['.$loop->parent->index.']['.$loop->index.'][label_valor_id]'}}" value="{{$label->id}}">
                            <input type="hidden" value="{{$resposta->id}}" name="{{'topicos['.$loop->parent->parent->index.'][checkboxes]['.$loop->parent->index.']['.$loop->index.'][resposta_id]'}}" >
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
                      @elseif($resposta->pergunta->tipo === "image")
                        @foreach($resposta->arquivos as $arquivo)
                          <div class="col-md-6 col-sm-6 col-xs-12" style="display: flex; align-items: center;">
                            <input type="checkbox" value="{{$arquivo->id}}" name="{{'topicos['.$loop->parent->parent->index.'][imagens]['.$loop->parent->index.'][remove_arquivo][]'}}" >
                            <label class="form-check-label" style="margin: 0;">{{$arquivo->nome_origem}}</label>
                          </div>
                        @endforeach
                        <p class="mb-2 col-xs-12">
                          <label for="{{'topicos-'.$loop->parent->index.'-imagens-'.$loop->index.'-arquivos'}}">
                            <a class="btn btn-primary text-light" type="button" role="button" aria-disabled="false">Adicionar Arquivo</a>
                          </label>
                          <input
                            id="{{'topicos-'.$loop->parent->index.'-imagens-'.$loop->index.'-arquivos'}}"
                            type="file"
                            class="form-control"
                            multiple="multiple"
                            name="{{'topicos['.$loop->parent->index.'][imagens]['.$loop->index.'][arquivos][]'}}"
                            style="visibility: hidden; position: absolute;"
                            accept="image/*"
                          >
                          <input type="hidden" name="{{'topicos['.$loop->parent->index.'][imagens]['.$loop->index.'][pergunta_id]'}}" value="{{$resposta->pergunta->id}}">
                          <input type="hidden" name="{{'topicos['.$loop->parent->index.'][imagens]['.$loop->index.'][resposta_id]'}}" value="{{$resposta->id}}">
                        </p>
                        <div id="{{'erro-'.$loop->parent->index.'-'.$loop->index}}" class="alert alert-danger mb-2" style="display: none;">
                          <p class="m-0">Tipo de arquivo inválido, insira apenas imagens: <span id="{{$loop->parent->index.'-'.$loop->index.'-invalido'}}"></span></p>
                        </div>
                        <div id="{{'erro-'.$loop->parent->index.'-'.$loop->index.'-grande'}}" class="alert alert-danger mb-2" style="display: none;">
                          <p class="m-0">Arquivo ultrapassa o limite de tamanho permitido: <span id="{{$loop->parent->index.'-'.$loop->index.'-grande'}}"></span></p>
                        </div>
                        <p id="{{$loop->parent->index.'-'.$loop->index.'-vermelho'}}" style="font-size: 13px; color: red; display: none;" class="mb-2">* Atenção: Os arquivos destacados em vermelho não serão enviados.</p>
                        <p id="{{$loop->parent->index.'-'.$loop->index.'-area'}}">
                          <span id="{{$loop->parent->index.'-'.$loop->index.'-list'}}">
                            <span id="{{$loop->parent->index.'-'.$loop->index.'-names'}}"></span>
                          </span>
                        </p>
                        @push('scripts')
                          <script>
                            if(true) {
                              let fileTypesImage = ['image', 'png', 'jpg', 'jpeg'];
                              let tamanhoMaximo = 10000000; // 10 MB

                              let dtImage = new DataTransfer();
                              let imageInput = document.getElementById(`topicos-${'{{$loop->parent->index}}'}-imagens-${'{{$loop->index}}'}-arquivos`);
                              let imagesArea = document.getElementById(`${'{{$loop->parent->index}}'}-${'{{$loop->index}}'}-area`);
                              let imageInvalido = document.getElementById(`${'{{$loop->parent->index}}'}-${'{{$loop->index}}'}-invalido`);
                              let erroImage = document.getElementById(`erro-${'{{$loop->parent->index}}'}-${'{{$loop->index}}'}`);
                              let imageVermelho = document.getElementById(`${'{{$loop->parent->index}}'}-${'{{$loop->index}}'}-vermelho`);
                              let erroImageGrande = document.getElementById(`erro-${'{{$loop->parent->index}}'}-${'{{$loop->index}}'}-grande`);
                              let imageGrandeSpan = document.getElementById(`${'{{$loop->parent->index}}'}-${'{{$loop->index}}'}-grande`);

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
                                    if (!fileTypesImage.some(el => this.files[i].type.includes(el))) {
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
                                    if (fileTypesImage.some(el => file.type.includes(el))) {
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
                            }
                          </script>
                        @endpush
                        @elseif($resposta->pergunta->tipo === "document")
                          @foreach($resposta->arquivos as $arquivo)
                            <div class="col-md-6 col-sm-6 col-xs-12" style="display: flex; align-items: center;">
                              <input type="checkbox" value="{{$arquivo->id}}" name="{{'topicos['.$loop->parent->parent->index.'][documentos]['.$loop->parent->index.'][remove_arquivo][]'}}" >
                              <label class="form-check-label" style="margin: 0;">{{$arquivo->nome_origem}}</label>
                            </div>
                          @endforeach
                          <p class="mb-2 col-xs-12">
                            <label for="{{'topicos-'.$loop->parent->index.'-documentos-'.$loop->index.'-arquivos'}}">
                              <a class="btn btn-primary text-light" type="button" role="button" aria-disabled="false">Adicionar Arquivo</a>
                            </label>
                            <input
                              id="{{'topicos-'.$loop->parent->index.'-documentos-'.$loop->index.'-arquivos'}}"
                              type="file"
                              class="form-control"
                              multiple="multiple"
                              name="{{'topicos['.$loop->parent->index.'][documentos]['.$loop->index.'][arquivos][]'}}"
                              style="visibility: hidden; position: absolute;"
                              accept=".doc,.docx,.xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.pdf"
                            >
                            <input type="hidden" name="{{'topicos['.$loop->parent->index.'][documentos]['.$loop->index.'][pergunta_id]'}}" value="{{$resposta->pergunta->id}}">
                            <input type="hidden" name="{{'topicos['.$loop->parent->index.'][documentos]['.$loop->index.'][resposta_id]'}}" value="{{$resposta->id}}">
                          </p>
                          <div id="{{'erro-'.$loop->parent->index.'-'.$loop->index}}" class="alert alert-danger mb-2" style="display: none;">
                            <p class="m-0">Tipo de arquivo inválido, insira apenas documentos: <span id="{{$loop->parent->index.'-'.$loop->index.'-invalido'}}"></span></p>
                          </div>
                          <div id="{{'erro-'.$loop->parent->index.'-'.$loop->index.'-grande'}}" class="alert alert-danger mb-2" style="display: none;">
                            <p class="m-0">Arquivo ultrapassa o limite de tamanho permitido: <span id="{{$loop->parent->index.'-'.$loop->index.'-grande'}}"></span></p>
                          </div>
                          <p id="{{$loop->parent->index.'-'.$loop->index.'-vermelho'}}" style="font-size: 13px; color: red; display: none;" class="mb-2">* Atenção: Os arquivos destacados em vermelho não serão enviados.</p>
                          <p id="{{$loop->parent->index.'-'.$loop->index.'-area'}}">
                            <span id="{{$loop->parent->index.'-'.$loop->index.'-list'}}">
                              <span id="{{$loop->parent->index.'-'.$loop->index.'-names'}}"></span>
                            </span>
                          </p>
                          @push('scripts')
                            <script>
                              if (true) {
                                let fileTypesDocument = ['doc', 'docx', 'xml', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'pdf'];
                                let tamanhoMaximo = 10000000; // 10 MB
                            
                                let dtDocument = new DataTransfer();
                                let documentInput = document.getElementById(`topicos-${'{{$loop->parent->index}}'}-documentos-${'{{$loop->index}}'}-arquivos`);
                                let documentsArea = document.getElementById(`${'{{$loop->parent->index}}'}-${'{{$loop->index}}'}-area`);
                                let documentInvalido = document.getElementById(`${'{{$loop->parent->index}}'}-${'{{$loop->index}}'}-invalido`);
                                let erroDocument = document.getElementById(`erro-${'{{$loop->parent->index}}'}-${'{{$loop->index}}'}`);
                                let documentVermelho = document.getElementById(`${'{{$loop->parent->index}}'}-${'{{$loop->index}}'}-vermelho`);
                                let erroDocumentGrande = document.getElementById(`erro-${'{{$loop->parent->index}}'}-${'{{$loop->index}}'}-grande`);
                                let documentGrandeSpan = document.getElementById(`${'{{$loop->parent->index}}'}-${'{{$loop->index}}'}-grande`);
                            
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
                                      if (!fileTypesDocument.some(el => this.files[i].type.includes(el))) {
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
                                      if (fileTypesDocument.some(el => file.type.includes(el))) {
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
                              }
                            </script>
                          @endpush
                        @elseif($resposta->pergunta->tipo === "video")
                        @foreach($resposta->arquivos as $arquivo)
                          <div class="col-md-6 col-sm-6 col-xs-12" style="display: flex; align-items: center;">
                            <input type="checkbox" value="{{$arquivo->id}}" name="{{'topicos['.$loop->parent->parent->index.'][videos]['.$loop->parent->index.'][remove_arquivo][]'}}" >
                            <label class="form-check-label" style="margin: 0;">{{$arquivo->nome_origem}}</label>
                          </div>
                        @endforeach
                        <p class="mb-2 col-xs-12">
                          <label for="{{'topicos-'.$loop->parent->index.'-videos-'.$loop->index.'-arquivos'}}">
                            <a class="btn btn-primary text-light" type="button" role="button" aria-disabled="false">Adicionar Arquivo</a>
                          </label>
                          <input
                            id="{{'topicos-'.$loop->parent->index.'-videos-'.$loop->index.'-arquivos'}}"
                            type="file"
                            class="form-control"
                            multiple="multiple"
                            name="{{'topicos['.$loop->parent->index.'][videos]['.$loop->index.'][arquivos][]'}}"
                            style="visibility: hidden; position: absolute;"
                            accept="video/mp4,video/x-m4v,video/*"
                          >
                          <input type="hidden" name="{{'topicos['.$loop->parent->index.'][videos]['.$loop->index.'][pergunta_id]'}}" value="{{$resposta->pergunta->id}}">
                          <input type="hidden" name="{{'topicos['.$loop->parent->index.'][videos]['.$loop->index.'][resposta_id]'}}" value="{{$resposta->id}}">
                        </p>
                        <div id="{{'erro-'.$loop->parent->index.'-'.$loop->index}}" class="alert alert-danger mb-2" style="display: none;">
                          <p class="m-0">Tipo de arquivo inválido, insira apenas videos: <span id="{{$loop->parent->index.'-'.$loop->index.'-invalido'}}"></span></p>
                        </div>
                        <div id="{{'erro-'.$loop->parent->index.'-'.$loop->index.'-grande'}}" class="alert alert-danger mb-2" style="display: none;">
                          <p class="m-0">Arquivo ultrapassa o limite de tamanho permitido: <span id="{{$loop->parent->index.'-'.$loop->index.'-grande'}}"></span></p>
                        </div>
                        <p id="{{$loop->parent->index.'-'.$loop->index.'-vermelho'}}" style="font-size: 13px; color: red; display: none;" class="mb-2">* Atenção: Os arquivos destacados em vermelho não serão enviados.</p>
                        <p id="{{$loop->parent->index.'-'.$loop->index.'-area'}}">
                          <span id="{{$loop->parent->index.'-'.$loop->index.'-list'}}">
                            <span id="{{$loop->parent->index.'-'.$loop->index.'-names'}}"></span>
                          </span>
                        </p>
                        @push('scripts')
                          <script>
                            if (true) {
                              let fileTypesVideo = ['video/mp4', 'video/x-m4v', 'video/*'];
                              let tamanhoMaximo = 10000000; // 10 MB
                          
                              let dtVideo = new DataTransfer();
                              let videoInput = document.getElementById(`topicos-${'{{$loop->parent->index}}'}-videos-${'{{$loop->index}}'}-arquivos`);
                              let videosArea = document.getElementById(`${'{{$loop->parent->index}}'}-${'{{$loop->index}}'}-area`);
                              let videoInvalido = document.getElementById(`${'{{$loop->parent->index}}'}-${'{{$loop->index}}'}-invalido`);
                              let erroVideo = document.getElementById(`erro-${'{{$loop->parent->index}}'}-${'{{$loop->index}}'}`);
                              let videoVermelho = document.getElementById(`${'{{$loop->parent->index}}'}-${'{{$loop->index}}'}-vermelho`);
                              let erroVideoGrande = document.getElementById(`erro-${'{{$loop->parent->index}}'}-${'{{$loop->index}}'}-grande`);
                              let videoGrandeSpan = document.getElementById(`${'{{$loop->parent->index}}'}-${'{{$loop->index}}'}-grande`);
                          
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
                                    if (!fileTypesVideo.some(el => this.files[i].type.includes(el))) {
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
                                    erroVideo.style.display = 'none';
                                    verifyVideos = false;
                                }
                          
                                // Checa a existência de videos com tamanho maior que o permitido.
                                if (videosGrandes.length === 0) {
                                    // Caso todos os arquivos sejam válidos, esconde a mensagem de erro e atribui false para presença de videos inválidos.
                                    erroVideoGrande.style.display = 'none';
                                    videoGrande = false;
                                }
                          
                                // Guarda os arquivos no objeto de DataTransfer.
                                for (let file of this.files) {
                                    // Checa validez do tipo de arquivo antes de inserir.
                                    if (fileTypesVideo.some(el => file.type.includes(el))) {
                                        if (file.size < tamanhoMaximo) {
                                            dtVideo.items.add(file);
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
                                    erroVideo.style.display = 'block';
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
                                    erroVideoGrande.style.display = 'block';
                                    this.value = '';
                                }
                          
                                // Atualiza os arquivos do input.
                                videoInput.files = dtVideo.files;
                                // Atribui evento no botão de deletar arquivo.
                                let deleteButtons = document.querySelectorAll('.file-delete');
                                for (let button of deleteButtons) {
                                    button.addEventListener('click', function (e) {
                                        let name = this.nextElementSibling.innerHTML;
                                        // Remove o nome do arquivo da página.
                                        this.parentElement.remove();
                                        
                                        for(let i = 0; i < dtVideo.items.length; i++) {
                                            if (name === dtVideo.items[i].getAsFile().name) {
                                                // Delete file on DataTransfer Object.
                                                dtVideo.items.remove(i);
                                                continue;
                                            }
                                        }
                                        videoInput.files = dtVideo.files;
                                    });
                                }
                              });
                            }
                          </script>
                        @endpush
                      @endif
                    </div>
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