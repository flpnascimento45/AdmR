<?php

require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModFechto.php'; 
require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModFechtoPessoa.php'; 
require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModPessoa.php'; 
require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModDespesa.php'; 
require_once $GLOBALS['pasta_raiz'].'/Servidor/Dao/DaoFechto.php'; 
require_once $GLOBALS['pasta_raiz'].'/Servidor/Dao/DaoPessoa.php'; 
require_once $GLOBALS['pasta_raiz'].'/Servidor/Dao/DaoDespesa.php'; 

require_once $GLOBALS['pasta_raiz'].'/Servidor/util/funcoes.php';
require_once $GLOBALS['pasta_raiz'].'/Servidor/util/funcoesSql.php';

class ControleFechamento {

    public function listar($post){

        $retorno           = array("Ok",array(),0);
        $arrayFiltros      = array();
        $complementoOrdLim = "";

        if (isset($post['ctrConsulta'])){

            $paramsConsulta = $post['ctrConsulta'];
            $filtrosPagina  = isset($paramsConsulta['filtros']) ? $paramsConsulta['filtros'] : array();

            $retornoFiltros = retornaFiltros($filtrosPagina, 'fechamento');
            
            if ($retornoFiltros[0] == "Ok"){
                $arrayFiltros = $retornoFiltros[1];
            } else {
                $retorno = $retornoFiltros;
                array_push($retorno, 0);
            }

            $complementoOrdLim = retornaOrdenacaoLimit($paramsConsulta, 'fechamento');

        }

        if ($retorno[0] == "Ok"){
            $dao = new DaoFechto();
            $retornoDao = $dao->listar($arrayFiltros, $complementoOrdLim);
            $retorno[1] = $retornoDao[0];
            $retorno[2] = $retornoDao[1];
        }

        return $retorno;

    }

    public function consultar($dados){

        if (isset($dados['id'])){

            $fechto = new ModFechto($dados['id']);
            $dao    = new DaoFechto();

            $retorno = $dao->consultar($fechto);
            
            return $retorno;

        } else {
            return array("Erro", "Fechamento não localizado!");
        }

    }

    public function altIncFechto($dados){

        if (isset($dados['data']) && 
            isset($dados['id']) && 
            isset($dados['descricao'])
            ){

            $retorno = validaCampo('Data', $dados['data'], 'dat', 'N');
            $retorno = $retorno[0] == "Ok" ? validaCampo('Descrição', $dados['descricao'], 'str', 'N') : "";

            if($retorno[0] == 'Ok'){

                $fec = new ModFechto($dados['id'], $dados['descricao'], $dados['data'], '', '', '', $_SESSION['usuario']['usrId']);

                $dao = new DaoFechto();

                if ($fec->getFechtoId() > 0){

                    $retorno = $dao->consultar($fec, true);

                    if ($retorno[0] == "Ok"){
                        
                        $fec = $retorno[1];

                        if ($fec->getFechtoSit() == 'A'){

                            $fec->setFechtoData($dados['data'])
                                ->setFechtoDescricao($dados['descricao'])
                                ->setUsrAlt($_SESSION['usuario']['usrId']);

                            $retorno = $dao->alterar($fec);

                        } else {
                            $retorno = array("Erro","Situação do fechamento inválida!");
                        }

                    }

                } else {
                    $retorno = $dao->incluir($fec);
                }  

            }                     

            return $retorno;

        } else {
            return array("Erro", "Informações do fechamento não localizadas!");
        }

    }

    public function cancelaFechto($dados){

        if (isset($dados['id'])){

            $fec = new ModFechto($dados['id']);

            $dao = new DaoFechto();

            if ($fec->getFechtoId() > 0){

                $retorno = $dao->consultar($fec, true);

                if ($retorno[0] == "Ok"){
                    
                    $fec = $retorno[1];

                    if ($fec->getFechtoSit() == 'A'){

                        $fec->setFechtoSit('X')
                            ->setUsrAlt($_SESSION['usuario']['usrId']);

                        $retorno = $dao->alterar($fec);

                        $retorno = $retorno[0] == "Ok" ? array("Ok", "Fechamento cancelado!") : $retorno;

                    } else {
                        $retorno = array("Erro","Situação do fechamento inválida!");
                    }

                }

            } else {
                $retorno = array("Erro", "Fechamento não localizado.");
            }               

            return $retorno;

        } else {
            return array("Erro", "Informações do fechamento não localizadas!");
        }

    }

