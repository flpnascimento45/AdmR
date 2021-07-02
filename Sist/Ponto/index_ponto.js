ctrConsulta.numRegistros = 20;
ctrConsulta.ordenacao    = ['data', 'desc'];

var pontoSelecionado = 0;

function inicia_processo(){
    listaPontos();
}

function processaPagina(){
    listaPontos();
}

function listaPontos(){

    $.ajax({         
        url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
        dataType: 'json',
        type: 'POST',
        data: {oper: 17, ctrConsulta: ctrConsulta},
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
                        <td class="ordenacao" tipord="C" tipo="data">Data</td>
                        <td class="ordenacao" tipord="C" tipo="situacao">Situação</td>
                    </tr>
                </thead>
                <tbody>
        `;

        dados.forEach(elemento => {

            let vDta = elemento.pontoData.split('-').reverse().join('/');
            let vSit = '';

            switch (elemento.pontoSituacao){
                case 'F':
                    vSit = 'F - Fechado';
                break;
                default:
                    vSit = 'A - Aberto';
            }

            textoTabela += `
                <tr ponto="${elemento.pontoId}" class="registroTab">
                    <td>${elemento.pontoId}</td>
                    <td>${vDta}</td>
                    <td>${vSit}</td>
                </tr>
            `;

        });

        textoTabela += '</tbody></table>';

    }

    montaPaginacao(numTotalReg);

    let vFiltros = [
        {campo: 'id',       descricao: 'Id',       tipo: 'int'},
        {campo: 'data',     descricao: 'Data',     tipo: 'dat'},
        {campo: 'situacao', descricao: 'Situação', tipo: 'str'}
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

function criaListaFunc(listaFuncionario){

    let txtFunc = `
                    <table class="table mt-2">
                        <thead style="background-color: #34495e; color: white;">
                            <tr>
                                <td tipo="fantasia">Nome</td>
                                <td tipo="presenteAusente">Presente / Ausente</td>
                            </tr>
                        </thead>
                        <tbody>
                `;

    listaFuncionario.forEach(func => {

        let vCheck   = func.ponPesPresente == 1 ? 'checked' : '';
        let txtCheck = func.ponPesPresente == 1 ? '<b style="color: green;">Presente</b>' : 'Ausente';

        txtFunc += `
                    <tr pessoa="${func.ponPesId}">
                        <td>${func.pessoa}</td>
                        <td>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" ${vCheck} disabled class="custom-control-input checkPresente" id="switch${func.ponPesId}">
                                <label class="custom-control-label" for="switch${func.ponPesId}">${txtCheck}</label>
                            </div>
                        </td>
                    </tr>
                `;
    });

    txtFunc += '</tbody></table>';

    $('.listaFuncionarios').html(txtFunc);

}

function listarFuncionariosPonto(){

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
        data: {oper: 12, ctrConsulta: vTmpCtrConsulta, tipo: 'FUN'},
        beforeSend: function(){
            $.blockUI({ message: '<p>Aguarde...</p>' });
        },
        success: function(dados){

            $.unblockUI();
            
            if (dados[0] == 'Ok'){

                let txtFunc = `
                    <table class="table mt-2">
                        <thead style="background-color: #34495e; color: white;">
                            <tr>
                                <td>Nome</td>
                                <td class="tdPresenteAusente"><a tipo="P">Presente</a> / <a tipo="A">Ausente</a></td>
                            </tr>
                        </thead>
                        <tbody>
                `;
                
                dados[1].forEach(func => {
                    txtFunc += `
                                <tr pessoa="${func.pessoaId}">
                                    <td>${func.pessoaFantasia}</td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" pessoa="${func.pessoaId}" class="custom-control-input checkPresente" id="switch${func.pessoaId}">
                                            <label class="custom-control-label" for="switch${func.pessoaId}">Ausente</label>
                                        </div>
                                    </td>
                                </tr>
                            `;
                });

                txtFunc += `
                    <tr>
                        <td colspan="2" style="text-align: center";>
                            <button type="button" class="btn btn-outline-success confirmarPonto">Confirmar</button>
                        </td>
                    </tr>
                `;

                txtFunc += '</tbody></table>';

                $('.listaFuncionarios').html(txtFunc);

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

function consultaPonto(){

    if (pontoSelecionado.length <= 0 || pontoSelecionado <= 0){
        modalOk(function(){}, 'Id do ponto não identificado!');
    } else {

        $.ajax({         
            url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
            dataType: 'json',
            type: 'POST',
            data: {oper: 18, id: pontoSelecionado},
            beforeSend: function(){
                $.blockUI({ message: '<p>Aguarde...</p>' });
            },
            success: function(dados){
    
                $.unblockUI();
                
                if (dados[0] == 'Ok'){
                    
                    trocaVisao('registro');

                    if (dados[1]['pontoSituacao'] === 'A'){

                        $(".listaFuncionarios").html('');

                        $('.cabecalhoPonto').html(`
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"><b>Data</b></label>
                                <div class="input-group col-sm-10">
                                    <input type="date" value="${dados[1]['pontoData']}" class="form-control" id="dataPonto" style="max-width: 200px;" />
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-success" id="confirmaInsAltPonto"><span class="oi oi-check"></span></button>
                                    </div>
                                </div>        
                            </div> 
                        `);

                        listarFuncionariosPonto();

                    } else {

                        $(".listaFuncionarios").html('');

                        $('.cabecalhoPonto').html(`
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Data</span>
                                </div>
                                <input type="date" value="${dados[1]['pontoData']}" disabled class="form-control" id="dataPonto" style="max-width: 200px;" />
                            </div>        
                        `);

                        criaListaFunc(dados[1]['pontoPessoas']);

                    }
                    
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

    pontoSelecionado = 0;
    
    $(".listaFuncionarios").html('');

    let vDtAtual = dataAtualFormatada(1);

    $('.cabecalhoPonto').html(`
        <div class="form-group row">
            <label class="col-sm-2 col-form-label"><b>Data</b></label>
            <div class="input-group col-sm-10">
                <input type="date" class="form-control" value="${vDtAtual}" id="dataPonto" style="max-width: 200px;" />
                <div class="input-group-append">
                    <button type="button" class="btn btn-outline-success" id="confirmaInsAltPonto"><span class="oi oi-check"></span></button>
                </div>
            </div> 
        </div>               
    `);

    trocaVisao('registro');

}

function salvarPonto(){

    $.ajax({         
        url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
        dataType: 'json',
        type: 'POST',
        data: {oper: 19, id: pontoSelecionado, data: $("#dataPonto").val()},
        beforeSend: function(){
            $.blockUI({ message: '<p>Aguarde...</p>' });
        },
        success: function(dados){

            $.unblockUI();
            
            if (dados[0] == 'Ok'){
                pontoSelecionado = dados[2];
                toastMensagem(dados[1], 'Ponto', '#30eb73');
                consultaPonto();
                listaPontos();
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

function buscaTabPonto(){

    let retorno = [];

    $(".listaFuncionarios table tbody tr input.checkPresente").each(function(){
        retorno.push({pessoa: $(this).attr('pessoa'), presente: $(this).is(':checked')});
    });

    return retorno;

}

function confirmarPonto(){

    let vPontos = buscaTabPonto();

    modalConfirm(function(confirm, arrParams){

        if (confirm){
            
            $.ajax({         
                url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
                dataType: 'json',
                type: 'POST',
                data: {oper: 20, id: arrParams[0], arrPonto: arrParams[1]},
                beforeSend: function(){
                    $.blockUI({ message: '<p>Aguarde...</p>' });
                },
                success: function(dados){
        
                    $.unblockUI();
                    
                    if (dados[0] == 'Ok'){
                        toastMensagem(dados[1], 'Ponto', '#30eb73');
                        consultaPonto();
                        listaPontos();
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

    }, 'Confirma finalização do ponto?', [pontoSelecionado, vPontos], 'Sim', 'Não');

}

$(document).ready(function(){

    $("#tituloAplicacao").html('Ponto ' + $("#tituloAplicacao").html());

    $('.montaTabela').on('click', 'tr.registroTab', function(){
        pontoSelecionado = $(this).attr('ponto');
        consultaPonto();
    });

    $('.row').on('click', 'button.novoCadastro', function(){
        novoCad();
    });

    $(".visaoRegistro").on("click", "button#confirmaInsAltPonto", function(){
        salvarPonto();
    });

    $(".visaoRegistro").on("click", ".voltaLista", function(){
        trocaVisao('lista');
    });

    $(".listaFuncionarios").on("click", ".checkPresente", function(){
        
        if ($(this).is(':checked')){
            $(this).parent().find('label').html('<b style="color: green">Presente</b>');
        } else {
            $(this).parent().find('label').html('Ausente');
        }

    });

    $(".listaFuncionarios").on("click", ".confirmarPonto", function(){
        confirmarPonto();
    });

    $(".listaFuncionarios").on("click", ".tdPresenteAusente a", function(){

        let vPres = $(this).attr('tipo') == 'P';

        $(".listaFuncionarios table tbody tr input.checkPresente").each(function(){
            
            if (vPres){
                $(this).prop('checked', true);
                $(this).parent().find('label').html('<b style="color: green">Presente</b>');
            } else {
                $(this).prop('checked', false);
                $(this).parent().find('label').html('Ausente');
            }

        });

    });

});