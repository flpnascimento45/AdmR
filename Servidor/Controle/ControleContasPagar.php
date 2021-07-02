<?php

require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModContasPagar.php'; 
require_once $GLOBALS['pasta_raiz'].'/Servidor/Dao/DaoContasPagar.php'; 

require_once $GLOBALS['pasta_raiz'].'/Servidor/util/funcoesSql.php';
require_once $GLOBALS['pasta_raiz'].'/Servidor/util/funcoes.php';

class ControleContasPagar {

    public function listar($post){

        $retorno           = array("Ok",array(),0);
        $arrayFiltros      = array();
        $complementoOrdLim = "";

        if (isset($post['ctrConsulta'])){

            $paramsConsulta = $post['ctrConsulta'];
            $filtrosPagina  = isset($paramsConsulta['filtros']) ? $paramsConsulta['filtros'] : array();

            $retornoFiltros = retornaFiltros($filtrosPagina, 'v_contas_pagar');
            
            if ($retornoFiltros[0] == "Ok"){
                $arrayFiltros = $retornoFiltros[1];
            } else {
                $retorno = $retornoFiltros;
                array_push($retorno, 0);
            }

            $complementoOrdLim = retornaOrdenacaoLimit($paramsConsulta, 'v_contas_pagar');

        }

        if ($retorno[0] == "Ok"){
            $dao = new DaoContasPagar();
            $retornoDao = $dao->listar($arrayFiltros, $complementoOrdLim);
            $retorno[1] = $retornoDao[0];
            $retorno[2] = $retornoDao[1];
        }

        return $retorno;

    }

    public function consultar($dados){

        if (isset($dados['id'])){

            $pagar = new ModContasPagar($dados['id']);
            $dao   = new DaoContasPagar();

            $retorno = $dao->consultar($pagar);

            return $retorno;

        } else {
            return array("Erro", "Conta não localizada!");
        }

    }

    public function validaConta(ModContasPagar $pagar){
        
        $retorno = validaCampo('Favorecido', $pagar->getPessoa(), 'num', 'N');
        $retorno = $retorno[0] == 'Ok' ? validaCampo('Despesa',     $pagar->getDespesa(),        'num', 'N') : $retorno;
        $retorno = $retorno[0] == 'Ok' ? validaCampo('Situação',    $pagar->getPagarSituacao(),  'str', 'N') : $retorno;
        $retorno = $retorno[0] == 'Ok' ? validaCampo('Data Vencto', $pagar->getPagarDtVencto(),  'dat', 'N') : $retorno;
        $retorno = $retorno[0] == 'Ok' ? validaCampo('Data Docto',  $pagar->getPagarDtDocto(),   'dat', 'N') : $retorno;
        $retorno = $retorno[0] == 'Ok' ? validaCampo('Valor',       $pagar->getPagarValor(),     'num', 'N') : $retorno;
        $retorno = $retorno[0] == 'Ok' ? validaCampo('Valor Pago',  $pagar->getPagarValorPago(), 'num', 'S') : $retorno;
        $retorno = $retorno[0] == 'Ok' ? validaCampo('Sequencia',   $pagar->getPagarSeq(),       'int', 'N') : $retorno;
        
        if ($pagar->getPagarSituacao() == 'P'){
            $retorno = $retorno[0] == 'Ok' ? validaCampo('Data Pagto',  $pagar->getPagarDtPagto(),   'dat', 'N') : $retorno;
        } else {
            $retorno = $retorno[0] == 'Ok' ? validaCampo('Data Pagto',  $pagar->getPagarDtPagto(),   'dat', 'S') : $retorno;
        }

        return $retorno;

    }

    public function alteraConta($dados){

        if (isset($dados['pessoa']) && 
            isset($dados['despesa']) &&
            isset($dados['situacao']) &&
            isset($dados['vencto']) &&
            isset($dados['docto']) &&
            isset($dados['pagto']) &&
            isset($dados['valor']) &&
            isset($dados['valorpago']) &&
            isset($dados['numdoc']) &&
            isset($dados['obs']) &&
            isset($dados['id'])
            ){

            $conta = new ModContasPagar($dados['id'], $dados['pessoa'], $dados['despesa'], $dados['situacao'],
                                        $dados['vencto'], $dados['pagto'], $dados['docto'], $dados['valorpago'],
                                        $dados['valor'], $dados['numdoc'], 1, $dados['obs'], '', '',
                                        $_SESSION['usuario']['usrId'], $_SESSION['usuario']['usrId']);
            
            $retorno = self::validaConta($conta);

            if($retorno[0] == 'Ok'){
                $dao = new DaoContasPagar();
                $retorno = $dao->alterar($conta);
            }                     

            return $retorno;

        } else {
            return array("Erro", "Informações da conta não localizadas!");
        }

    }

    public function incluirConta($dados){

        if (isset($dados['pessoa']) && 
            isset($dados['despesa']) &&
            isset($dados['docto']) &&
            isset($dados['numdoc']) &&
            isset($dados['obs']) &&
            isset($dados['id']) &&
            isset($dados['arrayContas'])
        ){

            $retorno = array("Ok", "");

            if (count($dados['arrayContas']) <= 0){
                $retorno = array("Erro", "Parcelas não localizadas!");
            } else {

                foreach($dados['arrayContas'] as $conta){

                    $conta = new ModContasPagar(0, $dados['pessoa'], $dados['despesa'], $conta['situacao'],
                                                $conta['vencto'], $conta['pagto'], $dados['docto'], $conta['valorpago'],
                                                $conta['valor'], $dados['numdoc'], 1, $dados['obs'], '', '',
                                                $_SESSION['usuario']['usrId'], $_SESSION['usuario']['usrId']);
            
                    $retorno = $retorno[0] == 'Ok' ? self::validaConta($conta) : $retorno;
                    
                }

                if($retorno[0] == 'Ok'){

                    $dao = new DaoContasPagar();

                    $retornoId = 0;

                    foreach($dados['arrayContas'] as $conta){

                        $conta = new ModContasPagar(0, $dados['pessoa'], $dados['despesa'], $conta['situacao'],
                                                    $conta['vencto'], $conta['pagto'], $dados['docto'], $conta['valorpago'],
                                                    $conta['valor'], $dados['numdoc'], 1, $dados['obs'], '', '',
                                                    $_SESSION['usuario']['usrId'], $_SESSION['usuario']['usrId']);
                        
                        $retorno = $retorno[0] == 'Ok' ? $dao->incluir($conta) : $retorno;

                        $retornoId = $retornoId > 0 ? $retornoId : $retorno[2];

                    }

                    if($retorno[0] == 'Ok'){
                        $retorno[2] = $retornoId;
                    }

                }

            }

            return $retorno;

        } else {
            return array("Erro", "Informações da conta não localizadas!");
        }

    }

}

?>