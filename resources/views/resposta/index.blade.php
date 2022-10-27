@extends('gentelella.layouts.app')

@section('content')

<div class="x_panel modal-content">
    <div class="x_title">
      <h2>Relatórios</h2>
      @if (Auth::user()->nivel === "User")
      <ul class="nav navbar-right panel_toolbox">
        <a href="{{route('resposta.create')}}" class="btn-circulo btn  btn-success btn-md  pull-right " data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Novo Funcionario"> Novo Relatório </a>
      </ul>
      @endif
      <div class="clearfix"></div>
    </div>
    <div class="x_panel">
      <div class="x_content">
        <div class="form-group row">
          <div class="form-group col-md-6 col-sm-6 col-xs-12">
             <label class="control-label">Setor</label>
              <select onchange="getUnidades()" id="setor_id" name="setor_id" class="form-control" required>
                  <option value="" selected>Selecione o setor</option>
                  @foreach ($setores_usuario_logado as $setor)
                    <option value="{{$setor->id}}">{{$setor->nome}}</option>
                  @endforeach
              </select>
          </div>
          <div class="form-group col-md-6 col-sm-6 col-xs-12">
             <label class="control-label">Unidade</label>
              <select onchange="getTabela()" id="unidade_id" name="unidade_id" class="form-control" minlength="2" required disabled>
                  <option value="" selected>Selecione o setor para carregar as opções</option>
                  {{-- API --}}
              </select>
          </div>
        </div>
      </div>
    </div>
    <div class="x_panel">
       <div class="x_content">
         <table id="tb_resposta" class="table table-hover table-striped compact" style="width: 100%;">
            <thead>
               <tr>
                  <th class="text-center">Data</th>
                  <th class="text-center">Unidade</th>
                  <th class="text-center">Marcador</th>
                  <th class="text-center">Preenchido por</th>
                  <th class="text-center">Ações</th>
               </tr>
            </thead>   
            <tbody id="tb_resposta_body">
              <tr>
                <td colspan="5" style="text-align: center">Selecione a unidade</td>
              </tr>
            </tbody>
          </table>
       </div>
    </div>
 </div>

@endsection

