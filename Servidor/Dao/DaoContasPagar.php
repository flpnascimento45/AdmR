<?php

require_once $GLOBALS['pasta_raiz'].'/Servidor/util/conexao.php'; 
require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModFiltro.php';
require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModContasPagar.php';

class DaoContasPagar {

    public function consultar(ModContasPagar $pagar){
                
        $con = Conexao::getInstance();
        
        $sql = "select pagar_id, pessoa_id, desp_id, pagar_situacao, pagar_data_vencto, ".
               " pagar_data_pagto, pagar_data_docto, pagar_num_doc, pagar_sequencia, ".
               " coalesce(pagar_valor_pago,0) as pagar_valor_pago, pagar_valor, ".
               " pagar_obs, data_inclusao, data_alteracao, usr_inclusao, usr_alteracao ".
               " from contas_pagar ".
               " where pagar_id = :id;";
              
        $rs = $con->prepare($sql);

        $rs->bindValue(':id', $pagar->getPagarId(), PDO::PARAM_STR);
        
        $rs->execute();
        
        $consulta = array("Erro","Despesa não localizada!");

        while($row = $rs->fetch(PDO::FETCH_OBJ)){

            $pagRet = new ModContasPagar($row->pagar_id, $row->pessoa_id, $row->desp_id, $row->pagar_situacao,
                                         $row->pagar_data_vencto, $row->pagar_data_pagto, $row->pagar_data_docto, 
                                         $row->pagar_valor_pago, $row->pagar_valor,
                                         $row->pagar_num_doc,$row->pagar_sequencia, $row->pagar_obs, 
                                         $row->data_inclusao, $row->data_alteracao, $row->usr_inclusao, $row->usr_alteracao);

            $consulta = array("Ok", $pagRet->retornaArray());

        }
        
        return $consulta;
                
    }

    /**
    *
    * @param ModFiltro[] $arrayFiltros 
    * @return array
    */
    public function listar($arrayFiltros, $complementoOrdLim){
                
        $con = Conexao::getInstance();
        
        $sql = "select pagar_id, pessoa_fantasia, desp_nome, pagar_situacao, pagar_data_vencto, ".
               " pagar_data_pagto, pagar_data_docto, pagar_num_doc, pagar_sequencia, ".
               " pagar_valor_pago, pagar_valor, ".
               " pagar_obs, data_inclusao, data_alteracao, usr_inclusao, usr_alteracao ".
               " from v_contas_pagar ";
        
        $sqlConta = "select count(pagar_id) as total ".
                    " from v_contas_pagar ";

        $contFil = 0;

        foreach($arrayFiltros as $filtro){
            $retSqlFil = ($contFil == 0 ? ' where ' : ' and ').$filtro->adicionaWhere();
            $sql      .= $retSqlFil;
            $sqlConta .= $retSqlFil;
            $contFil++;
        }
        
        $sql .= $complementoOrdLim.";";

        $rs = $con->prepare($sql);

        foreach($arrayFiltros as $filtro){
            $filtro->bindSql($rs);
        }

        $rs->execute();
        
        $consulta = array();

        while($row = $rs->fetch(PDO::FETCH_OBJ)){

            $pagRet = new ModContasPagar($row->pagar_id, $row->pessoa_fantasia, $row->desp_nome, $row->pagar_situacao,
                                         $row->pagar_data_vencto, $row->pagar_data_pagto, $row->pagar_data_docto,
                                         $row->pagar_valor_pago, $row->pagar_valor,
                                         $row->pagar_num_doc,$row->pagar_sequencia, $row->pagar_obs, 
                                         $row->data_inclusao, $row->data_alteracao, $row->usr_inclusao, $row->usr_alteracao);

            array_push($consulta, $pagRet->retornaArray());

        }

        /* contagem numero de registros */
        $rsConta = $con->prepare($sqlConta);

        foreach($arrayFiltros as $filtro){
            $filtro->bindSql($rsConta);
        }

        $rsConta->execute();
        
        $numRegistrosSql = 0;

        while($row = $rsConta->fetch(PDO::FETCH_OBJ)){
            $numRegistrosSql = $row->total;
        }

        // array_push($consulta, $sql);
        return array($consulta, $numRegistrosSql);
                
    }

