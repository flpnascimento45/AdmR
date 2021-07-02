<?php
    require_once '../compartilhado/verificaSessao.php';
?> 

<!DOCTYPE html>
<html>
    <head>

      <?php
        require_once '../compartilhado/padrao_top.php';
      ?>

      <title>Romanza - Despesas</title>

    </head>

    <body>

        <?php
            require_once '../compartilhado/menu/menu.php';
        ?>

        <div class="main">

            <div class="row filtrosTela">
            </div>
            
            <div class="row mt-1">
                <button type="button" class="btn btn-outline-success novoCadastro">Novo</button>
            </div>

            <div class="row montaTabela mt-1">
            </div>

            <div class="row paginacaoTela">
            </div>

        </div>

        <div class="modal fade modalEdicao" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 999999999;">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Despesa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form>
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label for="despesaId">Id</label><br>
                                    <label id="despesaId"></label>
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="despesaNome">Nome</label>
                                    <input type="text" maxlength="50" class="form-control" id="despesaNome" placeholder="Nome">
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="despesaValor">Valor</label>
                                    <input type="text" class="form-control maskdinheiro" id="despesaValor" placeholder="Valor">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label for="despesaSituacao">Situação</label>
                                    <select id="despesaSituacao" class="form-control" style="width: 120px;">
                                        <option value="A">A - Ativo</option>
                                        <option value="I">I - Inativo</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="despesaDataInclusao">Data Inclusão</label>
                                    <input type="date" class="form-control" disabled id="despesaDataInclusao">
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="despesaDataAlteracao">Data Alteração</label>
                                    <input type="date" class="form-control" disabled id="despesaDataAlteracao">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="despesaTipo">Tipo</label>
                                    <select id="despesaTipo" class="form-control" style="width: 180px;">
                                        <option value="FUN">Funcionário</option>
                                        <option value="PON">Por Ponto</option>
                                        <option value="OUT">Outros</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="despesaInclusao">Usuário Inclusão</label><br>
                                    <label id="despesaInclusao"></label>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="despesaAlteracao">Usuário Alteração</label><br>
                                    <label id="despesaAlteracao"></label>
                                </div>
                            </div>
                    
                        </form>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-success" id="salvarDespesa">Salvar</button>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Sair</button>
                    </div>
                </div>
            </div>
        </div>

        <?php
            require_once '../compartilhado/padrao_bottom.php';
        ?>

        <script src="../compartilhado/ctrConsulta.js"></script>
        <script src="index_despesa.js"></script>

        <?php
            validaUsuario('Despesa', true);
        ?>

    </body>

</html>