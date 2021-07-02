<?php

require_once $GLOBALS['pasta_raiz'].'/Servidor/util/conexao.php'; 
require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModFiltro.php';
require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModUsuario.php';
require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModUsuarioAcesso.php';

class DaoUsuario{

    public function consultar(ModUsuario $usuarioConsulta){
                
        $con = Conexao::getInstance();
        
        $sql = "select usr_id, usr_login, usr_email, usr_situacao, ".
               "       data_inclusao, data_alteracao, usr_inclusao, usr_alteracao ".
               " from usuario ".
               (
                    $usuarioConsulta->getUsrId() > 0 ?
                      " where usr_id = :id;"
                    : " where usr_login = :login ".
                      "   and usr_senha = MD5(:senha) ".
                      "   and usr_situacao = 'A';"
               );
        
        $rs = $con->prepare($sql);

        if ($usuarioConsulta->getUsrId() > 0){
            $rs->bindValue(':id', $usuarioConsulta->getUsrId(), PDO::PARAM_STR);
        } else {
            $rs->bindValue(':login', $usuarioConsulta->getUsrLogin(), PDO::PARAM_STR);
            $rs->bindValue(':senha', $usuarioConsulta->getUsrSenha(), PDO::PARAM_STR);
        }       

        $rs->execute();
        
        $consulta = array("Erro","Usuário ou senha inválidos!");

        while($row = $rs->fetch(PDO::FETCH_OBJ)){

            $usuario = new ModUsuario($row->usr_id, $row->usr_login, $row->usr_email, '', $row->usr_situacao, 
            $row->data_inclusao, $row->data_alteracao, $row->usr_inclusao, $row->usr_alteracao);

            $usuario->setAcessos(self::retornarAcessos($usuario));

            $consulta = array("Ok",$usuario->retornaArray());

        }
        
        return $consulta;
                
    }

    public function retornarAcessos(ModUsuario $usuarioConsulta){
                
        $con = Conexao::getInstance();
        
        $sql = "select ua.usuace_id, ua.usuace_codigo, ua.usuace_acesso, a.apl_descricao ".
               " from usuario_acesso ua inner join aplicacoes a on (ua.usuace_codigo = a.apl_cod) ".
               " where ua.usr_id = :id order by ua.usuace_codigo;";
        
        $rs = $con->prepare($sql);
        $rs->bindValue(':id', $usuarioConsulta->getUsrId(), PDO::PARAM_STR);
        $rs->execute();
        
        $consulta = array();

        while($row = $rs->fetch(PDO::FETCH_OBJ)){

            $usuarioAcesso = new ModUsuarioAcesso($row->usuace_id, $row->apl_descricao, 
            $row->usuace_codigo, $row->usuace_acesso);

            array_push($consulta, $usuarioAcesso->retornaArray());

        }
        
        return $consulta;
                
    }

