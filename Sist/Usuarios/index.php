<?php
    require_once '../compartilhado/verificaSessao.php';
?> 

<!DOCTYPE html>
<html>
    <head>

      <?php
        require_once '../compartilhado/padrao_top.php';
      ?>

      <title>Romanza - Usuários</title>

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
                        <h5 class="modal-title" id="exampleModalCenterTitle">Usuário</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form>
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label for="usuarioId">Id</label><br>
                                    <label id="usuarioId"></label>
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="usuarioLogin">Login</label>
                                    <input type="text" maxlength="50" class="form-control" id="usuarioLogin" placeholder="Login">
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="usuarioEmail">Email</label>
                                    <input type="email" maxlength="100" class="form-control" id="usuarioEmail" placeholder="Email">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label for="usuarioSituacao">Situação</label>
                                    <select id="usuarioSituacao" class="form-control" style="width: 120px;">
                                        <option value="A">A - Ativo</option>
                                        <option value="I">I - Inativo</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="usuarioDataInclusao">Data Inclusão</label>
                                    <input type="date" class="form-control" disabled id="usuarioDataInclusao">
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="usuarioDataAlteracao">Data Alteração</label>
                                    <input type="date" class="form-control" disabled id="usuarioDataAlteracao">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label for="usuarioInclusao">Usuário Inclusão</label><br>
                                    <label id="usuarioInclusao"></label>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="usuarioAlteracao">Usuário Alteração</label><br>
                                    <label id="usuarioAlteracao"></label>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="alteraSenha">
                                        <label class="form-check-label" for="alteraSenha">
                                            Alterar senha
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="usuarioSen">Senha</label>
                                    <input type="password" class="form-control" disabled id="usuarioSen" placeholder="Senha">
                                </div>
                            </div>

                            <div class="form-row listaPermissoes">

                            </div>

                        </form>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-success" id="salvarUsuario">Salvar</button>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Sair</button>
                    </div>
                </div>
            </div>
        </div>

        <?php
            require_once '../compartilhado/padrao_bottom.php';
        ?>

        <script src="../compartilhado/ctrConsulta.js"></script>
        <script src="index_usuario.js"></script>

        <?php
            validaUsuario('Usuario', true);
        ?>

    </body>

</html>