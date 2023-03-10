// Array com os tipos de arquivo aceitos.
const fileTypes = [/* 'image', 'png', 'jpg', 'jpeg', */ 'doc', 'docx', 'xml', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'pdf'];
// Tamanho máximo de cada arquivo em bytes (1 MB = 1.000.000 bytes)
const tamanhoMaximo = 10000000; // 10 MB

const dtArquivo = new DataTransfer(); // Allows you to manipulate the files of the input file
const arquivoInput = document.querySelector('#arquivo');
const arquivoArea = document.querySelector('#arquivo-area');
const arquivoInvalido = document.querySelector('#arquivo-invalido');
const erroArquivo = document.querySelector('#erro-arquivo');
const arquivoVermelho = document.querySelector('#arquivo-vermelho');
const erroArquivoGrande = document.querySelector('#erro-arquivo-grande');
const arquivoGrandeSpan = document.querySelector('#arquivo-grande');

if (arquivoInput) {
    arquivoInput.addEventListener('change', function(e) {
        // Limpa os nomes de arquivo do último input feito pelo usuário.
        let arquivoInvalidos = [];
        let verifyArquivo = null;
        arquivoInvalido.innerHTML = '';
        let arquivoGrandesArr = [];
        let verifyArquivoGrande = null;
        arquivoGrandeSpan.innerHTML = '';
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
                // Caso exista um arquivo inválido, insere nome dos arquivos inválidos na array e atribui true para a presença de atestados inválidos.
                arquivoInvalidos.push(this.files[i].name);
                fileName.classList.add('text-danger');
                fileDelete.classList.add('text-danger');
                verifyArquivo = true;
                arquivoVermelho.style.display = 'block';
            } else if (this.files[i].size > tamanhoMaximo) {
                arquivoGrandesArr.push(this.files[i].name);
                fileName.classList.add('text-danger');
                fileDelete.classList.add('text-danger');
                verifyArquivoGrande = true;
                arquivoVermelho.style.display = 'block';
            }
            fileBlock.append(fileDelete, fileName);
            arquivoArea.append(fileBlock);
        }
        // Checa a existência de atestados inválidos.
        if (arquivoInvalidos.length === 0) {
            // Caso todos os arquivos sejam válidos, esconde a mensagem de erro e atribui false para presença de atestados inválidos.
            erroArquivo.style.display = 'none';
            verifyArquivo = false;
        }
        // Checa a existência de atestados com tamanho maior que o permitido.
        if (arquivoGrandesArr.length === 0) {
            // Caso todos os arquivos sejam válidos, esconde a mensagem de erro e atribui false para presença de atestados inválidos.
            erroArquivoGrande.style.display = 'none';
            verifyArquivoGrande = false;
        }
        // Guarda os arquivos no objeto de DataTransfer.
        for (let file of this.files) {
            // Checa validez do tipo de arquivo antes de inserir.
            if (fileTypes.some(el => file.type.includes(el))) {
                if (file.size < tamanhoMaximo) {
                    dtArquivo.items.add(file);
                }
            }
        }
        // Checa o status de presença de arquivos inválidos.
        let i = 1; // Variável de controle da formatação.
        if (verifyArquivo) {
            // Caso existam arquivos inválidos, insere o nome de cada arquivo inválido no alerta de erro da view.
            for (let arquivo of arquivoInvalidos) {
                if (i < arquivoInvalidos.length) {
                    arquivoInvalido.append(`${arquivo}, `);
                } else {
                    arquivoInvalido.append(`${arquivo}.`)
                }
                i++;
            }
            erroArquivo.style.display = 'block';
            this.value = '';
        }
        // Checa o status de presença de arquivos maiores que o tamanho máximo.
        let j = 1; // Variável de controle da formatação.
        if (verifyArquivoGrande) {
            // Caso existam arquivos inválidos, insere o nome de cada arquivo inválido no alerta de erro da view.
            for (let arquivo of arquivoGrandesArr) {
                if (j < arquivoGrandesArr.length) {
                    arquivoGrandeSpan.append(`${arquivo}, `);
                } else {
                    arquivoGrandeSpan.append(`${arquivo}.`)
                }
                j++;
            }
            erroArquivoGrande.style.display = 'block';
            this.value = '';
        }
        // Atualiza os arquivos do input.
        arquivoInput.files = dtArquivo.files;
        // Atribui evento no botão de deletar arquivo.
        let deleteButtons = document.querySelectorAll('.file-delete');
        for (let button of deleteButtons) {
            button.addEventListener('click', function (e) {
                let name = this.nextElementSibling.innerHTML;
                // Remove o nome do arquivo da página.
                this.parentElement.remove();
                
                for(let i = 0; i < dtArquivo.items.length; i++) {
                    if (name === dtArquivo.items[i].getAsFile().name) {
                    // Delete file on DataTransfer Object.
                    dtArquivo.items.remove(i);
                    continue;
                    }
                }
                arquivoInput.files = dtArquivo.files;
            });
        }
    });
}