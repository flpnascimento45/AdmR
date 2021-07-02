<?php

    error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);

	if (!isset($_SESSION)){
        session_start();
    }

	$retVld = false;
	
	if (isset($_SESSION)){

        if (isset($_SESSION['usuario'])){

            if (isset($_SESSION['usuario']['usrId'])){
                    
                if ($_SESSION['usuario']['usrId'] > 0){

                    $tokenUser = md5('seg'.$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);

                    if($_SESSION['usuario']['chaveAcesso']  == $tokenUser){
                        $retVld = true;
                    } else {
                        session_destroy();
                    }

                } else {
					session_destroy();
				}

            } else {
				session_destroy();
			}

        } else {
            session_destroy();
        }

    }
	
	if(!$retVld) {
		echo '<meta http-equiv="refresh" content="0; URL='."'".'/AdmR/Login'."'".'"/>';
		exit;
    }
    
    function validaUsuario($aplicacao, $iniciaProcesso){

        $vldIniciaProcesso = true; 

        foreach($_SESSION['usuario']['acessos'] as $acesso){

            if ($aplicacao == $acesso['acessoCodigo'] && $acesso['acessoValor'] != 'S'){
                $vldIniciaProcesso = false;
                echo '<meta http-equiv="refresh" content="0; URL='."'".'/AdmR/Sist'."'".'"/>';
            }

        }

        if ($iniciaProcesso && $vldIniciaProcesso){
            echo '<script>inicia_processo();</script>';
        }

    }

?>