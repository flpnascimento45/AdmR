var tipoFormulario = 'FUN';

var pessoaSelecionada  = 0;

function limparForm(){
    $("#pessoaFantasia").val('');
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
    $("#pessoaValCred").val('0,00');

    $(".listaDespesas").html('');

}

function preencherDadosPessoaDespesa(arrayDespesas){

    let txtDespesas = '<div class="col-12"><b>Despesas</b></div>';

    txtDespesas += '<div class="col-sm-12 col-md-6 col-lg-4 col-xl-4"><table class="table table-sm">';

    arrayDespesas.forEach(despesa => {
        
        txtDespesas += `
            <tr id="desp${despesa.pesDesId}" class="trConsultaDespesaPessoa">
                <td class="tdDespBtn">
                    <button type="button" class="btn btn-sm btn-outline-info altIncDespesa" desp="${despesa.pesDesId}" style="padding: .2rem .3rem; line-height: 1;">
                        <span class="oi oi-pencil"></span>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger inativarDespesa" desp="${despesa.pesDesId}" style="padding: .2rem .3rem; line-height: 1;">
                        <span class="oi oi-x"></span>
                    </button>
                </td>
                <td class="tdDespDescricao" style="min-width: 120px;">${despesa.despesa}</td>
                <td class="tdDespValor" style="min-width: 120px;">${despesa.pesDesVal.replace('.',',')}</td>
            </tr>
        `;

    });
    
    txtDespesas += `
                <tr>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-success altIncDespesa" desp="0" style="padding: .2rem .3rem; line-height: 1;">
                            <span class="oi oi-plus"></span>
                        </button>
                    </td>
                    <td colspan="2">Clique para adicionar despesa</td>
            </table>
        </div>
    `;

    $(".listaDespesas").html(txtDespesas);

}

function preencherDadosPessoa(pessoa){

    $("#pessoaFantasia").val(pessoa.pessoaFantasia);
    $("#pessoaCpfCnpj").val(retornaTextoCpf(pessoa.pessoaCpfCnpj));
    $("#pessoaTelefone").val(retornaTextoTel(pessoa.pessoaTelefone));
    $("#pessoaCelular").val(retornaTextoTel(pessoa.pessoaCelular));
    $("#pessoaSituacao").val(pessoa.pessoaSituacao);
    $("#pessoaId").html(pessoa.pessoaId);
    $("#pessoaObs").val(pessoa.pessoaObs);
    $("#pessoaValCred").val(parseFloat(pessoa.pessoaValCredito).toFixed(2).replace('.',','));
    $("#dataInclusao").val((pessoa.dtaInc !== null ? pessoa.dtaInc.substr(0,10) : ''));
    $("#dataAlteracao").val((pessoa.dtaAlt !== null ? pessoa.dtaAlt.substr(0,10) : ''));
    $("#usuarioInclusao").html(pessoa.usrInc);
    $("#usuarioAlteracao").html(pessoa.usrAlt);

    preencherDadosPessoaDespesa(pessoa.pessoaDespesas);

}

