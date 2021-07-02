<?php
    require_once '../compartilhado/verificaSessao.php';
?> 

<!DOCTYPE html>
<html>
    <head>

      <?php
        require_once '../compartilhado/padrao_top.php';
      ?>

      <title>Romanza - Prest. Serviço</title>

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
                        <h5 class="modal-title" id="exampleModalCenterTitle">Prest. Serviço</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form>

                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label for="pessoaId">Id</label><br>
                                    <label id="pessoaId"></label>
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="pessoaFantasia">Fantasia</label>
                                    <input type="text" maxlength="100" class="form-control" id="pessoaFantasia" placeholder="Fantasia">
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="pessoaRazao">Razão</label>
                                    <input type="text" maxlength="100" class="form-control" id="pessoaRazao" placeholder="Razão">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-5">
                                    <label for="pessoaCpfCnpj">Cpf/Cnpj</label>
                                    <input type="text" maxlength="18" class="form-control maskcpfcnpj" id="pessoaCpfCnpj" placeholder="Cpf/Cnpj">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="pessoaTelefone">Telefone</label>
                                    <input type="text" maxlength="14" class="form-control masktelefone" id="pessoaTelefone" placeholder="Telefone">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="pessoaCelular">Celular</label>
                                    <input type="text" maxlength="14" class="form-control maskcelular" id="pessoaCelular" placeholder="Celular">
                                </div>
                                
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="pessoaSituacao">Situação</label>
                                    <select id="pessoaSituacao" class="form-control" style="width: 120px;">
                                        <option value="A">A - Ativo</option>
                                        <option value="I">I - Inativo</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="pessoaObs">Observação</label>
                                    <input type="text" maxlength="200" class="form-control" id="pessoaObs" placeholder="Observação">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="dataInclusao">Data Inclusão</label>
                                    <input type="date" class="form-control" disabled id="dataInclusao">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="dataAlteracao">Data Alteração</label>
                                    <input type="date" class="form-control" disabled id="dataAlteracao">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="usuarioInclusao">Usuário Inclusão</label><br>
                                    <label id="usuarioInclusao"></label>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="usuarioAlteracao">Usuário Alteração</label><br>
                                    <label id="usuarioAlteracao"></label>
                                </div>
                            </div>
                    
                        </form>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-success" id="salvarPessoa">Salvar</button>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Sair</button>
                    </div>
                </div>
            </div>
        </div>

        <?php
            require_once '../compartilhado/padrao_bottom.php';
        ?>

        <script src="../compartilhado/ctrConsulta.js"></script>
        <script src="../compartilhado/listaPessoas.js"></script>
        <script src="index_prestserv.js"></script>

        <?php
            validaUsuario('PrestServ', true);
        ?>

    </body>

</html>