    /**
    *
    * @param ModFiltro[] $arrayFiltros 
    * @return array
    */
    public function listarUsuarios($arrayFiltros, $complementoOrdLim){
                
        $con = Conexao::getInstance();
        
        $sql = "select usr_id, usr_login, usr_email, usr_situacao ".
               " from usuario ";
        
        $sqlConta = "select count(usr_id) as total ".
                    " from usuario ";

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

            $usuario = new ModUsuario($row->usr_id, $row->usr_login, $row->usr_email, '', $row->usr_situacao);

            array_push($consulta, $usuario->retornaArray());

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

    public function validaLogin(ModUsuario $usuario){

        $retVld = true;

        $con = Conexao::getInstance();

        $sql = "select count(*) as qtd from usuario where usr_login = :login and usr_id <> :id;";

        $rs = $con->prepare($sql);
        $rs->bindValue(':login', $usuario->getUsrLogin(), PDO::PARAM_STR);
        $rs->bindValue(':id',    $usuario->getUsrId(), PDO::PARAM_INT);
        
        $rs->execute();

        while($row = $rs->fetch(PDO::FETCH_OBJ)){
            $retVld = $row->qtd == 0;
        }

        return $retVld;

    }

    /**
    *
    * @param ModUsuario $usuario 
    * @return array
    */
    public function alterarUsuario(ModUsuario $usuario){
        
        $retorno = array("Erro", "Nenhum processo realizado");
        
        try{
            
            if (self::validaLogin($usuario)){
                    
                $con = Conexao::getInstance();

                $sql = "update usuario ".
                       " set usr_login      = :login, ".
                       "     usr_email      = :email, ".
                       "     usr_situacao   = :situacao, ".
                       (strlen($usuario->getUsrSenha()) > 0 ? "usr_senha = MD5(:senha)," : "").
                       "     usr_alteracao  = :usralt, ".
                       "     data_alteracao = NOW() ".
                       " where usr_id = :id ";

                $rs = $con->prepare($sql);
                $rs->bindValue(':login',    $usuario->getUsrLogin(),    PDO::PARAM_STR);
                $rs->bindValue(':email',    $usuario->getUsrEmail(),    PDO::PARAM_STR);
                $rs->bindValue(':situacao', $usuario->getUsrSituacao(), PDO::PARAM_STR);
                $rs->bindValue(':usralt',   $usuario->getUsrAlt(),      PDO::PARAM_INT);
                $rs->bindValue(':id',       $usuario->getUsrId(),       PDO::PARAM_INT);

                if (strlen($usuario->getUsrSenha()) > 0){
                    $rs->bindValue(':senha', $usuario->getUsrSenha(), PDO::PARAM_STR);
                }
                
                $rs->execute();

                $retorno = array ("Ok", "Usuário alterado!", $usuario->getUsrId());

            } else {
                $retorno = array ("Erro","Login já existe para outro usuário!");
            }

        } catch (Exception $e){
            $retorno = array("Erro", "Falha ao realizar procedimento => ".$e->getMessage());
        }
        
        return $retorno;

    }

    /**
    *
    * @param ModUsuario $usuario 
    * @return array
    */
    public function incluirUsuario(ModUsuario $usuario){
        
        $retorno = array("Erro", "Nenhum processo realizado");
        
        try{

            if (self::validaLogin($usuario)){
                    
                $con = Conexao::getInstance();

                $sql = "insert into usuario (usr_login, usr_email, usr_situacao, usr_senha, usr_inclusao, data_inclusao) ".
                       " values (:login, :email, :situacao, MD5(:senha), :usrinc, NOW());";

                $rs = $con->prepare($sql);
                $rs->bindValue(':login',    $usuario->getUsrLogin(),    PDO::PARAM_STR);
                $rs->bindValue(':email',    $usuario->getUsrEmail(),    PDO::PARAM_STR);
                $rs->bindValue(':situacao', $usuario->getUsrSituacao(), PDO::PARAM_STR);
                $rs->bindValue(':senha',    $usuario->getUsrSenha(), PDO::PARAM_STR);
                $rs->bindValue(':usrinc',   $usuario->getUsrInc(),      PDO::PARAM_INT);
                
                $rs->execute();

                $idIns = $con->lastInsertId();

                $retorno = array ("Ok", "Usuário incluído!", $idIns);

            } else {
                $retorno = array ("Erro","Login já existe para outro usuário!");
            }

        } catch (Exception $e){
            $retorno = array("Erro", "Falha ao realizar procedimento => ".$e->getMessage());
        }
        
        return $retorno;

    }

    /**
    *
    * @param ModUsuarioAcesso[] $modAcessos 
    * @return array
    */
    public function alteraPermissao($modAcessos){

        $retorno = array("Ok","Ok");

        try {

            $con = Conexao::getInstance();

            $sqlUpd = "update usuario_acesso ".
                      " set usuace_acesso = :acesso ".
                      " where usuace_id   = :id;";

            foreach ($modAcessos as $acesso) {

                $rs = $con->prepare($sqlUpd);
                $rs->bindValue(':acesso', $acesso->getAcessoValor(), PDO::PARAM_STR);
                $rs->bindValue(':id',     $acesso->getAcessoId(),    PDO::PARAM_STR);
                
                $rs->execute();

            }

        } catch (Exception $e){
            $retorno = array("Erro", "Falha ao realizar procedimento => ".$e->getMessage());
        }

        return $retorno;

    }
    
}

?>
