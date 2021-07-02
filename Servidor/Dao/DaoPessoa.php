<?php

require_once $GLOBALS['pasta_raiz'].'/Servidor/util/conexao.php'; 
require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModFiltro.php';
require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModPessoa.php';
require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModPessoaDespesa.php';

class DaoPessoa {

    public function consultar(ModPessoa $pessoa){
                
        $con = Conexao::getInstance();
        
        $sql = "select pessoa_id, pessoa_fantasia, pessoa_razao, pessoa_telefone,".
               "       pessoa_celular, pessoa_cpf_cnpj, pessoa_situacao, pessoa_tipo_for, ".
               "       pessoa_tipo_fun, pessoa_tipo_psv, pessoa_obs, pessoa_val_cred, ".
               "       data_inclusao, data_alteracao, usr_inclusao, usr_alteracao ".
               " from pessoa ".
               " where pessoa_id = :id;";
              
        $rs = $con->prepare($sql);

        $rs->bindValue(':id', $pessoa->getPessoaId(), PDO::PARAM_STR);
        
        $rs->execute();
        
        $consulta = array("Erro","Pessoa não localizada!");

        while($row = $rs->fetch(PDO::FETCH_OBJ)){

            $pessoaRet = new ModPessoa($row->pessoa_id, $row->pessoa_fantasia, $row->pessoa_razao, $row->pessoa_telefone,
            $row->pessoa_celular, $row->pessoa_cpf_cnpj, $row->pessoa_situacao, $row->pessoa_tipo_fun, $row->pessoa_tipo_for,
            $row->pessoa_tipo_psv, $row->pessoa_obs, $row->data_inclusao, $row->data_alteracao, $row->usr_inclusao, 
            $row->usr_alteracao, $row->pessoa_val_cred);

            $consulta = array("Ok", $pessoaRet->retornaArray());

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
        
        $sql = "select pessoa_id, pessoa_fantasia, pessoa_razao, pessoa_telefone,".
               "       pessoa_celular, pessoa_cpf_cnpj, pessoa_situacao, pessoa_val_cred, ".
               "       pessoa_tipo_for, pessoa_tipo_fun, pessoa_tipo_psv ".
               " from pessoa ";
        
        $sqlConta = "select count(pessoa_id) as total ".
                    " from pessoa ";

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

            $pessoaRet = new ModPessoa($row->pessoa_id, $row->pessoa_fantasia, $row->pessoa_razao, $row->pessoa_telefone,
            $row->pessoa_celular, $row->pessoa_cpf_cnpj, $row->pessoa_situacao);
            $pessoaRet->setPessoaValCredito($row->pessoa_val_cred)
                      ->setPessoaFor($row->pessoa_tipo_for)
                      ->setPessoaFun($row->pessoa_tipo_fun)
                      ->setPessoaPsv($row->pessoa_tipo_psv);

            array_push($consulta, $pessoaRet->retornaArray());

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

    public function validaCpfCnpj(ModPessoa $pessoa){

        $retVld = true;

        if (strlen(trim($pessoa->getPessoaCpfCnpj())) > 0) {

            $con = Conexao::getInstance();

            $sql = "select count(*) as qtd ".
                " from pessoa ".
                " where pessoa_cpf_cnpj = :cpf ".
                "   and pessoa_id <> :id ".
                "   and pessoa_tipo_for = :for ".
                "   and pessoa_tipo_fun = :fun ".
                "   and pessoa_tipo_psv = :psv;";

            $rs = $con->prepare($sql);
            $rs->bindValue(':cpf', $pessoa->getPessoaCpfCnpj(), PDO::PARAM_STR);
            $rs->bindValue(':id',  $pessoa->getPessoaId(), PDO::PARAM_INT);
            $rs->bindValue(':for', $pessoa->getPessoaFor(), PDO::PARAM_STR);
            $rs->bindValue(':fun', $pessoa->getPessoaFun(), PDO::PARAM_STR);
            $rs->bindValue(':psv', $pessoa->getPessoaPsv(), PDO::PARAM_STR);
            
            $rs->execute();

            while($row = $rs->fetch(PDO::FETCH_OBJ)){
                $retVld = $row->qtd == 0;
            }

        }

        return $retVld;

    }

    /**
    *
    * @param ModPessoa $pessoa 
    * @return array
    */
    public function alterar(ModPessoa $pessoa){
        
        $retorno = array("Erro", "Nenhum processo realizado");
        
        try{

            if (self::validaCpfCnpj($pessoa)){

                $con = Conexao::getInstance();

                $sql = "update pessoa ".
                        " set pessoa_fantasia  = :fantasia, ".
                        "     pessoa_razao     = :razao, ".
                        "     pessoa_cpf_cnpj  = :cpf, ".
                        "     pessoa_situacao  = :situacao, ".
                        "     pessoa_telefone  = :telefone, ".
                        "     pessoa_celular   = :celular, ".
                        "     pessoa_obs       = :obs, ".
                        "     pessoa_val_cred  = :val_cred, ".
                        "     usr_alteracao    = :usralt, ".
                        "     data_alteracao   = NOW() ".
                        " where pessoa_id = :id;";

                $rs = $con->prepare($sql);
                $rs->bindValue(':fantasia', $pessoa->getPessoaFantasia(),   PDO::PARAM_STR);
                $rs->bindValue(':razao',    $pessoa->getPessoaRazao(),      PDO::PARAM_STR);
                $rs->bindValue(':cpf',      $pessoa->getPessoaCpfCnpj(),    PDO::PARAM_STR);
                $rs->bindValue(':situacao', $pessoa->getPessoaSituacao(),   PDO::PARAM_STR);
                $rs->bindValue(':telefone', $pessoa->getPessoaTelefone(),   PDO::PARAM_STR);
                $rs->bindValue(':celular',  $pessoa->getPessoaCelular(),    PDO::PARAM_STR);
                $rs->bindValue(':obs',      $pessoa->getPessoaObs(),        PDO::PARAM_STR);
                $rs->bindValue(':val_cred', $pessoa->getPessoaValCredito(), PDO::PARAM_STR);
                $rs->bindValue(':usralt',   $pessoa->getUsrAlt(),           PDO::PARAM_INT);
                $rs->bindValue(':id',       $pessoa->getPessoaId(),         PDO::PARAM_INT);
                
                $rs->execute();

                $retorno = array ("Ok", "Alterado com êxito!", $pessoa->getPessoaId());

            } else {
                $retorno = array("Erro", "Cpf/Cnpj já cadastrado.");
            }

        } catch (Exception $e){
            $retorno = array("Erro", "Falha ao realizar procedimento => ".$e->getMessage());
        }
        
        return $retorno;

    }

    /**
    *
    * @param ModPessoa $pessoa 
    * @return array
    */
    public function incluir(ModPessoa $pessoa){
        
        $retorno = array("Erro", "Nenhum processo realizado");
        
        try{
            
            if (self::validaCpfCnpj($pessoa)){

                $con = Conexao::getInstance();

                $sql = "insert into pessoa (pessoa_fantasia, pessoa_razao, pessoa_cpf_cnpj, pessoa_situacao, ".
                    " pessoa_telefone, pessoa_celular, pessoa_obs, usr_inclusao, data_inclusao,".
                    " pessoa_tipo_for, pessoa_tipo_fun, pessoa_tipo_psv, pessoa_val_cred) ".
                    " values (:fantasia, :razao, :cpf, :situacao, :telefone, :celular, :obs, :usrinc, NOW(),".
                    " :tipo_for, :tipo_fun, :tipo_psv, :val_cred);";

                $rs = $con->prepare($sql);
                $rs->bindValue(':fantasia', $pessoa->getPessoaFantasia(),   PDO::PARAM_STR);
                $rs->bindValue(':razao',    $pessoa->getPessoaRazao(),      PDO::PARAM_STR);
                $rs->bindValue(':cpf',      $pessoa->getPessoaCpfCnpj(),    PDO::PARAM_STR);
                $rs->bindValue(':situacao', $pessoa->getPessoaSituacao(),   PDO::PARAM_STR);
                $rs->bindValue(':telefone', $pessoa->getPessoaTelefone(),   PDO::PARAM_STR);
                $rs->bindValue(':celular',  $pessoa->getPessoaCelular(),    PDO::PARAM_STR);
                $rs->bindValue(':obs',      $pessoa->getPessoaObs(),        PDO::PARAM_STR);
                $rs->bindValue(':usrinc',   $pessoa->getUsrInc(),           PDO::PARAM_INT);
                $rs->bindValue(':tipo_for', $pessoa->getPessoaFor(),        PDO::PARAM_STR);
                $rs->bindValue(':tipo_fun', $pessoa->getPessoaFun(),        PDO::PARAM_STR);
                $rs->bindValue(':tipo_psv', $pessoa->getPessoaPsv(),        PDO::PARAM_STR);
                $rs->bindValue(':val_cred', $pessoa->getPessoaValCredito(), PDO::PARAM_STR);
                
                $rs->execute();

                $idIns = $con->lastInsertId();

                $retorno = array ("Ok", "Incluído com êxito!", $idIns);

            } else {
                $retorno = array("Erro", "Cpf/Cnpj já cadastrado.");
            }

        } catch (Exception $e){
            $retorno = array("Erro", "Falha ao realizar procedimento => ".$e->getMessage());
        }
        
        return $retorno;

    }
    
    public function listarDespesasAtivasPorPessoa(ModPessoa $pessoa){
                
        $con = Conexao::getInstance();
        
        $sql = "select pd.pesdes_id, d.desp_nome, pd.pesdes_val ".
               " from pessoa_despesa pd inner join despesa d on (pd.desp_id = d.desp_id) ".
               " where pd.pessoa_id = :id ".
               "   and pd.pesdes_sit = 'A';";
              
        $rs = $con->prepare($sql);

        $rs->bindValue(':id', $pessoa->getPessoaId(), PDO::PARAM_STR);
        
        $rs->execute();
        
        $consulta = array();

        while($row = $rs->fetch(PDO::FETCH_OBJ)){

            $pessoaDesp = new ModPessoaDespesa($row->pesdes_id, $pessoa->getPessoaId(), $row->desp_nome, $row->pesdes_val, 'A');

            array_push($consulta, $pessoaDesp->retornaArray());

        }
        
        return $consulta;
                
    }

    public function alterarDespesaPessoa(ModPessoaDespesa $despesa){
        
        $retorno = array("Erro", "Nenhum processo realizado");
        
        try{

            $con = Conexao::getInstance();

            $sql = "update pessoa_despesa ".
                    " set pesdes_sit     = :situacao, ".
                    ($despesa->getPesDesVal() > 0 ? "     pesdes_val     = :valor, " : "").
                    "     usr_alteracao  = :usralt, ".
                    "     data_alteracao = NOW() ".
                    " where pesdes_id = :id;";

            $rs = $con->prepare($sql);
            $rs->bindValue(':situacao', $despesa->getPesDesSituacao(), PDO::PARAM_STR);
            $rs->bindValue(':usralt',   $despesa->getUsrAlt(),         PDO::PARAM_INT);
            $rs->bindValue(':id',       $despesa->getPesDesId(),       PDO::PARAM_INT);
            
            if ($despesa->getPesDesVal() > 0){
                $rs->bindValue(':valor',    $despesa->getPesDesVal(),      PDO::PARAM_STR);
            }

            $rs->execute();

            $retorno = array ("Ok", "Despesa alterada!", $despesa->getPesDesId());

        } catch (Exception $e){
            $retorno = array("Erro", "Falha ao realizar procedimento => ".$e->getMessage());
        }
        
        return $retorno;

    }

    public function validaInsDespesa(ModPessoaDespesa $despesa){

        $retVld = true;

        $con = Conexao::getInstance();

        $sql = "select count(*) as qtd ".
               " from pessoa_despesa ".
               " where pessoa_id  = :pessoa_id ".
               "   and desp_id    = :desp_id ".
               "   and pesdes_sit = 'A';";

        $rs = $con->prepare($sql);
        $rs->bindValue(':pessoa_id', $despesa->getPessoa(),  PDO::PARAM_STR);
        $rs->bindValue(':desp_id',   $despesa->getDespesa(), PDO::PARAM_STR);
        
        $rs->execute();

        while($row = $rs->fetch(PDO::FETCH_OBJ)){
            $retVld = $row->qtd == 0;
        }

        return $retVld;

    }

    public function incluirDespesaPessoa(ModPessoaDespesa $despesa){
        
        $retorno = array("Erro", "Nenhum processo realizado");
        
        try{

            if (self::validaInsDespesa($despesa)){
                    
                $con = Conexao::getInstance();

                $sql = "insert into pessoa_despesa (pessoa_id, desp_id, pesdes_val, pesdes_sit, usr_inclusao, data_inclusao) ".
                        " values (:pessoa, :despesa, :valor, 'A', :usrinc, NOW());";

                $rs = $con->prepare($sql);
                $rs->bindValue(':pessoa',   $despesa->getPessoa(),         PDO::PARAM_STR);
                $rs->bindValue(':despesa',  $despesa->getDespesa(),        PDO::PARAM_STR);
                $rs->bindValue(':valor',    $despesa->getPesDesVal(),      PDO::PARAM_STR);
                $rs->bindValue(':usrinc',   $despesa->getUsrInc(),         PDO::PARAM_INT);
                
                $rs->execute();

                $idIns = $con->lastInsertId();

                $retorno = array ("Ok", "Despesa incluida!", $idIns);

            } else {
                $retorno = array ("Erro", "Despesa já existe para esse funcionário!");
            }

        } catch (Exception $e){
            $retorno = array("Erro", "Falha ao realizar procedimento => ".$e->getMessage());
        }
        
        return $retorno;

    }

    public function listarPessoasDespesas(ModPessoa $pessoaEnt, $tipoLista, $fechto=0){

        $despesasFil = $pessoaEnt->getPessoaDespesas();
                
        $con = Conexao::getInstance();
        
        $sql = "SELECT p.pessoa_id, p.pessoa_fantasia, p.pessoa_razao, p.pessoa_cpf_cnpj, p.pessoa_val_cred, ".
               "         pd.pesdes_id, pd.pesdes_val, d.desp_id, d.desp_nome ".
               " FROM pessoa p INNER JOIN pessoa_despesa pd ON (p.pessoa_id = pd.pessoa_id) ".
               "               INNER JOIN despesa d ON (pd.desp_id = d.desp_id) ".
               " WHERE p.pessoa_tipo_fun = 'S' ".
               "   AND p.pessoa_situacao = 'A' ".
               "   AND pd.pesdes_sit     = 'A' ".
               "   AND d.desp_tipo       = 'FUN' ".
               "   AND d.desp_situacao   = 'A' ".
               "   AND d.desp_id in (". implode(',', $despesasFil) .") ".
               "   AND NOT EXISTS ( ".
               "        SELECT fec.fecdesp_id ".
               "        FROM fechamento_pessoa fec INNER JOIN fechamento_pessoa_despesa fpd ON (fpd.fecdesp_id = fec.fecdesp_id) ".
               "        WHERE fec.pessoa_id = p.pessoa_id ".
               //"          AND fpd.desp_id   = d.desp_id ".
               "          AND fec.fechto_id = :fechto ".
               "   ) ".
               " ORDER BY p.pessoa_id, d.desp_nome;";

        if ($tipoLista == 'PON'){

            $sql = "SELECT p.pessoa_id, p.pessoa_fantasia, p.pessoa_razao, p.pessoa_cpf_cnpj, p.pessoa_val_cred, ".
                   "         pd.pesdes_id, d.desp_id, d.desp_nome, SUM(pd.pesdes_val) AS pesdes_val ".
                   " FROM pessoa p INNER JOIN ponto_pessoa_despesa ppd ON (p.pessoa_id = ppd.pessoa_id) ".
                   "         INNER JOIN pessoa_despesa pd ON (pd.pesdes_id = ppd.pesdes_id) ".
                   "         INNER JOIN despesa d ON (pd.desp_id = d.desp_id) ".
                   " WHERE p.pessoa_tipo_fun = 'S' ".
                   "   AND p.pessoa_situacao = 'A' ".
                   "   AND ppd.ppd_sit       = 'A' ".
                   "   AND pd.pesdes_sit     = 'A' ".
                   "   AND d.desp_situacao   = 'A' ".
                   "   AND d.desp_tipo       = 'PON' ".
                   "   AND d.desp_id in (". implode(',', $despesasFil) .") ".
                   "   AND NOT EXISTS ( ".
                   "        SELECT fec.fecdesp_id ".
                   "        FROM fechamento_pessoa fec INNER JOIN fechamento_pessoa_despesa fpd ON (fpd.fecdesp_id = fec.fecdesp_id) ".
                   "        WHERE fec.pessoa_id = p.pessoa_id ".
                   //"          AND fpd.desp_id   = d.desp_id ".
                   "          AND fec.fechto_id = :fechto ".
                   "   ) ".
                   " GROUP BY p.pessoa_id, p.pessoa_fantasia, p.pessoa_cpf_cnpj, ".
                   "          pd.pesdes_id, d.desp_id, d.desp_nome ".
                   " ORDER BY p.pessoa_id, d.desp_nome ";

        }
          
        $rs = $con->prepare($sql);
        $rs->bindValue(':fechto', $fechto,  PDO::PARAM_STR);
        $rs->execute();
        
        $consulta = array();

        $arrayDespesa = array();
        $pessoaIdAcum = 0;

        $pessoa = null;

        while($row = $rs->fetch(PDO::FETCH_OBJ)){

            if ($pessoaIdAcum <> $row->pessoa_id){

                if ($pessoaIdAcum > 0){
                    $pessoa->setPessoaDespesas($arrayDespesa);
                    array_push($consulta, $pessoa->retornaArray());
                }

                $pessoa = new ModPessoa($row->pessoa_id, $row->pessoa_fantasia, $row->pessoa_razao,
                                        '', '', $row->pessoa_cpf_cnpj);
                $pessoa->setPessoaValCredito($row->pessoa_val_cred);

                $pessoaIdAcum = $row->pessoa_id;
                $arrayDespesa = array();

            }

            $pessoaDesp = new ModPessoaDespesa($row->pesdes_id, $row->pessoa_id, $row->desp_nome, 
                                               $row->pesdes_val, 'A');
            
            array_push($arrayDespesa, $pessoaDesp->retornaArray());
            
        }

        if ($pessoaIdAcum > 0){
            $pessoa->setPessoaDespesas($arrayDespesa);
            array_push($consulta, $pessoa->retornaArray());
        }
        
        return $consulta;
                
    }

}

?>
