var tipoFormulario = 'PSV';

var pessoaSelecionada = 0;


function limparForm(){
    $("#pessoaFantasia").val('');
    $("#pessoaRazao").val('');
    $("#pessoaCpfCnpj").val('');
    $("#pessoaSituacao").val('A');
    $("#pessoaId").html(0);
    $("#pessoaObs").val('');
    $("#pessoaTelefone").val('');
    $("#pessoaCelular").val('');
    $("#dataInclusao").val('');
    $("#dataAlteracao").val('');
    $("#usuarioInclusao").html('');
    $("#usuarioAlteracao").html('');
}

function preencherDadosPessoa(pessoa){

    $("#pessoaFantasia").val(pessoa.pessoaFantasia);
    $("#pessoaRazao").val(pessoa.pessoaRazao);
    $("#pessoaCpfCnpj").val(retornaTextoCpf(pessoa.pessoaCpfCnpj));
    $("#pessoaTelefone").val(retornaTextoTel(pessoa.pessoaTelefone));
    $("#pessoaCelular").val(retornaTextoTel(pessoa.pessoaCelular));
    $("#pessoaSituacao").val(pessoa.pessoaSituacao);
    $("#pessoaId").html(pessoa.pessoaId);
    $("#pessoaObs").val(pessoa.pessoaObs);
    $("#dataInclusao").val((pessoa.dtaInc !== null ? pessoa.dtaInc.substr(0,10) : ''));
    $("#dataAlteracao").val((pessoa.dtaAlt !== null ? pessoa.dtaAlt.substr(0,10) : ''));
    $("#usuarioInclusao").html(pessoa.usrInc);
    $("#usuarioAlteracao").html(pessoa.usrAlt);

}

function consultaPrestServ(){

    if (pessoaSelecionada.length <= 0 || pessoaSelecionada <= 0){
        modalOk(function(){}, 'Id não identificado!');
    } else {

        $.ajax({         
            url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
            dataType: 'json',
            type: 'POST',
            data: {oper: 13, id: pessoaSelecionada, tipo: tipoFormulario},
            beforeSend: function(){
                $.blockUI({ message: '<p>Aguarde...</p>' });
            },
            success: function(dados){
    
                $.unblockUI();
                
                if (dados[0] == 'Ok'){
                    preencherDadosPessoa(dados[1]);
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

    pessoaSelecionada = 0;
    limparForm();

    $('.modalEdicao').modal('show');

}

function salvarPessoa(){

    let dados = {
        oper: 14,
        nome:     $("#pessoaFantasia").val(),
        razao:    $("#pessoaFantasia").val(),
        cpfcnpj:  $("#pessoaCpfCnpj").val().replace(/[//\.-]/g,''),
        telefone: $("#pessoaTelefone").val().replace(/[()\.-]/g,''),
        celular:  $("#pessoaCelular").val().replace(/[()\.-]/g,''),
        situacao: $("#pessoaSituacao option:selected").val(),
        obs:      $("#pessoaObs").val(),
        tipo:     tipoFormulario,
        id:       pessoaSelecionada
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
                pessoaSelecionada = dados[2];
                toastMensagem(dados[1], 'Prest. Serviço', '#30eb73');
                consultaPrestServ();
                listaPessoa();
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

    $("#tituloAplicacao").html('Prest. Serviço ' + $("#tituloAplicacao").html());

    $('.montaTabela').on('click', 'tr.registroTab', function(){
        pessoaSelecionada = $(this).attr('pessoa');
        consultaPrestServ();
    });

    $('.row').on('click', 'button.novoCadastro', function(){
        novoCad();
    });

    $(".modalEdicao").on("click", "button#salvarPessoa", function(){
        salvarPessoa();
    });

});