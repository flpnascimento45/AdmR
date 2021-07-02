ctrConsulta.numRegistros = 20;
ctrConsulta.ordenacao    = ['id', 'asc'];

var usuarioSelecionado = 0;

function inicia_processo(){
    listaUsuarios();
}

function processaPagina(){
    listaUsuarios();
}

function listaUsuarios(){

    $.ajax({         
        url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
        dataType: 'json',
        type: 'POST',
        data: {oper: 5, ctrConsulta: ctrConsulta},
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
                        <td class="ordenacao" tipord="C" tipo="login">Login</td>
                        <td class="ordenacao" tipord="C" tipo="email">Email</td>
                        <td class="ordenacao" tipord="C" tipo="situacao">Situação</td>
                    </tr>
                </thead>
                <tbody>
        `;

        dados.forEach(elemento => {
        
            textoTabela += `
                <tr usuario="${elemento.usrId}" class="registroTab registroTabUsuario">
                    <td>${elemento.usrId}</td>
                    <td>${elemento.usrLogin}</td>
                    <td>${elemento.usrEmail}</td>
                    <td>${elemento.usrSituacao}</td>
                </tr>
            `;

        });

        textoTabela += '</tbody></table>';

    }

    montaPaginacao(numTotalReg);

    let vFiltros = [
        {campo: 'id',       descricao: 'Id',       tipo: 'int'},
        {campo: 'login',    descricao: 'Login',    tipo: 'str'},
        {campo: 'email',    descricao: 'Email',    tipo: 'str'},
        {campo: 'situacao', descricao: 'Situação', tipo: 'str'}
    ];

    criarFiltro(vFiltros);

    $("div.montaTabela").html(textoTabela);

    alteraOrdenacao();
    
}

function limparForm(){
    $("#usuarioLogin").val('');
    $("#usuarioEmail").val('');
    $("#usuarioSen").val('');
    $("#usuarioSituacao").val('A');
    $("#usuarioId").html(0);
    $("#usuarioDataInclusao").val('');
    $("#usuarioDataAlteracao").val('');
    $("#usuarioInclusao").html('');
    $("#usuarioAlteracao").html('');
    $(".listaPermissoes").html('');
}

function preencherDadosUsuario(usuario){

    $("#usuarioLogin").val(usuario.usrLogin);
    $("#usuarioEmail").val(usuario.usrEmail);
    $("#usuarioSituacao").val(usuario.usrSituacao);
    $("#usuarioId").html(usuario.usrId);
    $("#usuarioDataInclusao").val((usuario.dtaInc !== null ? usuario.dtaInc.substr(0,10) : ''));
    $("#usuarioDataAlteracao").val((usuario.dtaAlt !== null ? usuario.dtaAlt.substr(0,10) : ''));
    $("#usuarioInclusao").html(usuario.usrInc);
    $("#usuarioAlteracao").html(usuario.usrAlt);
    $("#usuarioSen").val('');

    let txtAcessos = '<div class="col-12"><b>Permissões</b></div>';

    usuario.acessos.forEach(acesso => {

        vChecked = acesso.acessoValor === 'S' ? 'checked' : '';

        txtAcessos += `
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input checkPermissao" type="checkbox" ${vChecked} id="${acesso.acessoId}">
                    <label class="form-check-label" for="alteraSenha">
                        ${acesso.acessoDescricao}
                    </label>
                </div>
            </div>
        `
    });

    $(".listaPermissoes").html(txtAcessos);

    $(".listaPermissoes").on('click','.form-check-label',function(e){
        e.preventDefault();
    });

}

function consultaUsuario(){

    if (usuarioSelecionado.length <= 0 || usuarioSelecionado <= 0){
        modalOk(function(){}, 'Id do usuário não identificado!');
    } else {

        $('#alteraSenha').parent().css('display','');
        $('#alteraSenha').prop('checked', false);
        $('#usuarioSen').attr('disabled', 'true');

        $.ajax({         
            url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
            dataType: 'json',
            type: 'POST',
            data: {oper: 7, id: usuarioSelecionado},
            beforeSend: function(){
                $.blockUI({ message: '<p>Aguarde...</p>' });
            },
            success: function(dados){
    
                $.unblockUI();
                
                if (dados[0] == 'Ok'){
                    preencherDadosUsuario(dados[1]);
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

    usuarioSelecionado = 0;
    limparForm();

    $('#alteraSenha').parent().css('display','none');
    $('#usuarioSen').removeAttr('disabled');

    $('.modalEdicao').modal('show');

}

function retornaAcessos(){

    let permissoes = [];

    $(".listaPermissoes input.checkPermissao").each(function(){

        let acesso = {
            id: $(this).attr('id'),
            valor: ($(this).is(':checked') ? 'S' : 'N')
        };

        permissoes.push(acesso);

    });

    return permissoes;

}

function salvarUsuario(){

    if ($('#alteraSenha').is(':checked') && usuarioSelecionado > 0 && $("#usuarioSen").val().length <= 0){
        modalOk(function(){}, 'Preencher senha!');
    } else {

        let dados = {
            oper: 8,
            login: $("#usuarioLogin").val(),
            email: $("#usuarioEmail").val(),
            situacao: $("#usuarioSituacao option:selected").val(),
            id: usuarioSelecionado
        }
    
        if ($('#alteraSenha').is(':checked') || usuarioSelecionado <= 0){
            Object.assign(dados, {sen: $("#usuarioSen").val()});
        }

        if (usuarioSelecionado > 0){
            let acessos = retornaAcessos();
            Object.assign(dados, {acessos: acessos});
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
                    usuarioSelecionado = dados[2];
                    toastMensagem(dados[1], 'Usuário', '#30eb73');
                    consultaUsuario();
                    listaUsuarios();
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

$(document).ready(function(){

    $("#tituloAplicacao").html('Usuários ' + $("#tituloAplicacao").html());

    $('.montaTabela').on('click', 'tr.registroTabUsuario', function(){
        usuarioSelecionado = $(this).attr('usuario');
        consultaUsuario();
    });

    $(".modalEdicao").on('click','#alteraSenha',function(){

        if ($(this).is(':checked')){
            $('#usuarioSen').removeAttr('disabled');
        } else {
            $('#usuarioSen').attr('disabled', 'true');
        }
        
    });

    $('.row').on('click', 'button.novoCadastro', function(){
        novoCad();
    });

    $(".modalEdicao").on("click", "button#salvarUsuario", function(){
        salvarUsuario();
    });

});