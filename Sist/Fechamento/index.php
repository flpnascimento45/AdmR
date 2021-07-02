<?php
    require_once '../compartilhado/verificaSessao.php';
?> 

<!DOCTYPE html>
<html>
    <head>

      <?php
        require_once '../compartilhado/padrao_top.php';
      ?>

      <title>Romanza - Fechamento</title>

    </head>

    <body>

        <?php
            require_once '../compartilhado/menu/menu.php';
        ?>

        <div class="main">

            <div class="row visaoLista filtrosTela">
            </div>
            
            <div class="row visaoLista mt-1">
                <button type="button" class="btn btn-outline-success novoCadastro">Novo</button>
            </div>

            <div class="row visaoLista montaTabela mt-1">
            </div>

            <div class="row visaoLista paginacaoTela">
            </div>

            <div class="row visaoRegistro" style="display: none;">
                
                <div class="row">

                    <div class="col-4">
                        <button type="button" class="btn btn-outline-success voltaLista">
                            <span class="oi oi-action-undo"></span>
                        </button>
                    </div>

                    <div class="col-8 cabecalhoRegistro">
                    </div>

                </div>

                <div class="row regFechto mt-2 mb-1">

                    <form class="col-12">

                        <div class="form-row col-12">
                            
                            <div class="form-group col-md-2">
                                <label for="fechtoId">Id</label><br>
                                <label id="fechtoId"></label>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="fechtoDescricao">Descrição</label>
                                <input type="text" maxlength="50" class="form-control form-control-sm" id="fechtoDescricao"/>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="fechtoData">Data</label>
                                <input type="date" class="form-control form-control-sm" id="fechtoData"/>
                            </div>

                        </div>

                        <div class="form-row col-12">

                            <div class="form-group col-md-2">
                                <label for="fechtoSituacao">Situação</label><br>
                                <label id="fechtoSituacao"></label>
                            </div>

                            <div class="form-group col-md-2">
                                <label for="usuarioInclusao">Usuário Inclusão</label><br>
                                <label id="usuarioInclusao"></label>
                            </div>

                            <div class="form-group col-md-2">
                                <label for="usuarioAlteracao">Usuário Alteração</label><br>
                                <label id="usuarioAlteracao"></label>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="dataInclusao">Data Inclusão</label>
                                <input type="date" class="form-control form-control-sm" disabled id="dataInclusao">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="dataAlteracao">Data Alteração</label>
                                <input type="date" class="form-control form-control-sm" disabled id="dataAlteracao">
                            </div>

                        </div>

                    </form>

                </div>

                <div class="row listaPessoasDespesas mb-2">

                </div>

                <div class="row listaDespesas mb-2">

                </div>

                <div class="row listaFuncionarios mb-2">

                </div>

            </div>

        </div>

        <?php
            require_once '../compartilhado/padrao_bottom.php';
        ?>

        <script src="../compartilhado/ctrConsulta.js"></script>
        <script src="index_fechamento.js"></script>

        <?php
            validaUsuario('FechtoFunc', true);
        ?>

    </body>

</html>