    /**
    *
    * @param ModContasPagar $pagar 
    * @return array
    */
    public function alterar(ModContasPagar $pagar){
        
        $retorno = array("Erro", "Nenhum processo realizado");
        
        try{

            $con = Conexao::getInstance();

            $sql = "update contas_pagar ".
                    " set pessoa_id         = :pessoa, ".
                    "     desp_id           = :despesa, ".
                    "     pagar_situacao    = :situacao, ".
                    "     pagar_data_vencto = :datavencto, ".
                    "     pagar_data_docto  = :datadocto, ".
                    "     pagar_data_pagto  = :datapagto, ".
                    "     pagar_valor       = :valor, ".
                    "     pagar_valor_pago  = :valorpago, ".
                    "     pagar_num_doc     = :numdoc, ".
                    "     pagar_obs         = :obs, ".
                    "     usr_alteracao     = :usralt, ".
                    "     data_alteracao    = NOW() ".
                    " where pagar_id = :id;";

            $rs = $con->prepare($sql);
            $rs->bindValue(':pessoa',     $pagar->getPessoa(),        PDO::PARAM_STR);
            $rs->bindValue(':despesa',    $pagar->getDespesa(),       PDO::PARAM_STR);
            $rs->bindValue(':situacao',   $pagar->getPagarSituacao(), PDO::PARAM_STR);
            $rs->bindValue(':datavencto', $pagar->getPagarDtVencto(), PDO::PARAM_STR);
            $rs->bindValue(':datadocto',  $pagar->getPagarDtDocto(),  PDO::PARAM_STR);
            $rs->bindValue(':valor',      $pagar->getPagarValor(),    PDO::PARAM_STR);
            $rs->bindValue(':valorpago',  $pagar->getPagarValorPago(),PDO::PARAM_STR);
            $rs->bindValue(':numdoc',     $pagar->getPagarNumDoc(),   PDO::PARAM_STR);
            $rs->bindValue(':obs',        $pagar->getPagarObs(),      PDO::PARAM_STR);
            $rs->bindValue(':usralt',     $pagar->getUsrAlt(),        PDO::PARAM_STR);
            $rs->bindValue(':id',         $pagar->getPagarId(),       PDO::PARAM_STR);
            
            if (strlen($pagar->getPagarDtPagto()) > 0){
                $rs->bindValue(':datapagto',  $pagar->getPagarDtPagto(),  PDO::PARAM_STR);
            } else {
                $rs->bindValue(':datapagto', NULL, PDO::PARAM_INT);
            }

            $rs->execute();

            $retorno = array ("Ok", "Conta alterada!", $pagar->getPagarId());

        } catch (Exception $e){
            $retorno = array("Erro", "Falha ao realizar procedimento => ".$e->getMessage());
        }
        
        return $retorno;

    }

    /**
    *
    * @param ModContasPagar $pagar 
    * @return array
    */
    public function incluir(ModContasPagar $pagar){
        
        $retorno = array("Erro", "Nenhum processo realizado");
        
        try{

            $con = Conexao::getInstance();

            $sql = "INSERT INTO contas_pagar (pessoa_id, desp_id, pagar_situacao, ".
                   " pagar_data_vencto, pagar_data_pagto, pagar_data_docto, ".
                   " pagar_valor, pagar_valor_pago, pagar_num_doc, pagar_sequencia, ".
                   " pagar_obs, data_inclusao, usr_inclusao) values (".
                   " :pessoa_id, :desp_id, :pagar_situacao, ".
                   " :pagar_data_vencto, :pagar_data_pagto, :pagar_data_docto, ".
                   " :pagar_valor, :pagar_valor_pago, :pagar_num_doc, :pagar_sequencia, ".
                   " :pagar_obs, NOW(), :usr_inc);";

            $rs = $con->prepare($sql);
            $rs->bindValue(':pessoa_id',         $pagar->getPessoa(),        PDO::PARAM_STR);
            $rs->bindValue(':desp_id',           $pagar->getDespesa(),       PDO::PARAM_STR);
            $rs->bindValue(':pagar_situacao',    $pagar->getPagarSituacao(), PDO::PARAM_STR);
            $rs->bindValue(':pagar_data_vencto', $pagar->getPagarDtVencto(), PDO::PARAM_STR);
            $rs->bindValue(':pagar_data_docto',  $pagar->getPagarDtDocto(),  PDO::PARAM_STR);
            $rs->bindValue(':pagar_valor',       $pagar->getPagarValor(),    PDO::PARAM_STR);
            $rs->bindValue(':pagar_valor_pago',  $pagar->getPagarValorPago(),PDO::PARAM_STR);
            $rs->bindValue(':pagar_num_doc',     $pagar->getPagarNumDoc(),   PDO::PARAM_STR);
            $rs->bindValue(':pagar_sequencia',   $pagar->getPagarSeq(),      PDO::PARAM_INT);
            $rs->bindValue(':pagar_obs',         $pagar->getPagarObs(),      PDO::PARAM_STR);
            $rs->bindValue(':usr_inc',           $pagar->getUsrInc(),        PDO::PARAM_STR);
            
            if (strlen($pagar->getPagarDtPagto()) > 0){
                $rs->bindValue(':pagar_data_pagto',  $pagar->getPagarDtPagto(),  PDO::PARAM_STR);
            } else {
                $rs->bindValue(':pagar_data_pagto', NULL, PDO::PARAM_INT);
            }

            $rs->execute();

            $idIns = $con->lastInsertId();

            $retorno = array ("Ok", "Conta incluída!", $idIns);

        } catch (Exception $e){
            $retorno = array("Erro", "Falha ao realizar procedimento => ".$e->getMessage());
        }
        
        return $retorno;

    }
    
}

?>
