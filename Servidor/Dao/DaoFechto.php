<?php

require_once $GLOBALS['pasta_raiz'].'/Servidor/util/conexao.php'; 
require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModFiltro.php';
require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModFechto.php';
require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModFechtoPessoa.php';

class DaoFechto {

    public function consultar(ModFechto $fechto, $retornoObj=false){
                
        $con = Conexao::getInstance();
        
        $sql = "select fechto_id, fechto_descricao, fechto_data, fechto_situacao, ".
               "       data_inclusao, data_alteracao, usr_inclusao, usr_alteracao ".
               " from fechamento ".
               " where fechto_id = :id;";
              
        $rs = $con->prepare($sql);

        $rs->bindValue(':id', $fechto->getFechtoId(), PDO::PARAM_STR);
        
        $rs->execute();
        
        $consulta = array("Erro","Fechamento não localizado.");

        while($row = $rs->fetch(PDO::FETCH_OBJ)){

            $fecRet = new ModFechto($row->fechto_id, $row->fechto_descricao, $row->fechto_data, $row->fechto_situacao,
            $row->data_inclusao, $row->data_alteracao, $row->usr_inclusao, $row->usr_alteracao);

            $consulta = array("Ok", $fecRet->retornaArray());

            if ($retornoObj){
                $consulta = array("Ok", $fecRet);
            }

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
        
        $sql = "select fechto_id, fechto_descricao, fechto_data, fechto_situacao ".
               " from fechamento ";
        
        $sqlConta = "select count(fechto_id) as total ".
                    " from fechamento ";

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

            $fecRet = new ModFechto($row->fechto_id, $row->fechto_descricao, $row->fechto_data, $row->fechto_situacao);

            array_push($consulta, $fecRet->retornaArray());

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
    * @param ModFechto $ponto 
    * @return array
    */
    public function alterar(ModFechto $fechto){
        
        $retorno = array("Erro", "Nenhum processo realizado");
        
        try{

            $con = Conexao::getInstance();

            $sql = "update fechamento ".
                    " set fechto_descricao = :descricao, ".
                    "     fechto_situacao  = :situacao, ".
                    "     fechto_data      = :data, ".
                    "     usr_alteracao    = :usralt, ".
                    "     data_alteracao   = NOW() ".
                    " where fechto_id       = :id ".
                    "   and fechto_situacao = 'A';";

            $rs = $con->prepare($sql);
            $rs->bindValue(':descricao', $fechto->getFechtoDescricao(), PDO::PARAM_STR);
            $rs->bindValue(':situacao',  $fechto->getFechtoSit(),       PDO::PARAM_STR);
            $rs->bindValue(':data',      $fechto->getFechtoData(),      PDO::PARAM_STR);
            $rs->bindValue(':usralt',    $fechto->getUsrAlt(),          PDO::PARAM_INT);
            $rs->bindValue(':id',        $fechto->getFechtoId(),        PDO::PARAM_STR);
            
            $rs->execute();

            $retorno = array ("Ok", "Fechamento alterado!", $fechto->getFechtoId());

        } catch (Exception $e){
            $retorno = array("Erro", "Falha ao realizar procedimento => ".$e->getMessage());
        }
        
        return $retorno;

    }

    /**
    *
    * @param ModFechto $fechto 
    * @return array
    */
    public function incluir(ModFechto $fechto){
        
        $retorno = array("Erro", "Nenhum processo realizado");
        
        try{

            $con = Conexao::getInstance();

            $sql = "insert into fechamento (fechto_descricao, fechto_data, fechto_situacao, ".
                   " usr_inclusao, data_inclusao) ".
                   " values (:descricao, :data, 'A', :usrinc, NOW());";

            $rs = $con->prepare($sql);
            $rs->bindValue(':descricao', $fechto->getFechtoDescricao(), PDO::PARAM_STR);
            $rs->bindValue(':data',      $fechto->getFechtoData(),      PDO::PARAM_STR);
            $rs->bindValue(':usrinc',    $fechto->getUsrInc(),          PDO::PARAM_INT);
            
            $rs->execute();

            $idIns = $con->lastInsertId();

            $retorno = array ("Ok", "Fechamento incluído!", $idIns);

        } catch (Exception $e){
            $retorno = array("Erro", "Falha ao realizar procedimento => ".$e->getMessage());
        }
        
        return $retorno;

    }

    /**
    *
    * @param ModFechtoPessoa $fechtoPes 
    * @return array
    */
    public function incluirFechtoPessoa(ModFechtoPessoa $fechtoPes){
        
        $retorno = array("Erro", "Nenhum processo realizado");
        
        try{

            $con = Conexao::getInstance();

            $sql = "insert into fechamento_pessoa (fechto_id, pessoa_id, fecdesp_val_con_cfu, ".
                   " fecdesp_val_final, fecdesp_val_pago, fecdesp_val_ger_cfu, ".
                   " usr_inclusao, data_inclusao) ".
                   " values (:fechto_id, :pessoa_id, :val_con_cfu, :val_final, ".
                   " :val_pago, :val_ger_cfg, :usrinc, NOW());";

            $rs = $con->prepare($sql);
            $rs->bindValue(':fechto_id',   $fechtoPes->getFechto(),              PDO::PARAM_STR);
            $rs->bindValue(':pessoa_id',   $fechtoPes->getPessoa(),              PDO::PARAM_STR);
            $rs->bindValue(':val_con_cfu', $fechtoPes->getFecDespConsCredFunc(), PDO::PARAM_STR);
            $rs->bindValue(':val_final',   $fechtoPes->getFecDespValorFinal(),   PDO::PARAM_STR);
            $rs->bindValue(':val_pago',    $fechtoPes->getFecDespValorPago(),    PDO::PARAM_STR);
            $rs->bindValue(':val_ger_cfg', $fechtoPes->getFecDespGerCredFunc(),  PDO::PARAM_STR);
            $rs->bindValue(':usrinc',      $fechtoPes->getUsrInc(),              PDO::PARAM_INT);
            
            $rs->execute();

            $idIns = $con->lastInsertId();

            $despesasPessoa = $fechtoPes->getDespesas();

            foreach($despesasPessoa as $despesa){
                
                $sqlInsDesp = "insert into fechamento_pessoa_despesa (fecdesp_id, desp_id, fpd_valor, fpd_sit, fpd_valor_orig) ".
                              " values (:fecdesp_id, (select desp_id from pessoa_despesa where pesdes_id = :desp), ".
                              " :valor, 'A', :valororig);";

                $rs = $con->prepare($sqlInsDesp);
                $rs->bindValue(':fecdesp_id', $idIns,                PDO::PARAM_STR);
                $rs->bindValue(':desp',       $despesa['despesa'],   PDO::PARAM_STR);
                $rs->bindValue(':valor',      $despesa['valor'],     PDO::PARAM_STR);
                $rs->bindValue(':valororig',  $despesa['valororig'], PDO::PARAM_STR);
                
                $rs->execute();

            }

            $retorno = array ("Ok", "Despesas fechadas!", $idIns);

        } catch (Exception $e){
            $retorno = array("Erro", "Falha ao realizar procedimento => ".$e->getMessage());
        }
        
        return $retorno;

    }

    public function listarFechamentoPessoa(ModFechto $fechto){
                
        $con = Conexao::getInstance();
        
        $sql = "SELECT f.fecdesp_id, f.pessoa_id, f.fecdesp_val_con_cfu, f.fecdesp_val_final, ".
               "         f.fecdesp_val_pago, f.fecdesp_val_ger_cfu, p.pessoa_fantasia, p.pessoa_cpf_cnpj, ".
               "         SUM(d.fpd_valor) AS fpd_valor, SUM(d.fpd_valor_orig) AS fpd_valor_orig ".
               " FROM fechamento_pessoa f INNER JOIN fechamento_pessoa_despesa d ON (f.fecdesp_id = d.fecdesp_id) ".
               "                          INNER JOIN pessoa p ON (f.pessoa_id = p.pessoa_id) ".
               " WHERE f.fechto_id = :id ".
               " GROUP BY f.fecdesp_id, f.pessoa_id, f.fecdesp_val_con_cfu, f.fecdesp_val_final, ".
               "         f.fecdesp_val_pago, f.fecdesp_val_ger_cfu, p.pessoa_fantasia, p.pessoa_cpf_cnpj ".
               " ORDER BY p.pessoa_fantasia;";
              
        $rs = $con->prepare($sql);

        $rs->bindValue(':id', $fechto->getFechtoId(), PDO::PARAM_STR);
        
        $rs->execute();
        
        $consulta = array();

        while($row = $rs->fetch(PDO::FETCH_OBJ)){

            $fecPesRet = new ModFechtoPessoa($row->fecdesp_id, 0, array("fantasia" => $row->pessoa_fantasia, "id" => $row->pessoa_id),
                                            $row->fpd_valor_orig, $row->fecdesp_val_con_cfu, $row->fecdesp_val_final,
                                            $row->fecdesp_val_ger_cfu, $row->fecdesp_val_pago);

            array_push($consulta, $fecPesRet->retornaArray());

        }
        
        return array("Ok", $consulta);
                
    }

    /**
    *
    * @param ModFechtoPessoa $fechtoPes 
    * @return array
    */
    public function deletarFechtoPessoa(ModFechtoPessoa $fechtoPes){
        
        $retorno = array("Erro", "Nenhum processo realizado");
        
        try{

            $con = Conexao::getInstance();

            $sql = "delete from fechamento_pessoa where fecdesp_id = :fecdesp_id;";

            $rs = $con->prepare($sql);
            $rs->bindValue(':fecdesp_id', $fechtoPes->getFecDespId(), PDO::PARAM_STR);
            
            $rs->execute();

            $retorno = array ("Ok", "Despesas cancelada!");

        } catch (Exception $e){
            $retorno = array("Erro", "Falha ao realizar procedimento => ".$e->getMessage());
        }
        
        return $retorno;

    }

    /**
    *
    * @param ModFechto $fechto
    * @return array
    */
    public function finalizarFechto(ModFechto $fechto){
        
        $retorno = array("Erro", "Nenhum processo realizado");

        $con = Conexao::getInstance();

        $con->beginTransaction();
        
        try{

            $sql = "SELECT d.fpd_id, f.fecdesp_id, f.pessoa_id, f.fecdesp_val_con_cfu, ".
                   "       f.fecdesp_val_final, f.fecdesp_val_pago, f.fecdesp_val_ger_cfu, ".
                   "       d.desp_id, d.fpd_valor ".
                   " FROM fechamento_pessoa f INNER JOIN fechamento_pessoa_despesa d ON (f.fecdesp_id = d.fecdesp_id) ".
                   "                          INNER JOIN pessoa p ON (f.pessoa_id = p.pessoa_id) ".
                   " WHERE f.fechto_id = :id ".
                   " ORDER BY p.pessoa_fantasia;";

            $rs = $con->prepare($sql);
            $rs->bindValue(':id', $fechto->getFechtoId(), PDO::PARAM_STR);
            $rs->execute();

            while($row = $rs->fetch(PDO::FETCH_OBJ)){

                if ($row->fpd_valor > 0){
                    
                    $sqlInsPag = "insert into contas_pagar (pessoa_id, desp_id, pagar_situacao, ".
                                " pagar_data_vencto, pagar_data_pagto, pagar_data_docto, pagar_valor, pagar_valor_pago, ".
                                " pagar_num_doc, pagar_sequencia, pagar_obs, usr_inclusao, data_inclusao) ".
                                " values (:pessoa, :desp, 'P', NOW(), NOW(), NOW(), :valor, :valor_pago, ".
                                " :fechto_id, 1, 'Fechamento', :usr, NOW());";

                    $rsIns = $con->prepare($sqlInsPag);
                    $rsIns->bindValue(':pessoa',     $row->pessoa_id,        PDO::PARAM_STR);
                    $rsIns->bindValue(':desp',       $row->desp_id,          PDO::PARAM_STR);
                    $rsIns->bindValue(':valor',      $row->fpd_valor,        PDO::PARAM_STR);
                    $rsIns->bindValue(':valor_pago', $row->fpd_valor,        PDO::PARAM_STR);
                    $rsIns->bindValue(':fechto_id',  $fechto->getFechtoId(), PDO::PARAM_STR);
                    $rsIns->bindValue(':usr',        $fechto->getUsrInc(),   PDO::PARAM_STR);
                    $rsIns->execute();

                    $idIns = $con->lastInsertId();

                    $sqlUpd = "update fechamento_pessoa_despesa ".
                              " set pagar_id = :pagar, fpd_sit = 'P' ".
                              " where fpd_id = :fpd;";

                    $rsUpd = $con->prepare($sqlUpd);
                    $rsUpd->bindValue(':pagar', $idIns,        PDO::PARAM_STR);
                    $rsUpd->bindValue(':fpd',   $row->fpd_id,  PDO::PARAM_STR);
                    $rsUpd->execute();

                }

            }

            $sqlUpdF = "update fechamento ".
                       " set fechto_situacao = 'F', data_alteracao = NOW(), usr_alteracao = :usr ".
                       " where fechto_id = :id;";

            $rsUpdF = $con->prepare($sqlUpdF);
            $rsUpdF->bindValue(':usr', $fechto->getUsrInc(),   PDO::PARAM_STR);
            $rsUpdF->bindValue(':id',  $fechto->getFechtoId(), PDO::PARAM_STR);
            $rsUpdF->execute();

            $sqlUpdP = "UPDATE pessoa p ".
                       " SET pessoa_val_cred = pessoa_val_cred + ".
                       " (SELECT fecdesp_val_ger_cfu FROM fechamento_pessoa f WHERE f.pessoa_id = p.pessoa_id AND f.fechto_id = :id) ".
                       " WHERE EXISTS (SELECT fecdesp_id FROM fechamento_pessoa f WHERE f.pessoa_id = p.pessoa_id AND f.fechto_id = :id AND f.fecdesp_val_ger_cfu <> 0);";

            $rsUpdP = $con->prepare($sqlUpdP);
            $rsUpdP->bindValue(':id', $fechto->getFechtoId(), PDO::PARAM_STR);
            $rsUpdP->execute();

            $con->commit();

            $retorno = array ("Ok", "Fechamento realizado com êxito!");

        } catch (PDOException $e){
            $con->rollBack();
            $retorno = array("Erro", "Falha ao realizar procedimento => ".$e->getMessage());
        }
        
        return $retorno;

    }
    
}

?>
