ctrConsulta.numRegistros = 40;
ctrConsulta.ordenacao    = ['id', 'desc'];

var pagarSelecionada = 0;

function inicia_processo(){
    listaContas();
}

function processaPagina(){
    listaContas();
}

function listaContas(){

    $.ajax({         
        url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
        dataType: 'json',
        type: 'POST',
        data: {oper: 26, ctrConsulta: ctrConsulta},
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

function montaTabela(dados, numTotalReg){

    let textoTabela = "Nenhum registro localizado...";

    if (dados.length > 0){

        textoTabela = `
            <table class="table">
                <thead style="background-color: #34495e; color: white;">
                    <tr>
                        <td class="ordenacao" tipord="C" tipo="id">Id</td>
                        <td class="ordenacao" tipord="C" tipo="fantasia">Favorecido</td>
                        <td class="ordenacao" tipord="C" tipo="despesa">Despesa</td>
                        <td class="ordenacao" tipord="C" tipo="valor">Valor</td>
                        <td class="ordenacao" tipord="C" tipo="valor_pago">Valor Pago</td>
                        <td class="ordenacao" tipord="C" tipo="data_vencto">Data Vencto</td>
                        <td class="ordenacao" tipord="C" tipo="data_pagto">Data Pagto</td>
                        <td class="ordenacao" tipord="C" tipo="numdoc">Num.Doc.</td>
                        <td class="ordenacao" tipord="C" tipo="situacao">Situação</td>
                    </tr>
                </thead>
                <tbody>
        `;

        dados.forEach(elemento => {
        
            let vSit = '';

            switch (elemento.pagarSituacao){
                case 'X':
                    vSit = 'Cancelado';
                break;
                case 'P':
                    vSit = 'Pago';
                break;
                default:
                    vSit = 'Aberto';
            }
            
            textoTabela += `
                <tr conta="${elemento.pagarId}" class="registroTab">
                    <td>${elemento.pagarId}</td>
                    <td>${elemento.pessoa}</td>
                    <td>${elemento.despesa}</td>
                    <td>${parseFloat(elemento.pagarValor).toFixed(2).replace('.',',')}</td>
                    <td>${parseFloat(elemento.pagarValorPago).toFixed(2).replace('.',',')}</td>
                    <td>${elemento.pagarDtVencto ? elemento.pagarDtVencto.split('-').reverse().join('/') : ''}</td>
                    <td>${elemento.pagarDtPagto ? elemento.pagarDtPagto.split('-').reverse().join('/'): ''}</td>
                    <td>${elemento.pagarNumDoc}</td>
                    <td>${vSit}</td>
                </tr>
            `;

        });

        textoTabela += '</tbody></table>';

    }

    montaPaginacao(numTotalReg);

    let vFiltros = [
        {campo: 'id',          descricao: 'Id',          tipo: 'int'},
        {campo: 'fantasia',    descricao: 'Favorecido',  tipo: 'str'},
        {campo: 'despesa',     descricao: 'Despesa',     tipo: 'str'},
        {campo: 'valor',       descricao: 'Valor',       tipo: 'num'},
        {campo: 'valor_pago',  descricao: 'Valor pago',  tipo: 'num'},
        {campo: 'data_vencto', descricao: 'Data Vencto', tipo: 'dat'},
        {campo: 'data_pagto',  descricao: 'Data Pagto',  tipo: 'dat'},
        {campo: 'situacao',    descricao: 'Situação',    tipo: 'str'},
        {campo: 'numdoc',      descricao: 'Num.Docto',   tipo: 'str'}
    ];

    criarFiltro(vFiltros);

    $("div.montaTabela").html(textoTabela);

    alteraOrdenacao();
    
}

function buscaDespesas(){

    let vTmpCtrConsulta = {
        paginaAtual:  1,
        ordenacao:    ['nome', 'asc'],
        filtros:      [
            {campo: 'situacao', tipo: 'igual', descricao: 'Situação', valor: 'A'}
        ],
        numRegistros: 9999
    };

    $.ajax({         
        url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
        dataType: 'json',
        type: 'POST',
        data: {oper: 9, ctrConsulta: vTmpCtrConsulta},
        beforeSend: function(){
            $.blockUI({ message: '<p>Aguarde...</p>' });
        },
        success: function(dados){

            $.unblockUI();
            
            if (dados[0] == 'Ok'){

                let textoDesp = '<option value="0">Selecionar</option>';
                
                dados[1].forEach(desp => {
                    textoDesp += `<option value="${desp.despId}">${desp.despNome}</option>`;
                });

                $('#despesa').html(textoDesp);

            }
            
        },
        error: function(dadosErro){
            $.unblockUI();
            console.log(dadosErro);
        }
    });

}

function buscaFavorecido(){

    let vTmpCtrConsulta = {
        paginaAtual:  1,
        ordenacao:    ['fantasia', 'asc'],
        filtros:      [
            {campo: 'situacao', tipo: 'igual', descricao: 'Situação', valor: 'A'}
        ],
        numRegistros: 9999
    };

    $.ajax({         
        url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
        dataType: 'json',
        type: 'POST',
        data: {oper: 32, ctrConsulta: vTmpCtrConsulta},
        beforeSend: function(){
            $.blockUI({ message: '<p>Aguarde...</p>' });
        },
        success: function(dados){

            $.unblockUI();
            
            if (dados[0] == 'Ok'){

                let textoFun = '<option disabled style="background-color: #D3D3D3;">Funcionários</option>';
                let textoFor = '<option disabled style="background-color: #D3D3D3;">Fornecedores</option>';
                let textoPsv = '<option disabled style="background-color: #D3D3D3;">Prest. Serviço</option>';
                
                dados[1].forEach(pessoa => {

                    if (pessoa.pessoaFor == 'S'){
                        textoFor += `<option value="${pessoa.pessoaId}">${pessoa.pessoaFantasia}</option>`;
                    } else if (pessoa.pessoaFun == 'S'){
                        textoFun += `<option value="${pessoa.pessoaId}">${pessoa.pessoaFantasia}</option>`;
                    } else if (pessoa.pessoaPsv == 'S'){
                        textoPsv += `<option value="${pessoa.pessoaId}">${pessoa.pessoaFantasia}</option>`;
                    }
                    
                });

                let textoFinal = `
                    <option value="0">Selecionar</option>
                    ${textoFun}
                    ${textoFor}
                    ${textoPsv}
                `;

                $('#pessoa').html(textoFinal);

            }
            
        },
        error: function(dadosErro){
            $.unblockUI();
            console.log(dadosErro);
        }
    });

}

buscaDespesas();
buscaFavorecido();

function incluirPrestacao(){

    let sequencia = 1;

    $(".tabelaPrestacoes .tabelaPrestacoesBody tr.linhaParcela").each(function(){
        sequencia++;
    });

    let txtPrest = `
        <tr prest="${sequencia}" class="linhaParcela" id="linhaParcela${sequencia}">
            <td>${sequencia}</td>
            <td><input type="date" class="form-control form-control-sm dataPrest" style="max-width: 150px;"/></td>
            <td><input type="text" class="form-control form-control-sm valPrest maskdinheiro" value="0,00" style="max-width: 120px;"/></td>
            <td>
                <select class="form-control form-control-sm pagarSit" style="width: 120px;">
                    <option value="A">A - Aberta</option>
                    <option value="P">P - Pago</option>
                    <option value="X">X - Cancelado</option>
                </select>
            </td>
            <td><input type="date" class="form-control form-control-sm dataPagto" style="max-width: 150px;"/></td>
            <td><input type="text" class="form-control form-control-sm valPagto maskdinheiro" value="0,00" style="max-width: 120px;"/></td>
        </tr>
    `;

    $(".tabelaPrestacoes .tabelaPrestacoesBody").append(txtPrest);

}

function removerPrestacao(){

    let sequencia = 0;

    $(".tabelaPrestacoes .tabelaPrestacoesBody tr.linhaParcela").each(function(){
        sequencia++;
    });

    $(".tabelaPrestacoes .tabelaPrestacoesBody #linhaParcela"+sequencia).remove();

}

function limparForm(){

    $(".someInclusao").css('display', 'none');
    $(".someAlteracao").css('display', '');

    $("#pessoa").val('0');
    $("#despesa").val('0');
    $("#pagarId").html(0);
    $("#pagarSituacao").val('A');
    $("#pagarNumDoc").val('');
    $("#pagarDtDoctoInc").val('');
    $("#pagarObs").val('');

    $(".tabelaPrestacoes .tabelaPrestacoesBody").html('');

    incluirPrestacao();

}

function preencherDadosPagar(pagar){

    $(".someAlteracao").css('display', 'none');
    $(".someInclusao").css('display', '');

    $("#pessoa").val(pagar.pessoa);
    $("#despesa").val(pagar.despesa);
    $("#pagarValor").val(parseFloat(pagar.pagarValor).toFixed(2).replace('.',','));
    $("#pagarValorPago").val(parseFloat(pagar.pagarValorPago).toFixed(2).replace('.',','));
    $("#pagarSituacao").val(pagar.pagarSituacao);
    $("#pagarNumDoc").val(pagar.pagarNumDoc);
    $("#pagarId").html(pagar.pagarId);
    $("#pagarObs").val(pagar.pagarObs);
    $("#pagarSeq").html(pagar.pagarSeq);
    $("#pagarDtVencto").val((pagar.pagarDtVencto !== null ? pagar.pagarDtVencto.substr(0,10) : ''));
    $("#pagarDtPagto").val((pagar.pagarDtPagto !== null ? pagar.pagarDtPagto.substr(0,10) : ''));
    $("#pagarDtDocto").val((pagar.pagarDtDocto !== null ? pagar.pagarDtDocto.substr(0,10) : ''));
    $("#dataInclusao").val((pagar.dtaInc !== null ? pagar.dtaInc.substr(0,10) : ''));
    $("#dataAlteracao").val((pagar.dtaAlt !== null ? pagar.dtaAlt.substr(0,10) : ''));
    $("#usuarioInclusao").html(pagar.usrInc);
    $("#usuarioAlteracao").html(pagar.usrAlt);

}

function consultaConta(){

    if (pagarSelecionada.length <= 0 || pagarSelecionada <= 0){
        modalOk(function(){}, 'Id da conta não identificada!');
    } else {

        $.ajax({         
            url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
            dataType: 'json',
            type: 'POST',
            data: {oper: 27, id: pagarSelecionada},
            beforeSend: function(){
                $.blockUI({ message: '<p>Aguarde...</p>' });
            },
            success: function(dados){
    
                $.unblockUI();
                
                if (dados[0] == 'Ok'){

                    preencherDadosPagar(dados[1]);
                    $('.modalEdicao').modal('show');
                    
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

}

function novoCad(){

    pagarSelecionada = 0;
    limparForm();

    $('.modalEdicao').modal('show');

}

function alterarConta(){

    let dados = {
        oper:      33,
        pessoa:    $("#pessoa option:selected").val(),
        despesa:   $("#despesa option:selected").val(),
        situacao:  $("#pagarSituacao option:selected").val(),
        vencto:    $("#pagarDtVencto").val(),
        docto:     $("#pagarDtDocto").val(),
        pagto:     $("#pagarDtPagto").val(),
        valor:     $("#pagarValor").val().replace(',','.'),
        valorpago: $("#pagarValorPago").val().replace(',','.'),
        numdoc:    $("#pagarNumDoc").val(),
        obs:       $("#pagarObs").val(),
        id:        pagarSelecionada
    }

    $.ajax({         
        url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
        dataType: 'json',
        type: 'POST',
        data: dados,
        beforeSend: function(){
            $.blockUI({ message: '<p>Aguarde...</p>' });
        },
        success: function(dados){

            $.unblockUI();
            
            if (dados[0] == 'Ok'){
                pagarSelecionada = dados[2];
                toastMensagem(dados[1], 'Despesa', '#30eb73');
                consultaConta();
                listaContas();
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

function retornarParcelas(){

    let arrayContas = [];

    $(".tabelaPrestacoes .tabelaPrestacoesBody tr.linhaParcela").each(function(){
        
        let vValorPago = $(this).find('.valPagto').val().replace(',','.');
        let vValor     = $(this).find('.valPrest').val().replace(',','.');
        let vPagto     = $(this).find('.dataPagto').val();
        let vVencto    = $(this).find('.dataPrest').val();
        let vSituacao  = $(this).find('.pagarSit option:selected').val();

        arrayContas.push(
            {
                valorpago: vValorPago,
                valor:     vValor,
                pagto:     vPagto,
                vencto:    vVencto,
                situacao:  vSituacao
            }
        );

    });

    return arrayContas;

}

function incluirConta(){

    let arrayContas = retornarParcelas();

    let dados = {
        oper:        34,
        pessoa:      $("#pessoa option:selected").val(),
        despesa:     $("#despesa option:selected").val(),
        docto:       $("#pagarDtDoctoInc").val(),
        numdoc:      $("#pagarNumDoc").val(),
        obs:         $("#pagarObs").val(),
        id:          pagarSelecionada,
        arrayContas: arrayContas
    };

    $.ajax({         
        url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
        dataType: 'json',
        type: 'POST',
        data: dados,
        beforeSend: function(){
            $.blockUI({ message: '<p>Aguarde...</p>' });
        },
        success: function(dados){

            $.unblockUI();
            
            if (dados[0] == 'Ok'){
                pagarSelecionada = dados[2];
                toastMensagem(dados[1], 'Despesa', '#30eb73');
                consultaConta();
                listaContas();
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

function salvarConta(){

    if (pagarSelecionada > 0){
        alterarConta();
    } else {
        incluirConta();
    }

}

$(document).ready(function(){

    $("#tituloAplicacao").html('Contas a Pagar ' + $("#tituloAplicacao").html());

    $('.montaTabela').on('click', 'tr.registroTab', function(){
        pagarSelecionada = $(this).attr('conta');
        consultaConta();
    });

    $('.row').on('click', 'button.novoCadastro', function(){
        novoCad();
    });

    $(".modalEdicao").on("click", "button#salvarConta", function(){
        salvarConta();
    });

    $(".tabelaPrestacoes").on("click", "#incluirPrestacao", function(){
        incluirPrestacao();
    });

    $(".tabelaPrestacoes").on("click", "#removerPrestacao", function(){
        removerPrestacao();
    });

});