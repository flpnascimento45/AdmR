ctrConsulta.numRegistros = 20;
ctrConsulta.ordenacao    = ['id', 'desc'];

var fechtoSelecionado = 0;

var listaFuncionarios = [];

function inicia_processo(){
    listaFechtos();
}

function processaPagina(){
    listaFechtos();
}

function retornaSituacao(sit){

    let vSit = '';

    switch (sit){
        case 'F':
            vSit = 'F - Finalizado';
        break;
        case 'X':
            vSit = 'X - Cancelado';
        break;
        default:
            vSit = 'A - Aberto';
    }

    return vSit;

}

function listaFechtos(){

    $.ajax({         
        url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
        dataType: 'json',
        type: 'POST',
        data: {oper: 21, ctrConsulta: ctrConsulta},
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
                        <td class="ordenacao" tipord="C" tipo="descricao">Descrição</td>
                        <td class="ordenacao" tipord="C" tipo="data">Data</td>
                        <td class="ordenacao" tipord="C" tipo="situacao">Situação</td>
                    </tr>
                </thead>
                <tbody>
        `;

        dados.forEach(elemento => {

            let vDta = elemento.fechtoData.split('-').reverse().join('/');
            let vSit = retornaSituacao(elemento.fechtoSit);
           
            textoTabela += `
                <tr fechto="${elemento.fechtoId}" class="registroTab">
                    <td>${elemento.fechtoId}</td>
                    <td>${elemento.fechtoDescricao}</td>
                    <td>${vDta}</td>
                    <td>${vSit}</td>
                </tr>
            `;

        });

        textoTabela += '</tbody></table>';

    }

    montaPaginacao(numTotalReg);

    let vFiltros = [
        {campo: 'id',        descricao: 'Id',        tipo: 'int'},
        {campo: 'descricao', descricao: 'Descrição', tipo: 'str'},
        {campo: 'data',      descricao: 'Data',      tipo: 'dat'},
        {campo: 'situacao',  descricao: 'Situação',  tipo: 'str'}
    ];

    criarFiltro(vFiltros);

    $("div.montaTabela").html(textoTabela);

    alteraOrdenacao();
    
}

function trocaVisao(tipo){

    if (tipo === 'registro'){
        $('.visaoLista').css('display', 'none');
        $('.visaoRegistro').css('display','');
    } else {
        $('.visaoLista').css('display', '');
        $('.visaoRegistro').css('display','none');
    }

}

function preencheDados(dados){

    $('#fechtoId').html(dados.fechtoId);
    $('#fechtoDescricao').val(dados.fechtoDescricao);
    $('#fechtoData').val(dados.fechtoData);
    $('#fechtoSituacao').html(retornaSituacao(dados.fechtoSit));
    $('#usuarioInclusao').html(dados.usrInc);
    $('#usuarioAlteracao').html(dados.usrAlt);
    $('#dataInclusao').val((dados.dtaInc !== null ? dados.dtaInc.substr(0,10) : ''));
    $('#dataAlteracao').val((dados.dtaAlt !== null ? dados.dtaAlt.substr(0,10) : ''));

    $(".cabecalhoRegistro").html('');
    $(".listaFuncionarios").html('');
    $(".listaDespesas").html('');
    $(".listaPessoasDespesas").html('');
    
    if (dados.fechtoSit == 'A'){

        buscaDespesas();
        
        $(".cabecalhoRegistro").html(`
            <button type="button" class="btn btn-outline-success salvaFechto">Salvar</button>
            <button type="button" class="btn btn-outline-danger cancelaFechto">Cancelar</button>
        `);

    }

}

function criaLinhaFechtoPessoa(funcionario, comCabecalho=true){

    let txtLinha = comCabecalho ? `<tr id="linhaFunc${funcionario.fecDespId}" fecdesp="${funcionario.fecDespId}">` : '';

    let cor = 'black';

    if (funcionario.fecDespGerCredFunc > 0){
        cor = 'green';
    } else if (funcionario.fecDespGerCredFunc < 0){
        cor = 'red';
    }

    let vHid = $("#fechtoSituacao").html() == 'A - Aberto' ? '' : 'style="display: none;"';

    txtLinha += `
            <td>
            <button class="btn btn-outline-danger cancelaFuncionario" ${vHid} fecdesp="${funcionario.fecDespId}" type="button"><span class="oi oi-x"></span></button>
                ${funcionario.pessoa.fantasia}
            </td>
            <td>${parseFloat(funcionario.fecDespConsCredFunc).toFixed(2).replace('.',',')}</td>
            <td>${parseFloat(funcionario.fecDespValorFinal).toFixed(2).replace('.',',')}</td>
            <td>${(parseFloat(funcionario.fecDespConsCredFunc) + parseFloat(funcionario.fecDespValorFinal)).toFixed(2).replace('.',',')}</td>
            <td><b>${parseFloat(funcionario.fecDespValorPago).toFixed(2).replace('.',',')}</b></td>
            <td style="color: ${cor};"><b>${funcionario.fecDespGerCredFunc > 0 ? '+' : ''}${parseFloat(funcionario.fecDespGerCredFunc).toFixed(2).replace('.',',')}</b></td>
    `;

    txtLinha += comCabecalho ? '</tr>' : '';

    return txtLinha;

}

function criaTabelaFechtoPessoa(listaPessoas){

    if (listaPessoas.length > 0){

        let textoTab = `
            <table class="table">
                <thead style="background-color: #34495e; color: white;">
                    <tr style="background-color: white; color: black;">
                        <td colspan="6"><h5>Fechados</h5></td>
                    </tr>
                    <tr>
                        <td>Funcionário</td>
                        <td>Valor Créd. Func.</td>
                        <td>Valor Despesas</td>
                        <td>Valor Final</td>
                        <td>Valor Pago</td>
                        <td>Valor Gerado Func.</td>
                    </tr>
                </thead>
                <tbody>
        `;

        listaPessoas.forEach(funcionario => {
            textoTab += criaLinhaFechtoPessoa(funcionario);
        });

        let vHid = $("#fechtoSituacao").html() == 'A - Aberto' ? '' : 'style="display: none;"';

        textoTab += `
                    <tr>
                        <td colspan="6" style="text-align: center";>
                            <button type="button" ${vHid} class="btn btn-outline-success confirmarFechto">Confirmar</button>
                        </td>
                    </tr>
                </tbody>
                </table>
                `;

        $(".listaPessoasDespesas").html(textoTab);

    } else {
        $(".listaPessoasDespesas").html('');
    }

}

function listaFechtosPessoas(){

    if (fechtoSelecionado > 0){
            
        $.ajax({         
            url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
            dataType: 'json',
            type: 'POST',
            data: {oper: 29, id: fechtoSelecionado},
            beforeSend: function(){
                $.blockUI({ message: '<p>Aguarde...</p>' });
            },
            success: function(dados){
                
                $.unblockUI();
                
                if (dados[0] == 'Ok'){
                    criaTabelaFechtoPessoa(dados[1]);
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

function consultaFechto(){

    if (fechtoSelecionado.length <= 0 || fechtoSelecionado <= 0){
        modalOk(function(){}, 'Id do fechamento não identificado!');
    } else {

        $.ajax({         
            url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
            dataType: 'json',
            type: 'POST',
            data: {oper: 22, id: fechtoSelecionado},
            beforeSend: function(){
                $.blockUI({ message: '<p>Aguarde...</p>' });
            },
            success: function(dados){
    
                $.unblockUI();
                
                if (dados[0] == 'Ok'){
                    
                    preencheDados(dados[1]);
                    trocaVisao('registro');
                    listaFechtosPessoas();
                    
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

function limparForm(){

    $('#fechtoId').html('');
    $('#fechtoDescricao').val('');
    $('#fechtoData').val('');
    $('#fechtoSituacao').html(retornaSituacao('A'));
    $('#usuarioInclusao').html('');
    $('#usuarioAlteracao').html('');
    $('#dataInclusao').val('');
    $('#dataAlteracao').val('');
    
    $(".cabecalhoRegistro").html('');
    $(".listaFuncionarios").html('');
    $(".listaDespesas").html('');
    $(".listaPessoasDespesas").html('');

}

function novoCad(){

    limparForm();

    fechtoSelecionado = 0;
    
    let vDtAtual = dataAtualFormatada(1);

    $('#fechtoData').val(vDtAtual);

    $(".cabecalhoRegistro").html(`
        <button type="button" class="btn btn-outline-success salvaFechto">Salvar</button>
    `);

    trocaVisao('registro');

}

function salvaFechto(){

    let dadosChamada = {
        oper: 23, 
        id: fechtoSelecionado, 
        data: $("#fechtoData").val(),
        descricao: $("#fechtoDescricao").val()
    };

    $.ajax({         
        url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
        dataType: 'json',
        type: 'POST',
        data: dadosChamada,
        beforeSend: function(){
            $.blockUI({ message: '<p>Aguarde...</p>' });
        },
        success: function(dados){

            $.unblockUI();
            
            if (dados[0] == 'Ok'){
                fechtoSelecionado = dados[2];
                toastMensagem(dados[1], 'Fechamento', '#30eb73');
                consultaFechto();
                listaFechtos();
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

function cancelaFechto(){

    modalConfirm(function(confirm, arrParams){

        if (confirm){
            
            $.ajax({         
                url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
                dataType: 'json',
                type: 'POST',
                data: {oper: 24, id: arrParams[0]},
                beforeSend: function(){
                    $.blockUI({ message: '<p>Aguarde...</p>' });
                },
                success: function(dados){
        
                    $.unblockUI();
                    
                    if (dados[0] == 'Ok'){
                        toastMensagem(dados[1], 'Fechamento', '#30eb73');
                        consultaFechto();
                        listaFechtos();
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

    }, 'Confirma cancelamento do fechamento?', [fechtoSelecionado], 'Sim', 'Não');

}

function criaLinhaDespesa(desp){

    return `
        <div class="custom-control custom-switch ml-2">
            <input type="checkbox" class="custom-control-input checkDespesa" desp="${desp.despId}" id="switch${desp.despId}">
            <label class="custom-control-label" for="switch${desp.despId}">${desp.despNome}</label>
        </div>
    `;

}

function buscaDespesas(){

    let vTmpCtrConsulta = {
        paginaAtual:  1,
        ordenacao:    ['nome', 'asc'],
        filtros:      [
            {campo: 'situacao', tipo: 'igual', descricao: 'Situação', valor: 'A'},
            {campo: 'tipo', tipo: 'igual', descricao: 'Tipo', valor: 'FUN'}
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

                let textoDesp = '<div class="row"><h5>Buscar despesas</h5></div><div class="row">';
                
                dados[1].forEach(desp => {
                    textoDesp += criaLinhaDespesa(desp);
                });

                vTmpCtrConsulta = {
                    paginaAtual:  1,
                    ordenacao:    ['nome', 'asc'],
                    filtros:      [
                        {campo: 'situacao', tipo: 'igual', descricao: 'Situação', valor: 'A'},
                        {campo: 'tipo', tipo: 'igual', descricao: 'Tipo', valor: 'PON'}
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
                    success: function(dados2){
            
                        $.unblockUI();
                        
                        if (dados2[0] == 'Ok'){

                            dados2[1].forEach(desp => {
                                textoDesp += criaLinhaDespesa(desp);
                            });

                            textoDesp += '<button type="button" class="btn btn-sm btn-outline-info ml-3 pesqDespesasPessoa"><span class="oi oi-magnifying-glass"></span></button>'+
                            '</div>';

                            $('.listaDespesas').html(textoDesp);

                        }
            
                    },
                    error: function(dadosErro2){
                        $.unblockUI();
                        console.log(dadosErro2);
                    }
                });

            }
            
        },
        error: function(dadosErro){
            $.unblockUI();
            console.log(dadosErro);
        }
    });

}

function criaLinhaFuncionario(funcionario, comCabecalho=true){

    let txtLinha = comCabecalho ? `<tr id="linhaFunc${funcionario.pessoaId}" func="${funcionario.pessoaId}">` : '';

    txtLinha += `
            <td>
            <button class="btn btn-outline-success confirmaFuncionario" func="${funcionario.pessoaId}" type="button"><span class="oi oi-check"></span></button>
                ${funcionario.pessoaFantasia}
            </td>
            <td>
    `;

    let vlDesp = 0;

    funcionario.pessoaDespesas.forEach(despesa => {

        if (despesa['pesDesValOrig'] === undefined){
            despesa['pesDesValOrig'] = despesa.pesDesVal;
        }
        
        vlDesp += parseFloat(despesa.pesDesVal);

        txtLinha += `
            <div class="input-group input-group-sm col-12">
                <div class="input-group-prepend">
                    <!--button class="btn btn-outline-danger cancelaPessoaDespesa" type="button"><span class="oi oi-x"></span></button-->
                    <span class="input-group-text" id="inputGroup-sizing-sm">${despesa.despesa}</span>
                </div>
                <input type="text" class="form-control maskdinheiro campoValorDespesa" pesdes="${despesa.pesDesId}" func="${funcionario.pessoaId}" style="max-width: 100px;" value="${parseFloat(despesa.pesDesVal).toFixed(2).replace('.',',')}">
            </div>
        `;

    });

    let vlAPagar = parseFloat(vlDesp) + parseFloat(funcionario.pessoaValCredito);

    if (funcionario.valorPago == undefined){
        funcionario.valorPagoModif = 'N';
    }

    if (funcionario.valorPagoModif == 'N'){
        funcionario.valorPago = (vlAPagar > 0 ? vlAPagar : 0);
    }

    txtLinha += `
            </td>
            <td>${parseFloat(vlDesp).toFixed(2).replace('.',',')}</td>
            <td>${parseFloat(funcionario.pessoaValCredito).toFixed(2).replace('.',',')}</td>
            <td>${parseFloat(vlAPagar).toFixed(2).replace('.',',')}</td>
            <td>
                <input type="text" class="form-control form-control-sm maskdinheiro campoValorPago" func="${funcionario.pessoaId}" style="max-width: 150px;" value="${parseFloat(funcionario.valorPago).toFixed(2).replace('.',',')}" />
            </td>
    `;

    txtLinha += comCabecalho ? '</tr>' : '';

    return txtLinha;

}

function criaTabelaFuncionario(){

    if (listaFuncionarios.length > 0){

        let textoTab = `
            <table class="table">
                <thead style="background-color: #34495e; color: white;">
                    <tr>
                        <td>Funcionário</td>
                        <td>Despesas</td>
                        <td>Valor Despesas</td>
                        <td>Crédito Func.</td>
                        <td>Vl. a Pagar</td>
                        <td>Valor Pago</td>
                    </tr>
                </thead>
                <tbody>
        `;

        listaFuncionarios.forEach(funcionario => {
            textoTab += criaLinhaFuncionario(funcionario);
        });

        textoTab += `</tbody></table>`;

        $(".listaFuncionarios").html(textoTab);

    } else {
        $(".listaFuncionarios").html('Sem registros...');
    }

}

function listarPessoasDespesas(){

    let despesas = [];

    $('.listaDespesas .checkDespesa').each(function(){
        
        if ($(this).is(':checked')){
            despesas.push($(this).attr('desp'));
        }

    });

    if (despesas.length > 0){

        $.ajax({         
            url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
            dataType: 'json',
            type: 'POST',
            data: {oper: 25, despesas: despesas, idfechto: fechtoSelecionado},
            beforeSend: function(){
                $.blockUI({ message: '<p>Aguarde...</p>' });
            },
            success: function(dados){
    
                $.unblockUI();
                
                if (dados[0] == 'Ok'){

                    let dadosFunc = dados[1];

                    dadosFunc.sort(
                        
                        function(a, b){

                            if (a.pessoaFantasia > b.pessoaFantasia){
                                return 1;
                            } else if (b.pessoaFantasia > a.pessoaFantasia) {
                                return -1;
                            } else {
                                return 0;
                            }

                        }

                    );
                    
                    listaFuncionarios = dadosFunc;

                    criaTabelaFuncionario();    

                } else {
                    modalOk(function(){}, dados[1]);
                }
    
            },
            error: function(dadosErro){
                $.unblockUI();
                console.log(dadosErro);
            }
        });

    } else {
        modalOk(function(){}, 'Necessário selecionar despesa.');
    }

}

function alteraValorDespesa(idPessoa, idDespesa, novoValor){

    listaFuncionarios.forEach(funcionario => {
        
        if (funcionario.pessoaId == idPessoa){

            funcionario.pessoaDespesas.forEach(despesa => {
                if (despesa.pesDesId == idDespesa){
                    despesa.pesDesVal = novoValor.replace(',','.');
                }
            });

            let novoTextoTr = criaLinhaFuncionario(funcionario, false);

            $(".listaFuncionarios tr#linhaFunc"+idPessoa).html(novoTextoTr);

            $(".listaFuncionarios tr#linhaFunc"+idPessoa+' .campoValorPago').focus();

        }

    });

}

function alteraValorPago(idPessoa, novoValor){

    listaFuncionarios.forEach(funcionario => {
        
        if (funcionario.pessoaId == idPessoa){
            funcionario.valorPagoModif = 'S';
            funcionario.valorPago      = novoValor.replace(',', '.');
        }

    });

}

function confirmarFuncionario(idFunc){

    if (idFunc > 0){

        let vValPago = '';
        let vArrDesp = [];

        listaFuncionarios.forEach(funcionario => {

            if (funcionario.pessoaId == idFunc){

                vValPago = funcionario.valorPago;

                funcionario.pessoaDespesas.forEach(despesa => {
                    vArrDesp.push({despesa: despesa.pesDesId, valor: despesa.pesDesVal, valororig: despesa.pesDesValOrig});
                });
    
            }

        });
            
        $.ajax({         
            url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
            dataType: 'json',
            type: 'POST',
            data: {oper: 28, fechamento: fechtoSelecionado, funcionario: idFunc, valorpago: vValPago, despesas: vArrDesp},
            beforeSend: function(){
                $.blockUI({ message: '<p>Aguarde...</p>' });
            },
            success: function(dados){
    
                $.unblockUI();
                
                if (dados[0] == 'Ok'){
                    toastMensagem(dados[1], 'Fechamento', '#30eb73');
                    listarPessoasDespesas();
                    listaFechtosPessoas();
                } else {
                    modalOk(function(){}, dados[1]);
                }
                
            },
            error: function(dadosErro){
                $.unblockUI();
                console.log(dadosErro);
            }
        });

    } else {
        modalOk(function(){}, 'Funcionário não identificado!');
    }

}

function cancelarFecDesp(fecDespId){

    $.ajax({         
        url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
        dataType: 'json',
        type: 'POST',
        data: {oper: 30, idfec: fecDespId, id: fechtoSelecionado},
        beforeSend: function(){
            $.blockUI({ message: '<p>Aguarde...</p>' });
        },
        success: function(dados){

            $.unblockUI();
            console.log(dados);
            if (dados[0] == 'Ok'){
                toastMensagem(dados[1], 'Fechamento', '#30eb73');
                listaFechtosPessoas();
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

function finalizarFechamento(){

    $.ajax({         
        url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
        dataType: 'json',
        type: 'POST',
        data: {oper: 31, id: fechtoSelecionado},
        beforeSend: function(){
            $.blockUI({ message: '<p>Aguarde...</p>' });
        },
        success: function(dados){

            $.unblockUI();
            
            if (dados[0] == 'Ok'){
                toastMensagem(dados[1], 'Fechamento', '#30eb73');
                consultaFechto();
                listaFechtos();
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

$(document).ready(function(){

    $("#tituloAplicacao").html('Fechamento ' + $("#tituloAplicacao").html());

    $('.montaTabela').on('click', 'tr.registroTab', function(){
        fechtoSelecionado = $(this).attr('fechto');
        consultaFechto();
    });

    $('.row').on('click', 'button.novoCadastro', function(){
        novoCad();
    });

    $(".visaoRegistro").on("click", "button.salvaFechto", function(){
        salvaFechto();
    });

    $(".visaoRegistro").on("click", ".voltaLista", function(){
        trocaVisao('lista');
    });

    $(".visaoRegistro").on("click", ".cancelaFechto", function(){
        cancelaFechto();
    });

    $(".listaDespesas").on("click", ".pesqDespesasPessoa", function(){
        listarPessoasDespesas();
    });

    $(".listaFuncionarios").on("change", ".campoValorDespesa", function(){
        alteraValorDespesa($(this).attr('func'), $(this).attr('pesdes'), $(this).val());
    });

    $(".listaFuncionarios").on("change", ".campoValorPago", function(){
        alteraValorPago($(this).attr('func'), $(this).val());
    });

    $(".listaFuncionarios").on("click", ".confirmaFuncionario", function(){
        confirmarFuncionario($(this).attr('func'));
    });

    $(".listaPessoasDespesas").on("click", ".cancelaFuncionario", function(){
        cancelarFecDesp($(this).attr('fecdesp'));
    });

    $(".listaPessoasDespesas").on("click", ".confirmarFechto", function(){
        finalizarFechamento();
    });

});