function consultarFuncionario(apenasDespesas=false){

    if (pessoaSelecionada.length <= 0 || pessoaSelecionada <= 0){
        modalOk(function(){}, 'Id do funcionário não identificado!');
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

                    if (apenasDespesas){
                        preencherDadosPessoaDespesa(dados[1]['pessoaDespesas']);
                    } else {
                        preencherDadosPessoa(dados[1]);
                        $('.modalEdicao').modal('show');
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

    pessoaSelecionada = 0;
    limparForm();

    $('.modalEdicao').modal('show');

}

function salvarPessoa(){

    let dados = {
        oper: 14,
        nome:       $("#pessoaFantasia").val(),
        razao:      $("#pessoaFantasia").val(),
        cpfcnpj:    $("#pessoaCpfCnpj").val().replace(/[//\.-]/g,''),
        telefone:   $("#pessoaTelefone").val().replace(/[()\.-]/g,''),
        celular:    $("#pessoaCelular").val().replace(/[()\.-]/g,''),
        situacao:   $("#pessoaSituacao option:selected").val(),
        obs:        $("#pessoaObs").val(),
        valCredito: $("#pessoaValCred").val(),
        tipo:       tipoFormulario,
        id:         pessoaSelecionada
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
                toastMensagem(dados[1], 'Funcionário', '#30eb73');
                consultarFuncionario();
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

                let textoDesp = '<select class="form-control form-control-sm selectDespesa" style="width: 120px;">'+
                                    '<option value="0" valor="0.00">Selecionar</option>';
                
                dados[1].forEach(desp => {
                    textoDesp += `<option value="${desp.despId}" valor="${desp.despValor}">${desp.despNome}</option>`;
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
                                textoDesp += `<option value="${desp.despId}" valor="${desp.despValor}">${desp.despNome}</option>`;
                            });

                            textoDesp += '</select>';

                            $('.listaDespesas td.tipoDespesa').html(textoDesp);

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

function criaLinhaEdicaoDespesa(despesa){

    let txtTipoDesp = '<td class="tipoDespesa">' + despesa.descricao + '</td>';

    let txtDespesa = `
            <tr id="editDesp${despesa.id}" class="linhaEdicaoDespesa">
                <td>
                    <button type="button" class="btn btn-sm btn-outline-success confirmaDespesa" desp="${despesa.id}" style="padding: .2rem .3rem; line-height: 1;">
                        <span class="oi oi-check"></span>
                    </button>
                </td>
                ${txtTipoDesp}
                <td><input type="text" class="form-control form-control-sm maskdinheiro valorDespesa" style="width: 120px;" value="${despesa.valor}" /></td>
            </tr>
        `;

    if (despesa.id == 0){
        buscaDespesas();
    }

    $(".listaDespesas table").append(txtDespesa);

}

function salvarDespesa(id){

    let dados = {
        oper: 15,
        id: id,
        idpessoa: pessoaSelecionada,
        valor: $('.listaDespesas input.valorDespesa').val()
    }

    if (id == 0){
        Object.assign(dados, {iddespesa: $(".listaDespesas select.selectDespesa option:selected").val()});
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
                toastMensagem(dados[1], 'Despesa', '#30eb73');
                consultarFuncionario(true);
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

function alteraIncluiDespesa(despesa, descricao, valor){

    $(".listaDespesas tr.trConsultaDespesaPessoa").css('background-color','white');
    
    $(".listaDespesas .linhaEdicaoDespesa").remove();

    criaLinhaEdicaoDespesa({id: despesa, valor: valor, descricao: descricao});

    if (despesa > 0){
        $("tr#desp"+despesa).css('background-color','#ADD8E6');
    }

}

function inativarDespesa(id){

    modalConfirm(function(confirm, arrParams){

        if (confirm){
            
            $.ajax({         
                url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
                dataType: 'json',
                type: 'POST',
                data: {oper: 16, id: arrParams[0]},
                beforeSend: function(){
                    $.blockUI({ message: '<p>Aguarde...</p>' });
                },
                success: function(dados){
        
                    $.unblockUI();
                    
                    if (dados[0] == 'Ok'){
                        toastMensagem(dados[1], 'Despesa', '#30eb73');
                        consultarFuncionario(true);
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

    }, 'Deseja realmente inativar despesa?', [id], 'Sim', 'Não');

}

$(document).ready(function(){

    $("#tituloAplicacao").html('Funcionários ' + $("#tituloAplicacao").html());

    $('.montaTabela').on('click', 'tr.registroTab', function(){
        pessoaSelecionada = $(this).attr('pessoa');
        consultarFuncionario();
    });

    $('.row').on('click', 'button.novoCadastro', function(){
        novoCad();
    });

    $(".modalEdicao").on("click", "button#salvarPessoa", function(){
        salvarPessoa();
    });

    $(".listaDespesas").on("click", ".altIncDespesa", function (){

        let vDescr = '';
        let vValor = '0,00';

        if ($(this).attr('desp') > 0){
            vDescr = $(this).parent().parent().find('td.tdDespDescricao').html();
            vValor = $(this).parent().parent().find('td.tdDespValor').html();
        } 

        alteraIncluiDespesa($(this).attr('desp'), vDescr, vValor);

    });

    $(".listaDespesas").on("change", "select.selectDespesa", function(){
        $(".listaDespesas input.valorDespesa").val($(this).find('option:selected').attr('valor').replace('.',','));
    });

    $(".listaDespesas").on("click", "button.confirmaDespesa", function(){
        salvarDespesa($(this).attr('desp'));
    });

    $(".listaDespesas").on("click", "button.inativarDespesa", function(){
        inativarDespesa($(this).attr('desp'));
    });

});