    public function listarPessoasDespesas($dados){

        if (isset($dados['despesas']) && isset($dados['idfechto'])){

            $despesasFil = $dados['despesas'];

            $valida = true;

            foreach($despesasFil as $desp){
                if (!is_numeric($desp)){
                    $valida = false;
                }
            }

            if ($valida && count($despesasFil) > 0){

                $retorno = array();

                $despesasPonto = array();
                $despesasFunc  = array();

                $daoDesp = new DaoDespesa();

                foreach($despesasFil as $desp){

                    $modDesp = new ModDespesa($desp);

                    $retDesp = $daoDesp->consultar($modDesp);

                    if ($retDesp[1]['despTipo'] == 'FUN'){
                        array_push($despesasFunc, $desp);
                    } else if ($retDesp[1]['despTipo'] == 'PON'){
                        array_push($despesasPonto, $desp);
                    }

                }

                $dao       = new DaoPessoa();
                $modPessoa = new ModPessoa();

                if (count($despesasFunc) > 0){

                    $modPessoa->setPessoaDespesas($despesasFunc);

                    $retorno = array_merge($retorno, $dao->listarPessoasDespesas($modPessoa, 'FUN', $dados['idfechto']));

                }

                if (count($despesasPonto) > 0){

                    $modPessoa->setPessoaDespesas($despesasPonto);

                    $retornoPonto = $dao->listarPessoasDespesas($modPessoa, 'PON', $dados['idfechto']);

                    $arrayAddRetorno = array();

                    foreach($retornoPonto as $pessoaRetPon){

                        $vAchou = false;
                        
                        for($i = 0; $i < count($retorno); $i++){
                            
                            if ($pessoaRetPon['pessoaId'] == $retorno[$i]['pessoaId']){
                                $retorno[$i]['pessoaDespesas'] = array_merge($retorno[$i]['pessoaDespesas'], $pessoaRetPon['pessoaDespesas']);
                                $vAchou = true;
                            } 

                        }

                        if (!$vAchou){
                            array_push($arrayAddRetorno, $pessoaRetPon);
                        }

                    }

                    $retorno = array_merge($retorno, $arrayAddRetorno);

                }

                return array("Ok", $retorno);
                
            } else {
                return array("Erro", "Despesas inválidas ou não localizadas!");
            }

        } else {
            return array("Erro", "Informações das despesas não localizadas!");
        }

    }

    public function incluirFechtoPessoa($dados){

        if (isset($dados['fechamento']) && 
            isset($dados['funcionario']) &&
            isset($dados['despesas']) &&
            isset($dados['valorpago'])
            ){

            $retorno = validaCampo('Fechamento', $dados['fechamento'], 'num', 'N');
            $retorno = $retorno[0] == "Ok" ? validaCampo('Funcionário', $dados['funcionario'], 'num', 'N') : "";
            $retorno = $retorno[0] == "Ok" ? validaCampo('Valor Pago', $dados['valorpago'], 'num', 'S') : "";
            
            if (count($dados['despesas']) <= 0){
                $retorno = array("Erro", "Nenhuma despesa localizada!");
            } else {

                for ($i = 0; $i < count($dados['despesas']); $i++){
                    $retorno = $retorno[0] == "Ok" ? validaCampo('Despesa', $dados['despesas'][$i]['despesa'], 'num', 'N') : "";
                    $retorno = $retorno[0] == "Ok" ? validaCampo('Valor Despesa', $dados['despesas'][$i]['valor'], 'num', 'S') : "";
                }

            }

            if($retorno[0] == 'Ok'){

                $valorDespesas = 0;

                for ($i = 0; $i < count($dados['despesas']); $i++){
                    $valorDespesas += $dados['despesas'][$i]['valor'];
                }

                $modPessoa = new ModPessoa($dados['funcionario']);
                $daoPessoa = new DaoPessoa();
                $retPessoa = $daoPessoa->consultar(($modPessoa));

                $valorCredFunc = $retPessoa[1]['pessoaValCredito'];

                $valorGerCred = $valorDespesas - $dados['valorpago'];

                $modFechtoPessoa = new ModFechtoPessoa(0, $dados['fechamento'], $dados['funcionario'], 
                        0, $valorCredFunc, $valorDespesas, $valorGerCred, $dados['valorpago'], '', '', 
                        $_SESSION['usuario']['usrId'], 0, $dados['despesas']);

                $daoFechto = new DaoFechto();

                $retorno = $daoFechto->incluirFechtoPessoa($modFechtoPessoa);

            }                     

            return $retorno;

        } else {
            return array("Erro", "Informações do fechamento de despesa não localizadas!");
        }

    }

    public function listarFechamentoPessoa($dados){

        if (isset($dados['id'])){

            $fechto = new ModFechto($dados['id']);
            $dao    = new DaoFechto();

            $retorno = $dao->listarFechamentoPessoa($fechto);
            
            return $retorno;

        } else {
            return array("Erro", "Fechamento não localizado!");
        }

    }

    public function deletarFechtoPessoa($dados){

        if (isset($dados['id']) && isset($dados['idfec'])){

            $fechto = new ModFechto($dados['id']);
            $dao    = new DaoFechto();

            $retFechto = $dao->consultar($fechto);

            if ($retFechto[1]['fechtoSit'] == 'A'){

                $fechtoPes = new ModFechtoPessoa($dados['idfec']);

                $retorno = $dao->deletarFechtoPessoa($fechtoPes);

            } else {
                return array("Erro", "Situação do fechamento inválida!");
            }
            
            return $retorno;

        } else {
            return array("Erro", "Fechamento não localizado!");
        }

    }

    public function finalizarFechto($dados){

        if (isset($dados['id'])){

            $fechto = new ModFechto($dados['id']);
            $fechto->setUsrAlt($_SESSION['usuario']['usrId'])
                   ->setUsrInc($_SESSION['usuario']['usrId']);
                   
            $dao    = new DaoFechto();

            $retFechto = $dao->consultar($fechto);

            if ($retFechto[1]['fechtoSit'] == 'A'){

                $retorno = $dao->finalizarFechto($fechto);

            } else {
                return array("Erro", "Situação do fechamento inválida!");
            }
            
            return $retorno;

        } else {
            return array("Erro", "Fechamento não localizado!");
        }

    }

}

?>