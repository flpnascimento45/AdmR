<?php
    require_once 'compartilhado/verificaSessao.php';
?> 

<!DOCTYPE html>
<html>
    <head>

      <?php
        require_once 'compartilhado/padrao_top.php';
      ?>

      <title>Romanza - Inicio</title>

    </head>

    <body>

        <?php
            require_once 'compartilhado/menu/menu.php';
        ?>

        <div class="main">
            <div class="col-12"></div>
        </div>

        <?php
            require_once 'compartilhado/padrao_bottom.php';
        ?>

    </body>

    <script>
        $(document).ready(function(){
            $("#tituloAplicacao").html('Inicio ' + $("#tituloAplicacao").html());
        });
    </script>

</html>