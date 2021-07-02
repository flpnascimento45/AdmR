<?php

require_once $GLOBALS['pasta_raiz'].'/Servidor/util/conexao.php'; 
require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModFiltro.php';
require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModDespesa.php';

class DaoDespesa{

    public function consultar(ModDespesa $despesa){
                
        $con = Conexao::getInstance();
        
        $sql = "select desp_id, desp_nome, desp_valor, desp_situacao, desp_tipo, ".
               "       data_inclusao, data_alteracao, usr_inclusao, usr_alteracao ".
               " from despesa ".
               " where desp_id = :id;";
              
        $rs = $con->prepare($sql);

        $rs->bindValue(':id', $despesa->getDespId(), PDO::PARAM_STR);
        
        $rs->execute();
        
        $consulta = array("Erro","Despesa não localizada!");

        while($row = $rs->fetch(PDO::FETCH_OBJ)){

            $desp = new ModDespesa($row->desp_id, $row->desp_nome, $row->desp_valor, $row->desp_situacao, $row->desp_tipo,
            $row->data_inclusao, $row->data_alteracao, $row->usr_inclusao, $row->usr_alteracao);

            $consulta = array("Ok", $desp->retornaArray());

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
        
        $sql = "select desp_id, desp_nome, desp_valor, desp_situacao, desp_tipo ".
               " from despesa ";
        
        $sqlConta = "select count(desp_id) as total ".
                    " from despesa ";

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

            $desp = new ModDespesa($row->desp_id, $row->desp_nome, $row->desp_valor, $row->desp_situacao, $row->desp_tipo);

            array_push($consulta, $desp->retornaArray());

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
    * @param ModDespesa $despesa 
    * @return array
    */
    public function alterar(ModDespesa $despesa){
        
        $retorno = array("Erro", "Nenhum processo realizado");
        
        try{

            $con = Conexao::getInstance();

            $sql = "update despesa ".
                    " set desp_nome      = :nome, ".
                    "     desp_valor     = :valor, ".
                    "     desp_situacao  = :situacao, ".
                    "     desp_tipo      = :tipo, ".
                    "     usr_alteracao  = :usralt, ".
                    "     data_alteracao = NOW() ".
                    " where desp_id = :id;";

            $rs = $con->prepare($sql);
            $rs->bindValue(':nome',     $despesa->getDespNome(),  PDO::PARAM_STR);
            $rs->bindValue(':valor',    $despesa->getDespValor(), PDO::PARAM_STR);
            $rs->bindValue(':situacao', $despesa->getDespSit(),   PDO::PARAM_STR);
            $rs->bindValue(':tipo',     $despesa->getDespTipo(),  PDO::PARAM_STR);
            $rs->bindValue(':usralt',   $despesa->getUsrAlt(),    PDO::PARAM_INT);
            $rs->bindValue(':id',       $despesa->getDespId(),    PDO::PARAM_INT);
            
            $rs->execute();

            $retorno = array ("Ok", "Despesa alterada!", $despesa->getDespId());

        } catch (Exception $e){
            $retorno = array("Erro", "Falha ao realizar procedimento => ".$e->getMessage());
        }
        
        return $retorno;

    }

    /**
    *
    * @param ModDespesa $despesa 
    * @return array
    */
    public function incluir(ModDespesa $despesa){
        
        $retorno = array("Erro", "Nenhum processo realizado");
        
        try{

            $con = Conexao::getInstance();

            $sql = "insert into despesa (desp_nome, desp_valor, desp_situacao, desp_tipo, usr_inclusao, data_inclusao) ".
                    " values (:nome, :valor, :situacao, :tipo, :usrinc, NOW());";

            $rs = $con->prepare($sql);
            $rs->bindValue(':nome',     $despesa->getDespNome(),  PDO::PARAM_STR);
            $rs->bindValue(':valor',    $despesa->getDespValor(), PDO::PARAM_STR);
            $rs->bindValue(':situacao', $despesa->getDespSit(),   PDO::PARAM_STR);
            $rs->bindValue(':tipo',     $despesa->getDespTipo(),  PDO::PARAM_STR);
            $rs->bindValue(':usrinc',   $despesa->getUsrInc(),    PDO::PARAM_INT);
            
            $rs->execute();

            $idIns = $con->lastInsertId();

            $retorno = array ("Ok", "Despesa incluída!", $idIns);

        } catch (Exception $e){
            $retorno = array("Erro", "Falha ao realizar procedimento => ".$e->getMessage());
        }
        
        return $retorno;

    }
    
}

?>