@push('scripts')

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script>
    const userID = "{{ Auth::user()->id }}";
    const setorSelect = document.getElementById('setor_id');
    const unidadeSelect = document.getElementById('unidade_id');
    const tBody = document.getElementById('tb_resposta_body');

    const getUnidades = async () => {
      if(setorSelect.value) {
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
    }

    const getTabela = async () => {
      if(unidadeSelect.value) {
        setorSelect.setAttribute('disabled', 'disabled');
        unidadeSelect.setAttribute('disabled', 'disabled');

        const loadingTR = document.createElement('tr');
        const loadingTD = document.createElement('td');
        loadingTD.innerText = "Carregando...";
        loadingTD.setAttribute('colspan', '100%');
        loadingTR.append(loadingTD);
        loadingTR.classList.add('text-center');
        tBody.innerHTML = "";
        tBody.append(loadingTR);

        try {
          const response = await axios.get('/api/tabela', {
            params: {
              unidade_id: unidadeSelect.value,
              user_id: userID,
            }
          });

          const tabela = response.data.tabela;
          const user = response.data.usuario;
          tBody.innerHTML = "";
          
          if (response.data.length === 0) {
            const notFoundTR = document.createElement('tr');
            const notFoundTD = document.createElement('td');
            notFoundTD.setAttribute('colspan', '100%');
            notFoundTD.innerText = "Nenhum registro encontrado";
            notFoundTR.append(notFoundTD);
            notFoundTR.classList.add('text-center');
            tBody.append(notFoundTR);
          } else {
            console.log(tabela, user)
            //TODO: filtrar ações disponíveis por usuário e status do form
            for (let item of tabela) {
              const tr = document.createElement('tr');

              const data = document.createElement('td');
              data.innerText = new Intl.DateTimeFormat('en-GB',{day:'2-digit',month:'2-digit', year:'numeric'}).format(new Date(item.data));

              const unidade = document.createElement('td');
              unidade.innerText = item.unidade.nome;

              const marcador = document.createElement('td');
              marcador.innerText = item.marcador ? item.marcador.nome : '';

              const usuario = document.createElement('td');
              usuario.innerText = item.criador.name;

              const acoesTd = document.createElement('td');
              const acoes = document.createElement('div');
              acoes.style.display = "flex";
              acoesTd.append(acoes);

              if (item.status === 1 || user.nivel === "User") {
                const viewBtn = document.createElement('a');
                viewBtn.classList.add('btn', 'btn-info', 'btn-xs');
                viewBtn.innerHTML = "<i class='glyphicon glyphicon-list-alt'></i>";
                viewBtn.href = `/resposta/${item.unidade_id}?data=${item.data}`;
                viewBtn.title = "Visualizar relatório";
                acoes.append(viewBtn);

              } else if (item.status === 0 && user.nivel !== "User") {
                const aguardando = document.createElement('span');
                aguardando.innerText = "Aguardando Envio";
                aguardando.style.color = 'grey';
                acoes.append(aguardando);
              }

              if (item.status === 1 && user.nivel !== "User") {
                const exportarForm = document.createElement('form');
                exportarForm.style.display = "inline";
                exportarForm.method = "POST";
                exportarForm.action = "{{route('resposta.pdf')}}";
                exportarForm.target = "_blank";

                const hidden = document.createElement('input');
                hidden.type  = "hidden";
                hidden.name  = "_token";
                hidden.value = "{{ csrf_token() }}";

                const hidden1 = document.createElement('input');
                hidden1.type  = "hidden";
                hidden1.name  = "unidade_id";
                hidden1.value = item.unidade_id;
                
                const hidden2 = document.createElement('input');
                hidden2.type  = "hidden";
                hidden2.name  = "data";
                hidden2.value = item.data;

                const exportarBtn = document.createElement('button');
                exportarBtn.classList.add('btn', 'btn-xs', 'btn-primary');
                exportarBtn.innerHTML = "<i class='glyphicon glyphicon-file'></i>";
                exportarBtn.title = "Exportar formulário em PDF."

                exportarForm.append(hidden, hidden1, hidden2, exportarBtn);
                acoes.append(exportarForm);
              }

              if (item.status === 1 && user.nivel === "Super-Admin") {
                const devolverForm = document.createElement('form');
                devolverForm.style.display = "inline";
                devolverForm.method = 'POST';
                devolverForm.action = "{{route('resposta.enviar')}}";
                
                const hidden = document.createElement('input');
                hidden.type  = "hidden";
                hidden.name  = "_token";
                hidden.value = "{{ csrf_token() }}";

                const hidden1 = document.createElement('input');
                hidden1.type  = "hidden";
                hidden1.name  = "unidade_id";
                hidden1.value = item.unidade_id;
                
                const hidden2 = document.createElement('input');
                hidden2.type  = "hidden";
                hidden2.name  = "data";
                hidden2.value = item.data;

                const hidden3 = document.createElement('input');
                hidden3.type  = "hidden";
                hidden3.name  = "envio_status";
                hidden3.value = 0;

                const devolverBtn = document.createElement('button');
                devolverBtn.classList.add('btn', 'btn-xs', 'btn-warning');
                devolverBtn.innerHTML = "<i class='glyphicon glyphicon-send'></i>";
                devolverBtn.title = "Devolver formulário";
                
                devolverForm.addEventListener('submit', evt => {
                  evt.preventDefault();
                  swal({
                    title: "Atenção!",
                    text: "Você está prestes a devolver o formulário para a fase preenchimento.",
                    icon: "warning",
                    buttons: {
                        cancel: {
                          text: "Cancelar",
                          value: "cancelar",
                          visible: true,
                          closeModal: true,
                        },
                        ok: {
                          text: "Confirmar",
                          value: 'enviar',
                          visible: true,
                          closeModal: true,
                        }
                    }
                  }).then(function(resultado) {
                    if (resultado === 'enviar') {
                      evt.target.submit();
                    }
                  });
                });

                devolverForm.append(hidden, hidden1, hidden2, hidden3, devolverBtn);
                acoes.append(devolverForm);
              }

              if (item.status === 0 && user.nivel === "User") {
                const editBtn = document.createElement('a');
                editBtn.classList.add('btn', 'btn-warning', 'btn-xs');
                editBtn.innerHTML = "<i class='glyphicon glyphicon-pencil'></i>";
                editBtn.href = `/resposta/${item.unidade_id}/edit?data=${item.data}`;
                editBtn.title = "Editar formulário";
                acoes.append(editBtn);

                const envioForm = document.createElement('form');
                envioForm.style.display = "inline";
                envioForm.method = 'POST';
                envioForm.action = "{{route('resposta.enviar')}}";
                
                const hidden = document.createElement('input');
                hidden.type  = "hidden";
                hidden.name  = "_token";
                hidden.value = "{{ csrf_token() }}";

                const hidden1 = document.createElement('input');
                hidden1.type  = "hidden";
                hidden1.name  = "unidade_id";
                hidden1.value = item.unidade_id;
                
                const hidden2 = document.createElement('input');
                hidden2.type  = "hidden";
                hidden2.name  = "data";
                hidden2.value = item.data;

                const hidden3 = document.createElement('input');
                hidden3.type  = "hidden";
                hidden3.name  = "envio_status";
                hidden3.value = 1;

                const envioBtn = document.createElement('button');
                envioBtn.classList.add('btn', 'btn-xs', 'btn-success');
                envioBtn.innerHTML = "<i class='glyphicon glyphicon-send'></i>";
                envioBtn.title = "Enviar formulário"
                
                envioForm.addEventListener('submit', evt => {
                  evt.preventDefault();
                  swal({
                    title: "Atenção!",
                    text: "Você está prestes a enviar o formulário (não é possível fazer alterações após o envio).",
                    icon: "warning",
                    buttons: {
                        cancel: {
                          text: "Cancelar",
                          value: "cancelar",
                          visible: true,
                          closeModal: true,
                        },
                        ok: {
                          text: "Confirmar",
                          value: 'enviar',
                          visible: true,
                          closeModal: true,
                        }
                    }
                  }).then(function(resultado) {
                    if (resultado === 'enviar') {
                      evt.target.submit();
                    }
                  });
                });

                envioForm.append(hidden, hidden1, hidden2, hidden3, envioBtn);
                acoes.append(envioForm);
              } else if (item.status === 1 && user.nivel === "User") {
                const enviado = document.createElement('span');
                enviado.innerText = "Enviado";
                enviado.style.color = "green";
                acoes.append(enviado);
              }

              tr.append(data, unidade, marcador, usuario, acoesTd);
              tBody.append(tr);
            }
          }

          jQuery.extend( jQuery.fn.dataTableExt.oSort, {
            "date-euro-pre": function ( a ) {
                var x;
        
                if ( a.trim() !== '' ) {
                    var frDatea = a.trim().split(' ');
                    var frTimea = (undefined != frDatea[1]) ? frDatea[1].split(':') : [00,00,00];
                    var frDatea2 = frDatea[0].split('/');
                    x = (frDatea2[2] + frDatea2[1] + frDatea2[0] + frTimea[0] + frTimea[1] + ((undefined != frTimea[2]) ? frTimea[2] : 0)) * 1;
                }
                else {
                    x = Infinity;
                }
        
                return x;
            },
        
            "date-euro-asc": function ( a, b ) {
                return a - b;
            },
        
            "date-euro-desc": function ( a, b ) {
                return b - a;
            }
        } );

          $(document).ready(function(){
            $.fn.dataTable.moment('DD/MM/YYYY');
            var tb_resposta = $("#tb_resposta").DataTable({
              language: {
                    'url' : '{{ asset('js/portugues.json') }}',
              "decimal": ",",
              "thousands": "."
              },
              "columnDefs": [
                { "type": 'date-euro', "targets": 0}
              ],
              stateSave: true,
              stateDuration: -1,
              responsive: true,
              order: [[0, 'desc']],
              retrieve: true,
            })
        });
          
          unidadeSelect.removeAttribute('disabled');
          setorSelect.removeAttribute('disabled');

        } catch (error) {
          console.log(error);
          funcoes.notificationRight("top", "right", "danger", "Ocorreu um erro.");
          unidadeSelect.removeAttribute('disabled');
          setorSelect.removeAttribute('disabled');
        }
      }
    }
  </script>
  @if (session()->has('success'))
    <script>
      funcoes.notificationRight("top", "right", "success", "{{ session()->get("success") }}");
    </script>
  @endif
@endpush