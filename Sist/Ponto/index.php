<?php
    require_once '../compartilhado/verificaSessao.php';
?> 

<!DOCTYPE html>
<html>
    <head>

      <?php
        require_once '../compartilhado/padrao_top.php';
      ?>

      <title>Romanza - Ponto</title>

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

                    <div class="col-8 cabecalhoPonto">
                    </div>

                </div>

                <div class="row listaFuncionarios mb-2">

                </div>

            </div>

        </div>

        <?php
            require_once '../compartilhado/padrao_bottom.php';
        ?>

        <script src="../compartilhado/ctrConsulta.js"></script>
        <script src="index_ponto.js"></script>

        <?php
            validaUsuario('Ponto', true);
        ?>

    </body>

</html>