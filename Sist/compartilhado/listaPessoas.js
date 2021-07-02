ctrConsulta.numRegistros = 20;
ctrConsulta.ordenacao    = ['id', 'asc'];

function inicia_processo(){
    listaPessoa();
}

function processaPagina(){
    listaPessoa();
}

function listaPessoa(){

    $.ajax({         
        url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
        dataType: 'json',
        type: 'POST',
        data: {oper: 12, ctrConsulta: ctrConsulta, tipo: tipoFormulario},
        beforeSend: function(){
            $.blockUI({ message: '<p>Aguarde...</p>' });
        },
        success: function(dados){

            $.unblockUI();
            
            if (dados[0] == 'Ok'){
                montaTabela(dados[1], dados[2]);
            } else {
                modalOk(function(){}, dados[1]);
            }
            
        },
        error: function(dadosErro){
            $.unblockUI();
            console.log(dadosErro);
        }
    });

}

function retornaTextoTel(valor){

    let retorno = valor;

    if (valor.length === 8){
        retorno = valor.substring(0, 4) + '-' + valor.substring(4, 4);
    } else if (valor.length === 9){
        retorno = valor.substring(0, 5) + '-' + valor.substring(5, 4);
    } else if (valor.length === 10){
        retorno = '(' + valor.substr(0,2) + ')' + valor.substr(2,4) + '-' + valor.substr(6,4)
    } else if (valor.length === 11){
        retorno = '(' + valor.substr(0,2) + ')' + valor.substr(2,5) + '-' + valor.substr(7,4);
    }

    return retorno;

}

function retornaTextoCpf(valor){

    let retorno = valor;

    if (valor.length === 11){
        retorno = valor.substr(0,3) + '.' + valor.substr(3,3) + '.' + valor.substr(6,3) + '-' + valor.substr(9,2);
    } else if (valor.length === 14){ 
        retorno = valor.substr(0,2) + '.' + valor.substr(2,3) + '.' + valor.substr(5,3) + '/' + valor.substr(8,4) + '-' + valor.substr(12,2);
    }
    
    return retorno;
}

function montaTabela(dados, numTotalReg){

    let textoTabela = "Nenhum registro localizado...";

    if (dados.length > 0){

        textoTabela = `
            <table class="table">
                <thead style="background-color: #34495e; color: white;">
                    <tr>
                        <td class="ordenacao" tipord="C" tipo="id">Id</td>
                        <td class="ordenacao" tipord="C" tipo="fantasia">Nome</td>
                        <td class="ordenacao" tipord="C" tipo="telefone">Telefone</td>
                        <td class="ordenacao" tipord="C" tipo="celular">Celular</td>
                        <td class="ordenacao" tipord="C" tipo="cpfcnpj">Cpf/Cnpj</td>
                        <td class="ordenacao" tipord="C" tipo="situacao">Situação</td>
                        ${tipoFormulario == 'FUN' ? '<td class="ordenacao" tipord="C" tipo="valcred">Val. Créd.</td>' : ''}
                    </tr>
                </thead>
                <tbody>
        `;

        dados.forEach(elemento => {
            
            let textoCel = retornaTextoTel(elemento.pessoaCelular);
            let textoTel = retornaTextoTel(elemento.pessoaTelefone);
            let textpCpf = retornaTextoCpf(elemento.pessoaCpfCnpj);

            textoTabela += `
                <tr pessoa="${elemento.pessoaId}" class="registroTab">
                    <td>${elemento.pessoaId}</td>
                    <td>${elemento.pessoaFantasia}</td>
                    <td>${textoTel}</td>
                    <td>${textoCel}</td>
                    <td>${textpCpf}</td>
                    <td>${elemento.pessoaSituacao}</td>
                    ${tipoFormulario == 'FUN' ? '<td>'+parseFloat(elemento.pessoaValCredito).toFixed(2).replace('.',',')+'</td>' : ''}
                </tr>
            `;

        });

        textoTabela += '</tbody></table>';

    }

    montaPaginacao(numTotalReg);

    let vFiltros = [
        {campo: 'id',       descricao: 'Id',       tipo: 'int'},
        {campo: 'fantasia', descricao: 'Nome',     tipo: 'str'},
        {campo: 'cpfcnpj',  descricao: 'Cpf/Cnpj', tipo: 'str'},
        {campo: 'telefone', descricao: 'Telefone', tipo: 'str'},
        {campo: 'celular',  descricao: 'Celular',  tipo: 'str'},
        {campo: 'situacao', descricao: 'Situação', tipo: 'str'}
    ];

    criarFiltro(vFiltros);

    $("div.montaTabela").html(textoTabela);

    alteraOrdenacao();
    
}