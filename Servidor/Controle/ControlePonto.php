<?php

require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModPonto.php'; 
require_once $GLOBALS['pasta_raiz'].'/Servidor/Dao/DaoPonto.php'; 

require_once $GLOBALS['pasta_raiz'].'/Servidor/util/funcoes.php';
require_once $GLOBALS['pasta_raiz'].'/Servidor/util/funcoesSql.php';

class ControlePonto {

    public function listar($post){

        $retorno           = array("Ok",array(),0);
        $arrayFiltros      = array();
        $complementoOrdLim = "";

        if (isset($post['ctrConsulta'])){

            $paramsConsulta = $post['ctrConsulta'];
            $filtrosPagina  = isset($paramsConsulta['filtros']) ? $paramsConsulta['filtros'] : array();

            $retornoFiltros = retornaFiltros($filtrosPagina, 'ponto');
            
            if ($retornoFiltros[0] == "Ok"){
                $arrayFiltros = $retornoFiltros[1];
            } else {
                $retorno = $retornoFiltros;
                array_push($retorno, 0);
            }

            $complementoOrdLim = retornaOrdenacaoLimit($paramsConsulta, 'ponto');

        }

        if ($retorno[0] == "Ok"){
            $dao = new DaoPonto();
            $retornoDao = $dao->listar($arrayFiltros, $complementoOrdLim);
            $retorno[1] = $retornoDao[0];
            $retorno[2] = $retornoDao[1];
        }

        return $retorno;

    }

    public function consultar($dados){

        if (isset($dados['id'])){

            $ponto = new ModPonto($dados['id']);
            $dao = new DaoPonto();

            $retorno = $dao->consultar($ponto);

            if ($retorno[1]['pontoSituacao'] == 'F'){
                $retorno[1]['pontoPessoas'] = $dao->listarPontoPessoa($ponto);
            }
            
            return $retorno;

        } else {
            return array("Erro", "Ponto não localizado!");
        }

    }

    public function altIncPonto($dados){

        if (isset($dados['data']) && 
            isset($dados['id'])
            ){

            $retorno = validaCampo('Data', $dados['data'], 'dat', 'N');

            if($retorno[0] == 'Ok'){

                $pon = new ModPonto($dados['id'], $dados['data'], 'A', '', '', $_SESSION['usuario']['usrId'], $_SESSION['usuario']['usrId']);

                $dao = new DaoPonto();

                if ($pon->getPontoId() > 0){
                    $retorno = $dao->alterar($pon);
                } else {
                    $retorno = $dao->incluir($pon);
                }  

            }                     

            return $retorno;

        } else {
            return array("Erro", "Informações do ponto não localizadas!");
        }

    }

    public function confirmaPonto($dados){

        if (isset($dados['arrPonto']) && 
            isset($dados['id'])
            ){

            $retorno = array("Ok", "Ponto confirmado!");

            if (count($dados['arrPonto']) <= 0){
                $retorno = array("Erro", "Falha ao localizar registros de ponto.");
            }

            $dao      = new DaoPonto();
            $modPonto = new ModPonto($dados['id']);

            $retObjPonto = $dao->consultar($modPonto);

            $modPonto->setPontoSituacao($retObjPonto[1]['pontoSituacao'])
                     ->setPontoData($retObjPonto[1]['pontoData'])
                     ->setUsrAlt($_SESSION['usuario']['usrId']);

            if ($retObjPonto[1]['pontoSituacao'] != 'A'){
                $retorno = array("Erro", "Situação do lote inválida! Sit.: ".$retObjPonto[1]['pontoSituacao']);
            }
            
            if($retorno[0] == 'Ok'){

                $arrayPontos = $dados['arrPonto'];

                foreach($arrayPontos as $ponto){
                    
                    $newPonto = new ModPontoPessoa(0, $ponto['pessoa'], $dados['id'], 
                                                  ($ponto['presente'] ? 1 : 0), '', '', $_SESSION['usuario']['usrId']);
                    
                    $retPon = $dao->incluirPontoPessoa($newPonto);

                    if ($retPon[0] == "Erro"){
                        $retorno = array("Erro", "Atenção! Ponto pessoa id ".$ponto['pessoa']." não confirmado. Lote cancelado!", $retPon[1]);
                    }

                }

                if ($retorno[0] == 'Ok'){

                    $modPonto->setPontoSituacao('F');
    
                    $dao->alterar($modPonto);
                    
                } else {
    
                    $dao->cancelarLote($modPonto);
    
                }
                
            }

            return $retorno;

        } else {
            return array("Erro", "Informações do ponto não localizadas!");
        }

    }

}

?>