<?php

require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModPessoa.php'; 
require_once $GLOBALS['pasta_raiz'].'/Servidor/Dao/DaoPessoa.php'; 

require_once $GLOBALS['pasta_raiz'].'/Servidor/util/funcoes.php';
require_once $GLOBALS['pasta_raiz'].'/Servidor/util/funcoesSql.php';

class ControlePessoa {

    public function listar($post){

        $retorno           = array("Ok",array(),0);
        $arrayFiltros      = array();
        $complementoOrdLim = "";

        if (isset($post['ctrConsulta'])){

            $paramsConsulta = $post['ctrConsulta'];

            if (!isset($paramsConsulta['filtros'])){
                $paramsConsulta['filtros'] = array();
            }

            if ($post['tipo'] == 'FOR'){
                array_push($paramsConsulta['filtros'], array("campo"     => 'tipo_for', 
                                                             "tipo"      => 'igual', 
                                                             "descricao" => 'Fornecedor', 
                                                             "valor"     => 'S'));
            } else if ($post['tipo'] == 'PSV'){
                array_push($paramsConsulta['filtros'], array("campo"     => 'tipo_psv', 
                                                             "tipo"      => 'igual', 
                                                             "descricao" => 'Prest.Serv.', 
                                                             "valor"     => 'S'));
            } else {
                array_push($paramsConsulta['filtros'], array("campo"     => 'tipo_fun', 
                                                             "tipo"      => 'igual', 
                                                             "descricao" => 'Funcionario', 
                                                             "valor"     => 'S'));
            }

            $filtrosPagina  = isset($paramsConsulta['filtros']) ? $paramsConsulta['filtros'] : array();

            $retornoFiltros = retornaFiltros($filtrosPagina, 'pessoa');
            
            if ($retornoFiltros[0] == "Ok"){
                $arrayFiltros = $retornoFiltros[1];
            } else {
                $retorno = $retornoFiltros;
                array_push($retorno, 0);
            }

            $complementoOrdLim = retornaOrdenacaoLimit($paramsConsulta, 'pessoa');

        }

        if ($retorno[0] == "Ok"){
            
            $dao = new DaoPessoa();
            
            $retornoDao = $dao->listar($arrayFiltros, $complementoOrdLim);
            $retorno[1] = $retornoDao[0];
            $retorno[2] = $retornoDao[1];

        }

        return $retorno;

    }

    public function consultar($dados){

        if (isset($dados['id']) && isset($dados['tipo'])){

            $pessoa = new ModPessoa($_POST['id']);
            $dao = new DaoPessoa();

            $retorno = $dao->consultar($pessoa);

            if ($retorno[0] == 'Ok' && $dados['tipo'] == 'FUN'){
                $retorno[1]['pessoaDespesas'] = $dao->listarDespesasAtivasPorPessoa($pessoa);
            }

            return $retorno;

        } else {
            return array("Erro", "Pessoa não localizada.");
        }

    }

    /**
    *
    * @param ModPessoa $pessoa 
    * @return array
    */
    public function validaPessoa(ModPessoa $pessoa){
        
        $retorno = array("Ok","Ok");

        $retorno = $retorno[0] == 'Ok' ? validaCampo('Nome',     $pessoa->getPessoaFantasia(), 'str', 'N') : $retorno;
        $retorno = $retorno[0] == 'Ok' ? validaCampo('Razão',    $pessoa->getPessoaRazao(),    'str', 'N') : $retorno;
        $retorno = $retorno[0] == 'Ok' ? validaCampo('Situação', $pessoa->getPessoaSituacao(), 'str', 'N') : $retorno;

        if (strlen(trim($pessoa->getPessoaCpfCnpj())) > 11){
            $retorno = $retorno[0] == 'Ok' ? validaCampo('Cnpj', $pessoa->getPessoaCpfCnpj(),  'cnp', 'S') : $retorno;
        } else if (strlen(trim($pessoa->getPessoaCpfCnpj())) > 0){
            $retorno = $retorno[0] == 'Ok' ? validaCampo('Cpf', $pessoa->getPessoaCpfCnpj(),  'cpf', 'S') : $retorno;
        }

        return $retorno;

    }

