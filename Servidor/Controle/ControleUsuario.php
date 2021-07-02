<?php

require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModUsuario.php'; 
require_once $GLOBALS['pasta_raiz'].'/Servidor/Dao/DaoUsuario.php'; 

require_once $GLOBALS['pasta_raiz'].'/Servidor/util/funcoesSql.php';

class ControleUsuario {

    public function entrarSistema($dadosLogin){

        if (isset($dadosLogin['usu']) && isset($dadosLogin['sen'])){

            $usuario = new ModUsuario(0, $_POST['usu'], '', $_POST['sen']);
            $daoUsuario = new DaoUsuario();

            $retornoValida = $daoUsuario->consultar($usuario);

            if($retornoValida[0] == "Ok"){

                if(!isset($_SESSION)) { 
                    session_start(); 
                }

                $retornoValida[1]['chaveAcesso'] = md5('seg'.$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
                $_SESSION['usuario'] = $retornoValida[1];

            }

            return $retornoValida;

        } else {
            return array("Erro", "Usuário ou senha não localizados!");
        }

    }

    public function listarUsuarios($post){

        $retorno           = array("Ok",array(),0);
        $arrayFiltros      = array();
        $complementoOrdLim = "";

        if (isset($post['ctrConsulta'])){

            $paramsConsulta = $post['ctrConsulta'];
            $filtrosPagina  = isset($paramsConsulta['filtros']) ? $paramsConsulta['filtros'] : array();

            $retornoFiltros = retornaFiltros($filtrosPagina, 'usuario');
            
            if ($retornoFiltros[0] == "Ok"){
                $arrayFiltros = $retornoFiltros[1];
            } else {
                $retorno = $retornoFiltros;
                array_push($retorno, 0);
            }

            $complementoOrdLim = retornaOrdenacaoLimit($paramsConsulta, 'usuario');

        }

        if ($retorno[0] == "Ok"){
            $daoUsuario = new DaoUsuario();
            $retornoDao = $daoUsuario->listarUsuarios($arrayFiltros, $complementoOrdLim);
            $retorno[1] = $retornoDao[0];
            $retorno[2] = $retornoDao[1];
        }

        return $retorno;

    }

    public function consultarUsuario($dadosUser){

        if (isset($dadosUser['id'])){

            $usuario = new ModUsuario($_POST['id']);
            $daoUsuario = new DaoUsuario();

            $retornoValida = $daoUsuario->consultar($usuario);

            return $retornoValida;

        } else {
            return array("Erro", "Usuário não localizado!");
        }

    }

    public function validaUsuario(ModUsuario $usuario){
        
        $retorno = array("Ok","Ok");

        if (strlen($usuario->getUsrLogin()) <= 0){
            $retorno = array("Erro", "Login inválido!");
        } else if ($usuario->getUsrSituacao() != 'A' && $usuario->getUsrSituacao() != 'I'){
            $retorno = array("Erro", "Situação inválida!");
        } else if ($usuario->getUsrId() <= 0 && strlen($usuario->getUsrSenha()) <= 0){
            $retorno = array("Erro", "Necessário preencher senha!");
        }

        return $retorno;

    }

    public function altIncUsuario($dadosUsuario){

        if (isset($dadosUsuario['login']) && 
            isset($dadosUsuario['email']) &&
            isset($dadosUsuario['situacao']) &&
            isset($dadosUsuario['id'])
            ){

            $senha = '';

            if (isset($dadosUsuario['sen'])){
                $senha = trim($dadosUsuario['sen']);
            }

            $usuario = new ModUsuario($dadosUsuario['id'], $dadosUsuario['login'], $dadosUsuario['email'], $senha, $dadosUsuario['situacao'], '', '', $_SESSION['usuario']['usrId'], $_SESSION['usuario']['usrId']);
            
            $retorno = self::validaUsuario($usuario);

            if($retorno[0] == 'Ok'){

                $daoUsuario = new DaoUsuario();

                if ($usuario->getUsrId() > 0){

                    $retorno = $daoUsuario->alterarUsuario($usuario);

                    if($retorno[0] == 'Ok'){

                        if (isset($dadosUsuario['acessos'])){

                            $listaAcessos = array();

                            foreach ($dadosUsuario['acessos'] as $acesso){
                                $modAcesso = new ModUsuarioAcesso($acesso['id'], '', '', $acesso['valor']);
                                array_push($listaAcessos, $modAcesso);
                            }

                            $daoUsuario->alteraPermissao($listaAcessos);

                        }

                    }

                } else {
                    $retorno = $daoUsuario->incluirUsuario($usuario);
                }  

            }                     

            return $retorno;

        } else {
            return array("Erro", "Informações do usuário não localizadas!");
        }

    }

}

?>