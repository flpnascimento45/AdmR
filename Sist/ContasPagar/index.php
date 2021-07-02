<?php
    require_once '../compartilhado/verificaSessao.php';
?> 

<!DOCTYPE html>
<html>
    <head>

      <?php
        require_once '../compartilhado/padrao_top.php';
      ?>

      <title>Romanza - Contas a Pagar</title>

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
                        <h5 class="modal-title" id="exampleModalCenterTitle">Contas a Pagar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form>
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label for="pagarId">Id</label><br>
                                    <label id="pagarId"></label>
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="pessoa">Favorecido</label>
                                    <select id="pessoa" class="form-control">
                                    </select>
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="despesa">Despesa</label>
                                    <select id="despesa" class="form-control">
                                    </select>
                                </div>
                            </div>

                            <div class="form-row someInclusao">
                                <div class="form-group col-md-4">
                                    <label for="pagarSituacao">Situação</label>
                                    <select id="pagarSituacao" class="form-control" style="width: 200px;">
                                        <option value="A">A - Aberta</option>
                                        <option value="P">P - Pago</option>
                                        <option value="X">X - Cancelado</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="pagarValor">Valor</label>
                                    <input type="text" class="form-control maskdinheiro" id="pagarValor" placeholder="Valor" style="width: 200px;">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="pagarValorPago">Valor pago</label>
                                    <input type="text" class="form-control maskdinheiro" id="pagarValorPago" placeholder="Valor" style="width: 200px;">
                                </div>
                            </div>

                            <div class="form-row someInclusao">
                                <div class="form-group col-md-4">
                                    <label for="pagarDtVencto">Data Vencto</label>
                                    <input type="date" class="form-control" id="pagarDtVencto">
                                </div>
                                <div class="form-group col-md-4 ">
                                    <label for="pagarDtPagto">Data Pagto</label>
                                    <input type="date" class="form-control" id="pagarDtPagto">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="pagarDtDocto">Data Docto</label>
                                    <input type="date" class="form-control" id="pagarDtDocto">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-2 someAlteracao">
                                    <label for="pagarDtDoctoInc">Data Docto</label>
                                    <input type="date" class="form-control" id="pagarDtDoctoInc">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="pagarNumDoc">Num. Docto</label>
                                    <input type="text" maxlength="50" class="form-control" id="pagarNumDoc">
                                </div>
                                <div class="form-group col-md-2 someInclusao">
                                    <label for="pagarSeq">Sequencia</label><br>
                                    <label id="pagarSeq"></label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="pagarObs">Observação</label>
                                    <input type="text" maxlength="100" class="form-control" id="pagarObs">
                                </div>
                            </div>

                            <div class="form-row someInclusao">
                                <div class="form-group col-md-2">
                                    <label for="usuarioInclusao">Usuário Inclusão</label><br>
                                    <label id="usuarioInclusao"></label>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="usuarioAlteracao">Usuário Alteração</label><br>
                                    <label id="usuarioAlteracao"></label>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="dataInclusao">Data Inclusão</label>
                                    <input type="date" class="form-control" disabled id="dataInclusao">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="dataAlteracao">Data Alteração</label>
                                    <input type="date" class="form-control" disabled id="dataAlteracao">
                                </div>
                            </div>

                            <div class="form-row someAlteracao">

                                <table class="table tabelaPrestacoes" style="max-width: 800px;">

                                    <thead>
                                        <tr>
                                            <td>Parc.</td>
                                            <td>Data</td>
                                            <td>Valor</td>
                                            <td>Situação</td>
                                            <td>Data Pagto</td>
                                            <td>Valor Pagto</td>
                                        </tr>
                                    </thead>

                                    <tbody class="tabelaPrestacoesBody">

                                    </tbody>

                                    <tfoot>

                                        <tr>
                                            <td colspan="6">
                                                <button type="button" class="btn btn-outline-success btn-sm" id="incluirPrestacao"><span class="oi oi-plus"></span></button>
                                                <button type="button" class="btn btn-outline-danger btn-sm" id="removerPrestacao"><span class="oi oi-minus"></span></button>
                                            </td>
                                        </tr>

                                    </tfoot>
                                    
                                </table>

                            </div>
                    
                        </form>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-success" id="salvarConta">Salvar</button>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Sair</button>
                    </div>
                </div>
            </div>
        </div>

        <?php
            require_once '../compartilhado/padrao_bottom.php';
        ?>

        <script src="../compartilhado/ctrConsulta.js"></script>
        <script src="index_pagar.js"></script>

        <?php
            validaUsuario('ContasPagar', true);
        ?>

    </body>

</html>