    public function altIncPessoa($dados){

        if (isset($dados['nome']) && 
            isset($dados['razao']) &&
            isset($dados['cpfcnpj']) &&
            isset($dados['telefone']) &&
            isset($dados['celular']) &&
            isset($dados['situacao']) &&
            isset($dados['obs']) &&
            isset($dados['tipo']) &&
            isset($dados['id'])
            ){

            $vTipoFor = $dados['tipo'] == 'FOR' ? 'S' : 'N';
            $vTipoFun = $dados['tipo'] == 'FUN' ? 'S' : 'N';
            $vTipoPsv = $dados['tipo'] == 'PSV' ? 'S' : 'N';
            $vValCred = $dados['tipo'] == 'FUN' ? $dados['valCredito'] : 0;

            $vValCred = str_replace('.','',$vValCred);
            $vValCred = str_replace(',','.',$vValCred);

            if (strlen($vValCred) == 0){
                $vValCred = 0;
            }

            $pessoa = new ModPessoa($dados['id'], $dados['nome'], $dados['razao'], $dados['telefone'],
                                    $dados['celular'], $dados['cpfcnpj'], $dados['situacao'], 
                                    $vTipoFun, $vTipoFor, $vTipoPsv, $dados['obs'], '', '', 
                                    $_SESSION['usuario']['usrId'], $_SESSION['usuario']['usrId'], $vValCred);
            
            $retorno = self::validaPessoa($pessoa);

            if($retorno[0] == 'Ok'){

                $dao = new DaoPessoa();

                if ($pessoa->getPessoaId() > 0){
                    $retorno = $dao->alterar($pessoa);
                } else {
                    $retorno = $dao->incluir($pessoa);
                }  

            }                     

            return $retorno;

        } else {
            return array("Erro", "Informações não localizadas!");
        }

    }

    public function altIncPessoaDespesa($dados){

        if (isset($dados['id']) &&
            isset($dados['idpessoa']) &&
            isset($dados['valor'])
        ){

            $retorno = array("Ok","Nenhum processo realizado!");

            $valorDespesa = str_replace('.','',$dados['valor']);
            $valorDespesa = str_replace(',','.',$dados['valor']);

            if ($valorDespesa <= 0 || $valorDespesa <= 0){
                $retorno = array("Erro", "Valor não pode estar zerado!");
            }

            $modPessoaDesp = new ModPessoaDespesa($dados['id'], $dados['idpessoa'], 0, $valorDespesa, 
                                                  'A', '', '', $_SESSION['usuario']['usrId'], $_SESSION['usuario']['usrId']);

            if ($dados['id'] == 0){
                
                if (isset($dados['iddespesa']) && $dados['iddespesa'] > 0){
                    $modPessoaDesp->setDespesa($dados['iddespesa']);
                } else {
                    $retorno = array("Erro", "Necessário preencher tipo de despesa!");
                }

            }

            if ($retorno[0] == "Ok"){

                $dao = new DaoPessoa();

                if ($dados['id'] == 0){
                    $retorno = $dao->incluirDespesaPessoa($modPessoaDesp);
                } else {
                    $retorno = $dao->alterarDespesaPessoa($modPessoaDesp);
                }

            }
            

            return $retorno;

        } else {
            return array("Erro", "Informações não localizadas!");
        }

    }

    public function inativarPessoaDespesa($dados){

        if (isset($dados['id'])){

            $modPessoaDesp = new ModPessoaDespesa($dados['id'], 0, 0, 0, 'I', '', '', 
                                                  $_SESSION['usuario']['usrId'], $_SESSION['usuario']['usrId']);

            $dao = new DaoPessoa();

            $retorno = $dao->alterarDespesaPessoa($modPessoaDesp);
            
            return $retorno;

        } else {
            return array("Erro", "Informações não localizadas!");
        }

    }

    public function listarAtivos($post){

        $retorno           = array("Ok",array(),0);
        $arrayFiltros      = array();
        $complementoOrdLim = "";

        if (isset($post['ctrConsulta'])){

            $paramsConsulta = $post['ctrConsulta'];

            if (!isset($paramsConsulta['filtros'])){
                $paramsConsulta['filtros'] = array();
            }

            $filtrosPagina  = isset($paramsConsulta['filtros']) ? $paramsConsulta['filtros'] : array();

            $retornoFiltros = retornaFiltros($filtrosPagina, 'pessoa');
            
            if ($retornoFiltros[0] == "Ok"){
                $arrayFiltros = $retornoFiltros[1];
            } else {
                $retorno = $retornoFiltros;
                array_push($retorno, 0);
            }

            $complementoOrdLim = retornaOrdenacaoLimit($paramsConsulta, 'pessoa');

        }

        if ($retorno[0] == "Ok"){
            
            $dao = new DaoPessoa();
            
            $retornoDao = $dao->listar($arrayFiltros, $complementoOrdLim);
            $retorno[1] = $retornoDao[0];
            $retorno[2] = $retornoDao[1];

        }

        return $retorno;

    }

}

?>