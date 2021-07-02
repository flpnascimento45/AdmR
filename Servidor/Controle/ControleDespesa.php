<?php

require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModDespesa.php'; 
require_once $GLOBALS['pasta_raiz'].'/Servidor/Dao/DaoDespesa.php'; 

require_once $GLOBALS['pasta_raiz'].'/Servidor/util/funcoesSql.php';

class ControleDespesa {

    public function listar($post){

        $retorno           = array("Ok",array(),0);
        $arrayFiltros      = array();
        $complementoOrdLim = "";

        if (isset($post['ctrConsulta'])){

            $paramsConsulta = $post['ctrConsulta'];
            $filtrosPagina  = isset($paramsConsulta['filtros']) ? $paramsConsulta['filtros'] : array();

            $retornoFiltros = retornaFiltros($filtrosPagina, 'despesa');
            
            if ($retornoFiltros[0] == "Ok"){
                $arrayFiltros = $retornoFiltros[1];
            } else {
                $retorno = $retornoFiltros;
                array_push($retorno, 0);
            }

            $complementoOrdLim = retornaOrdenacaoLimit($paramsConsulta, 'despesa');

        }

        if ($retorno[0] == "Ok"){
            $dao = new DaoDespesa();
            $retornoDao = $dao->listar($arrayFiltros, $complementoOrdLim);
            $retorno[1] = $retornoDao[0];
            $retorno[2] = $retornoDao[1];
        }

        return $retorno;

    }

    public function consultar($dados){

        if (isset($dados['id'])){

            $despesa = new ModDespesa($dados['id']);
            $dao = new DaoDespesa();

            $retorno = $dao->consultar($despesa);

            return $retorno;

        } else {
            return array("Erro", "Despesa não localizada!");
        }

    }

    public function validaDespesa(ModDespesa $despesa){
        
        $retorno = array("Ok","Ok");

        if (strlen($despesa->getDespNome()) <= 0){
            $retorno = array("Erro", "Necessário preencher nome!");
        } else if ($despesa->getDespSit() != 'A' && $despesa->getDespSit() != 'I'){
            $retorno = array("Erro", "Situação inválida!");
        } else if ($despesa->getDespTipo() != 'FUN' && $despesa->getDespTipo() != 'PON' && $despesa->getDespTipo() != 'OUT'){
            $retorno = array("Erro", "Tipo inválido!");
        }

        return $retorno;

    }

    public function altIncDespesa($dados){

        if (isset($dados['nome']) && 
            isset($dados['valor']) &&
            isset($dados['situacao']) &&
            isset($dados['tipo']) &&
            isset($dados['id'])
            ){

            $despesa = new ModDespesa($dados['id'], $dados['nome'], str_replace(',','.',$dados['valor']), 
                                      $dados['situacao'], $dados['tipo'], '', '', 
                                      $_SESSION['usuario']['usrId'], $_SESSION['usuario']['usrId']);
            
            $retorno = self::validaDespesa($despesa);

            if($retorno[0] == 'Ok'){

                $dao = new DaoDespesa();

                if ($despesa->getDespId() > 0){
                    $retorno = $dao->alterar($despesa);
                } else {
                    $retorno = $dao->incluir($despesa);
                }  

            }                     

            return $retorno;

        } else {
            return array("Erro", "Informações da despesa não localizadas!");
        }

    }

}

?>