<?php

require_once $GLOBALS['pasta_raiz'].'/Servidor/util/conexao.php'; 
require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModFiltro.php';
require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModPonto.php';
require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModPontoPessoa.php';

class DaoPonto{

    public function consultar(ModPonto $ponto){
                
        $con = Conexao::getInstance();
        
        $sql = "select ponto_id, ponto_data, ponto_situacao,".
               "       data_inclusao, data_alteracao, usr_inclusao, usr_alteracao ".
               " from ponto ".
               " where ponto_id = :id;";
              
        $rs = $con->prepare($sql);

        $rs->bindValue(':id', $ponto->getPontoId(), PDO::PARAM_STR);
        
        $rs->execute();
        
        $consulta = array("Erro","Ponto não localizado.");

        while($row = $rs->fetch(PDO::FETCH_OBJ)){

            $pontoRet = new ModPonto($row->ponto_id, $row->ponto_data, $row->ponto_situacao,
            $row->data_inclusao, $row->data_alteracao, $row->usr_inclusao, $row->usr_alteracao);

            $consulta = array("Ok", $pontoRet->retornaArray());

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
        
        $sql = "select ponto_id, ponto_data, ponto_situacao ".
               " from ponto ";
        
        $sqlConta = "select count(ponto_id) as total ".
                    " from ponto ";

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

            $pontoRet = new ModPonto($row->ponto_id, $row->ponto_data, $row->ponto_situacao);

            array_push($consulta, $pontoRet->retornaArray());

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

    public function listarPontoPessoa(ModPonto $ponto){
                
        $con = Conexao::getInstance();
        
        $sql = "select p.ptopes_id, p.ponto_id, p.pessoa_id, p.ptopes_presente, pe.pessoa_fantasia ".
               " from ponto_pessoa p inner join pessoa pe on (pe.pessoa_id = p.pessoa_id) ".
               " where p.ponto_id = :id;";
              
        $rs = $con->prepare($sql);

        $rs->bindValue(':id', $ponto->getPontoId(), PDO::PARAM_STR);
        
        $rs->execute();
        
        $consulta = array();

        while($row = $rs->fetch(PDO::FETCH_OBJ)){

            $pontoRet = new ModPontoPessoa($row->ptopes_id, $row->pessoa_fantasia, $row->ponto_id, $row->ptopes_presente);

            array_push($consulta, $pontoRet->retornaArray());

        }
        
        return $consulta;
                
    }

    /**
    *
    * @param ModPonto $ponto 
    * @return array
    */
    public function alterar(ModPonto $ponto){
        
        $retorno = array("Erro", "Nenhum processo realizado");
        
        try{

            $con = Conexao::getInstance();

            $sql = "update ponto ".
                    " set ponto_data     = :data, ".
                    "     ponto_situacao = :situacao, ".
                    "     usr_alteracao  = :usralt, ".
                    "     data_alteracao = NOW() ".
                    " where ponto_id = :id ".
                    "   and ponto_situacao = 'A';";

            $rs = $con->prepare($sql);
            $rs->bindValue(':data',     $ponto->getPontoData(),     PDO::PARAM_STR);
            $rs->bindValue(':situacao', $ponto->getPontoSituacao(), PDO::PARAM_STR);
            $rs->bindValue(':usralt',   $ponto->getUsrAlt(),        PDO::PARAM_INT);
            $rs->bindValue(':id',       $ponto->getPontoId(),       PDO::PARAM_STR);
            
            $rs->execute();

            $retorno = array ("Ok", "Ponto alterado!", $ponto->getPontoId());

        } catch (Exception $e){
            $retorno = array("Erro", "Falha ao realizar procedimento => ".$e->getMessage());
        }
        
        return $retorno;

    }

    /**
    *
    * @param ModPonto $ponto 
    * @return array
    */
    public function incluir(ModPonto $ponto){
        
        $retorno = array("Erro", "Nenhum processo realizado");
        
        try{

            $con = Conexao::getInstance();

            $sql = "insert into ponto (ponto_data, ponto_situacao, usr_inclusao, data_inclusao) ".
                    " values (:data, 'A', :usrinc, NOW());";

            $rs = $con->prepare($sql);
            $rs->bindValue(':data',     $ponto->getPontoData(), PDO::PARAM_STR);
            $rs->bindValue(':usrinc',   $ponto->getUsrInc(),    PDO::PARAM_INT);
            
            $rs->execute();

            $idIns = $con->lastInsertId();

            $retorno = array ("Ok", "Ponto incluído!", $idIns);

        } catch (Exception $e){
            $retorno = array("Erro", "Falha ao realizar procedimento => ".$e->getMessage());
        }
        
        return $retorno;

    }

    public function incluirPontoPessoa(ModPontoPessoa $ponto){
        
        $retorno = array("Erro", "Nenhum processo realizado");
        
        try{

            $con = Conexao::getInstance();

            $sql = "insert into ponto_pessoa (pessoa_id, ponto_id, ptopes_presente, usr_inclusao, data_inclusao) ".
                    " values (:pessoa, :ponto, :presente, :usrinc, NOW());";

            $rs = $con->prepare($sql);
            $rs->bindValue(':pessoa',   $ponto->getPessoa(),         PDO::PARAM_STR);
            $rs->bindValue(':ponto',    $ponto->getPonto(),          PDO::PARAM_STR);
            $rs->bindValue(':presente', $ponto->getPonPesPresente(), PDO::PARAM_INT);
            $rs->bindValue(':usrinc',   $ponto->getUsrInc(),         PDO::PARAM_INT);
            
            $rs->execute();

            $idIns = $con->lastInsertId();

            $retorno = array ("Ok", "Ponto incluído!", $idIns);

        } catch (Exception $e){
            $retorno = array("Erro", "Falha ao realizar procedimento => ".$e->getMessage());
        }
        
        return $retorno;

    }

    public function cancelarLote($ponto){

        $retorno = array("Erro", "Nenhum processo realizado");
        
        try{

            $con = Conexao::getInstance();

            $sql = "delete from ponto_pessoa_despesa ".
                    " where ptopes_id in (select p.ptopes_id from ponto_pessoa p where p.ponto_id = :ponto);";

            $rs = $con->prepare($sql);
            $rs->bindValue(':ponto', $ponto->getPonto(), PDO::PARAM_STR);
            $rs->execute();

            $sql = "delete from ponto_pessoa where ponto_id = :ponto;";

            $rs = $con->prepare($sql);
            $rs->bindValue(':ponto', $ponto->getPonto(), PDO::PARAM_STR);
            $rs->execute();

            $retorno = array ("Ok", "Ok");

        } catch (Exception $e){
            $retorno = array("Erro", "Falha ao realizar procedimento => ".$e->getMessage());
        }
        
        return $retorno;

    }
    
}

?>
