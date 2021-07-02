ctrConsulta.numRegistros = 20;
ctrConsulta.ordenacao    = ['id', 'asc'];

var despesaSelecionada = 0;

function inicia_processo(){
    listaDespesas();
}

function processaPagina(){
    listaDespesas();
}

function listaDespesas(){

    $.ajax({         
        url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
        dataType: 'json',
        type: 'POST',
        data: {oper: 9, ctrConsulta: ctrConsulta},
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
                        <td class="ordenacao" tipord="C" tipo="nome">Nome</td>
                        <td class="ordenacao" tipord="C" tipo="valor">Valor</td>
                        <td class="ordenacao" tipord="C" tipo="situacao">Situação</td>
                        <td class="ordenacao" tipord="C" tipo="tipo">Tipo</td>
                    </tr>
                </thead>
                <tbody>
        `;

        dados.forEach(elemento => {
        
            let vTipo = '';

            switch (elemento.despTipo){
                case 'PON':
                    vTipo = 'Por ponto';
                break;
                case 'FUN':
                    vTipo = 'Funcionário';
                break;
                default:
                    vTipo = 'Outros';
            }

            textoTabela += `
                <tr despesa="${elemento.despId}" class="registroTab">
                    <td>${elemento.despId}</td>
                    <td>${elemento.despNome}</td>
                    <td>${elemento.despValor.replace('.',',')}</td>
                    <td>${elemento.despSit}</td>
                    <td>${vTipo}</td>
                </tr>
            `;

        });

        textoTabela += '</tbody></table>';

    }

    montaPaginacao(numTotalReg);

    let vFiltros = [
        {campo: 'id',       descricao: 'Id',       tipo: 'int'},
        {campo: 'nome',     descricao: 'Nome',     tipo: 'str'},
        {campo: 'valor',    descricao: 'Valor',    tipo: 'num'},
        {campo: 'situacao', descricao: 'Situação', tipo: 'str'},
        {campo: 'tipo',     descricao: 'Tipo',     tipo: 'str'}
    ];

    criarFiltro(vFiltros);

    $("div.montaTabela").html(textoTabela);

    alteraOrdenacao();
    
}

function limparForm(){
    $("#despesaNome").val('');
    $("#despesaValor").val('');
    $("#despesaSituacao").val('A');
    $("#despesaTipo").val('FUN');
    $("#despesaId").html(0);
    $("#despesaDataInclusao").val('');
    $("#despesaDataAlteracao").val('');
    $("#despesaInclusao").html('');
    $("#despesaAlteracao").html('');
}

function preencherDadosDespesa(despesa){

    $("#despesaNome").val(despesa.despNome);
    $("#despesaValor").val(parseFloat(despesa.despValor).toFixed(2).replace('.',','));
    $("#despesaSituacao").val(despesa.despSit);
    $("#despesaTipo").val(despesa.despTipo);
    $("#despesaId").html(despesa.despId);
    $("#despesaDataInclusao").val((despesa.dtaInc !== null ? despesa.dtaInc.substr(0,10) : ''));
    $("#despesaDataAlteracao").val((despesa.dtaAlt !== null ? despesa.dtaAlt.substr(0,10) : ''));
    $("#despesaInclusao").html(despesa.usrInc);
    $("#despesaAlteracao").html(despesa.usrAlt);

}

function consultaDespesa(){

    if (despesaSelecionada.length <= 0 || despesaSelecionada <= 0){
        modalOk(function(){}, 'Id da despesa não identificada!');
    } else {

        $.ajax({         
            url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
            dataType: 'json',
            type: 'POST',
            data: {oper: 10, id: despesaSelecionada},
            beforeSend: function(){
                $.blockUI({ message: '<p>Aguarde...</p>' });
            },
            success: function(dados){
    
                $.unblockUI();
                
                if (dados[0] == 'Ok'){
                    preencherDadosDespesa(dados[1]);
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

    despesaSelecionada = 0;
    limparForm();

    $('.modalEdicao').modal('show');

}


function salvarDespesa(){

    let dados = {
        oper: 11,
        nome:     $("#despesaNome").val(),
        valor:    $("#despesaValor").val(),
        situacao: $("#despesaSituacao option:selected").val(),
        tipo:     $("#despesaTipo option:selected").val(),
        id: despesaSelecionada
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
                despesaSelecionada = dados[2];
                toastMensagem(dados[1], 'Despesa', '#30eb73');
                consultaDespesa();
                listaDespesas();
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

    $("#tituloAplicacao").html('Despesas ' + $("#tituloAplicacao").html());

    $('.montaTabela').on('click', 'tr.registroTab', function(){
        despesaSelecionada = $(this).attr('despesa');
        consultaDespesa();
    });

    $('.row').on('click', 'button.novoCadastro', function(){
        novoCad();
    });

    $(".modalEdicao").on("click", "button#salvarDespesa", function(){
        salvarDespesa();
    });

});