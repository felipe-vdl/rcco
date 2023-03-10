@extends('gentelella.layouts.app')

@section('content')

<div class="x_panel modal-content">
    <div class="x_title">
      <h2>Relatórios Externos</h2>
      <ul class="nav navbar-right panel_toolbox">
        @if (Auth::user()->nivel === "Admin" OR Auth::user()->nivel === "Super-Admin")
          <a href="{{route('relatorio_externo.create')}}" class="btn-circulo btn  btn-success btn-md  pull-right " data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Novo Funcionario">
            Novo Relatório
          </a>
        @endif
      </ul>
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
              <select onchange="getRelatoriosExternos()" id="unidade_id" name="unidade_id" class="form-control" minlength="2" required disabled>
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
                  <th class="text-center">Nome</th>
                  <th class="text-center">Unidade</th>
                  <th class="text-center">Enviado por</th>
                  <th class="text-center">Ações</th>
               </tr>
            </thead>   
            <tbody id="tb_resposta_body">
            </tbody>
            <tfoot>
              <tr>
                  <th><input class="filter-input" data-column="0" type="text" placeholder="Filtro por Data"></th>
                  <th><input class="filter-input" data-column="1" type="text" placeholder="Filtro por Nome"></th>
                  <th><input class="filter-input" data-column="2" type="text" placeholder="Filtro por Unidade"></th>
                  <th><input class="filter-input" data-column="3" type="text" placeholder="Filtro por Autor"></th>
                  <th>{{-- <input class="filter-input" data-column="4" type="text" placeholder="Filtro por Ações"> --}}</th>
              </tr>
          </tfoot>
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
    });

    let tb_resposta;
    $(document).ready(function(){
      $.fn.dataTable.moment('DD/MM/YYYY');
      tb_resposta = $("#tb_resposta").DataTable({
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
        columns: [
          {data: 'data_correspondente', title: 'Data'},
          {data: 'nome', title: 'Nome'},
          {data: 'unidade', title: 'Unidade'},
          {data: 'criador', title: 'Enviado por'},
          {data: 'actions', title: 'Ações'},
        ],
        order: [[0, 'desc']],
        retrieve: true,
      });

      $('.filter-input').keyup(function() {
         tb_resposta.column( $(this).data('column') )
         .search( $(this).val() )
         .draw();
      });
    });

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

    const getRelatoriosExternos = async (unidade_id) => {
      if(unidadeSelect.value || unidade_id) {
        setorSelect.setAttribute('disabled', 'disabled');
        unidadeSelect.setAttribute('disabled', 'disabled');

        const loadingTR = document.createElement('tr');
        const loadingTD = document.createElement('td');
        loadingTD.innerText = "Carregando...";
        loadingTD.setAttribute('colspan', '100%');
        loadingTR.append(loadingTD);
        loadingTR.classList.add('text-center');

        try {
          const response = await axios.get('/api/relatorio_externo', {
            params: {
              unidade_id: unidadeSelect.value.length > 0 ? unidadeSelect.value : unidade_id,
              user_id: userID,
            }
          });

          const tabela = response.data.tabela;
          const user = response.data.usuario;
          
          tBody.innerHTML = "";
          const dados = tabela.map(i => {
            let actions = `<div style="display: flex;">
                <a title="Visualizar relatório" href="/storage/relatorio_externo/${i.filename}" target="_blank" class="btn btn-info btn-xs" ><i class="glyphicon glyphicon-list-alt"></i></a>
                ${(user.nivel === "Super-Admin" || user.nivel === "Admin") ? `<form class="delete-form" style="display: inline;" method="POST" action="{{route('relatorio_externo.destroy', 0)}}">
                  <input type="hidden" name="_token" value="{{csrf_token()}}">
                  <input type="hidden" name="_method" value="delete" />
                  <input type="hidden" name="relatorio_id" value="${i.id}">
                  <input type="hidden" name="unidade_id" value="${i.unidade.id}">
                  <button class="btn btn-xs btn-danger" title="Deletar relatório.">
                    <i class="glyphicon glyphicon-remove"></i>
                  </button>
                </form>` : ''}
              </div>`;

            return {
              data_correspondente: new Intl.DateTimeFormat('en-GB',{day:'2-digit',month:'2-digit', year:'numeric'}).format(new Date(i.data)),
              nome: i.nome,
              unidade: i.unidade.nome,
              criador: i.criador.name,
              actions: actions
            }
          });

          tb_resposta.clear();
          tb_resposta.rows.add(dados);
          tb_resposta.draw();

          let deleteForms = document.getElementsByClassName('delete-form');
          if (deleteForms) {
            for (let deleteForm of deleteForms) {
              deleteForm.addEventListener('submit', evt => {
                evt.preventDefault();
                swal({
                  title: "Atenção!",
                  text: "Você está prestes a deletar o relatório.",
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
            }
          }
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
  @if(request()->get("unidade_id"))
    <script>
      getRelatoriosExternos("{{request()->get('unidade_id')}}");
    </script>
  @endif
  @if (session()->has('success'))
    <script>
      funcoes.notificationRight("top", "right", "success", "{{ session()->get("success") }}");
    </script>
  @endif
@endpush