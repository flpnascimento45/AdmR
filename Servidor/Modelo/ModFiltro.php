<?php

require_once $GLOBALS['pasta_raiz'].'/Servidor/util/funcoes.php';

class ModFiltro {

    private $filtroTabela; // tabela que esta no sql
    private $filtroCampo; // campo depois de traduzido do que vem da tela
    private $filtroCampoTela; // campo que vem da tela
    private $filtroApelido; // apelido do campo
    private $filtroNomeUnico; // nome criado do campo ja traduzido
    private $filtroTipo; // igual, maior igual, menor igual, maior, menor, entre
    private $filtroTipoValor; // texto, inteiro, numero, data, etc
    private $filtroValor; // array
    private $filtroSituacaoValida;

    public static $arrayCampos = array(
        "usuario" => array (
            "login"    => array("campo" => 'usr_login',    "tipo" => "str", "apelido" => "Login"),
            "email"    => array("campo" => 'usr_email',    "tipo" => "str", "apelido" => "Email"),
            "situacao" => array("campo" => 'usr_situacao', "tipo" => "str", "apelido" => "Situação"),
            "id"       => array("campo" => 'usr_id',       "tipo" => "int", "apelido" => "Id")
        ),
        "despesa" => array (
            "nome"     => array("campo" => 'desp_nome',     "tipo" => "str", "apelido" => "Nome"),
            "valor"    => array("campo" => 'desp_valor',    "tipo" => "num", "apelido" => "Valor"),
            "situacao" => array("campo" => 'desp_situacao', "tipo" => "str", "apelido" => "Situação"),
            "tipo"     => array("campo" => 'desp_tipo',     "tipo" => "str", "apelido" => "Tipo"),
            "id"       => array("campo" => 'desp_id',       "tipo" => "int", "apelido" => "Id")
        ),
        "pessoa" => array (
            "fantasia" => array("campo" => 'pessoa_fantasia', "tipo" => "str", "apelido" => "Nome"),
            "razao"    => array("campo" => 'pessoa_razao',    "tipo" => "str", "apelido" => "Razão"),
            "cpfcnpj"  => array("campo" => 'pessoa_cpf_cnpj', "tipo" => "str", "apelido" => "Cpf/Cnpj"),
            "telefone" => array("campo" => 'pessoa_telefone', "tipo" => "str", "apelido" => "Telefone"),
            "celular"  => array("campo" => 'pessoa_celular',  "tipo" => "str", "apelido" => "Celular"),
            "situacao" => array("campo" => 'pessoa_situacao', "tipo" => "str", "apelido" => "Situação"),
            "valcred"  => array("campo" => 'pessoa_val_cred', "tipo" => "num", "apelido" => "Val. Créd."),
            "tipo_for" => array("campo" => 'pessoa_tipo_for', "tipo" => "str", "apelido" => "Fornecedor"),
            "tipo_fun" => array("campo" => 'pessoa_tipo_fun', "tipo" => "str", "apelido" => "Funcionario"),
            "tipo_psv" => array("campo" => 'pessoa_tipo_psv', "tipo" => "str", "apelido" => "Prest.Serv."),
            "id"       => array("campo" => 'pessoa_id',       "tipo" => "int", "apelido" => "Id")
        ),
        "ponto" => array (
            "data"     => array("campo" => 'ponto_data',     "tipo" => "dat", "apelido" => "Data"),
            "situacao" => array("campo" => 'ponto_situacao', "tipo" => "str", "apelido" => "Situação"),
            "id"       => array("campo" => 'ponto_id',       "tipo" => "int", "apelido" => "Id")
        ),
        "fechamento" => array (
            "data"      => array("campo" => 'fechto_data',      "tipo" => "dat", "apelido" => "Data"),
            "situacao"  => array("campo" => 'fechto_situacao',  "tipo" => "str", "apelido" => "Situação"),
            "descricao" => array("campo" => 'fechto_descricao', "tipo" => "str", "apelido" => "Descrição"),
            "id"        => array("campo" => 'fechto_id',        "tipo" => "int", "apelido" => "Id")
        ),
        "v_contas_pagar" => array (
            "data_vencto" => array("campo" => 'pagar_data_vencto', "tipo" => "dat", "apelido" => "Data Vencto"),
            "data_docto"  => array("campo" => 'pagar_data_docto',  "tipo" => "dat", "apelido" => "Data Docto"),
            "data_pagto"  => array("campo" => 'pagar_data_pagto',  "tipo" => "dat", "apelido" => "Data Pagto"),
            "situacao"    => array("campo" => 'pagar_situacao',    "tipo" => "str", "apelido" => "Situação"),
            "numdoc"      => array("campo" => 'pagar_num_doc',     "tipo" => "str", "apelido" => "Num.Docto"),
            "fantasia"    => array("campo" => 'pessoa_fantasia',   "tipo" => "str", "apelido" => "Favorecido"),
            "despesa"     => array("campo" => 'desp_nome',         "tipo" => "str", "apelido" => "Despesa"),
            "valor"       => array("campo" => 'pagar_valor',       "tipo" => "num", "apelido" => "Valor"),
            "valor_pago"  => array("campo" => 'pagar_valor_pago',  "tipo" => "num", "apelido" => "Valor Pago"),
            "id"          => array("campo" => 'pagar_id',          "tipo" => "int", "apelido" => "Id")
        )
    );

    public function __construct($filtroTabela="", $filtroCampoTela="", $filtroNomeUnico="", $filtroTipo="", $filtroValor="") {  
        $this->filtroTabela         = $filtroTabela;
        $this->filtroCampoTela      = $filtroCampoTela;
        $this->filtroNomeUnico      = $filtroNomeUnico;
        $this->filtroTipo           = $filtroTipo;
        $this->filtroValor          = $filtroValor;
        $this->filtroTipoValor      = self::traduz($filtroTabela, $filtroCampoTela, 'tipo');
        $this->filtroCampo          = self::traduz($filtroTabela, $filtroCampoTela, 'campo');
        $this->filtroApelido        = self::traduz($filtroTabela, $filtroCampoTela, 'apelido');
        $this->filtroSituacaoValida = array('Ok','');
    }

    public function traduz($filtroTabela, $filtroCampoTela, $campo){
        return (strlen($filtroCampoTela) > 0 ? ModFiltro::$arrayCampos[$filtroTabela][$filtroCampoTela][$campo] : '');
    }

    public function adicionaWhere(){

        if ($this->filtroTipo == 'contem'){
            $retornoTxt = $this->filtroTabela.".".$this->filtroCampo." like CONCAT('%', :".$this->filtroNomeUnico.", '%')";
        } else if ($this->filtroTipo == 'inicio_igual') {
            $retornoTxt = $this->filtroTabela.".".$this->filtroCampo." like CONCAT(:".$this->filtroNomeUnico.", '%')";
        } else if ($this->filtroTipo == 'maior') {
            $retornoTxt = $this->filtroTabela.".".$this->filtroCampo." > :".$this->filtroNomeUnico;
        } else if ($this->filtroTipo == 'menor') {
            $retornoTxt = $this->filtroTabela.".".$this->filtroCampo." < :".$this->filtroNomeUnico;
        } else if ($this->filtroTipo == 'maior_igual') {
            $retornoTxt = $this->filtroTabela.".".$this->filtroCampo." >= :".$this->filtroNomeUnico;
        } else if ($this->filtroTipo == 'menor_igual') {
            $retornoTxt = $this->filtroTabela.".".$this->filtroCampo." <= :".$this->filtroNomeUnico;
        } else if ($this->filtroTipo == 'diferente') {
            $retornoTxt = $this->filtroTabela.".".$this->filtroCampo." <> :".$this->filtroNomeUnico;
        } else {
            $retornoTxt = $this->filtroTabela.".".$this->filtroCampo." = :".$this->filtroNomeUnico;
        }

        return $retornoTxt;

    }

    public function bindSql($rs){

        if ($this->filtroTipoValor === 'int'){
            $rs->bindValue(':'.$this->filtroNomeUnico, $this->filtroValor, PDO::PARAM_INT);
        } else {
            $rs->bindValue(':'.$this->filtroNomeUnico, $this->filtroValor, PDO::PARAM_STR);
        }
        
    }

    /**
     * @param ModFiltro[] $filtros
     */
    public static function organizaFiltros($filtros){

        $arrayCamposIguais = array();

        foreach($filtros as $filtro){

            $contaIgual = 0;

            foreach($arrayCamposIguais as $campo){
                $contaIgual += ($filtro->getFiltroCampo() == $campo ? 1 : 0);
            }

            array_push($arrayCamposIguais, $filtro->getFiltroCampo());

            $contaIgual++;
            $filtro->setFiltroNomeUnico($filtro->getFiltroCampo().'_'.$contaIgual);

            self::validaFiltro($filtro);

        }

    }

    /**
     * @param ModFiltro $filtro
     */
    public static function validaFiltro($filtro){
        $filtro->setFiltroSituacaoValida(validaCampo($filtro->getFiltroApelido(), $filtro->getFiltroValor(), $filtro->getFiltroTipoValor(), 'N'));
    }

    /**
     * Get the value of filtroCampo
     */ 
    public function getFiltroCampo()
    {
        return $this->filtroCampo;
    }

    /**
     * Set the value of filtroCampo
     *
     * @return  self
     */ 
    public function setFiltroCampo($filtroCampo)
    {
        $this->filtroCampo = $filtroCampo;

        return $this;
    }

    /**
     * Get the value of filtroTipo
     */ 
    public function getFiltroTipo()
    {
        return $this->filtroTipo;
    }

    /**
     * Set the value of filtroTipo
     *
     * @return  self
     */ 
    public function setFiltroTipo($filtroTipo)
    {
        $this->filtroTipo = $filtroTipo;

        return $this;
    }

    /**
     * Get the value of filtroTipoValor
     */ 
    public function getFiltroTipoValor()
    {
        return $this->filtroTipoValor;
    }

    /**
     * Set the value of filtroTipoValor
     *
     * @return  self
     */ 
    public function setFiltroTipoValor($filtroTipoValor)
    {
        $this->filtroTipoValor = $filtroTipoValor;

        return $this;
    }

    /**
     * Get the value of filtroValor
     */ 
    public function getFiltroValor()
    {
        return $this->filtroValor;
    }

    /**
     * Set the value of filtroValor
     *
     * @return  self
     */ 
    public function setFiltroValor($filtroValor)
    {
        $this->filtroValor = $filtroValor;

        return $this;
    }

    /**
     * Get the value of filtroSituacaoValida
     */ 
    public function getFiltroSituacaoValida()
    {
        return $this->filtroSituacaoValida;
    }

    /**
     * Set the value of filtroSituacaoValida
     *
     * @return  self
     */ 
    public function setFiltroSituacaoValida($filtroSituacaoValida)
    {
        $this->filtroSituacaoValida = $filtroSituacaoValida;

        return $this;
    }

    /**
     * Get the value of filtroTabela
     */ 
    public function getFiltroTabela()
    {
        return $this->filtroTabela;
    }

    /**
     * Set the value of filtroTabela
     *
     * @return  self
     */ 
    public function setFiltroTabela($filtroTabela)
    {
        $this->filtroTabela = $filtroTabela;

        return $this;
    }

    /**
     * Get the value of filtroNomeUnico
     */ 
    public function getFiltroNomeUnico()
    {
        return $this->filtroNomeUnico;
    }

    /**
     * Set the value of filtroNomeUnico
     *
     * @return  self
     */ 
    public function setFiltroNomeUnico($filtroNomeUnico)
    {
        $this->filtroNomeUnico = $filtroNomeUnico;

        return $this;
    }

    /**
     * Get the value of filtroCampoTela
     */ 
    public function getFiltroCampoTela()
    {
        return $this->filtroCampoTela;
    }

    /**
     * Set the value of filtroCampoTela
     *
     * @return  self
     */ 
    public function setFiltroCampoTela($filtroCampoTela)
    {
        $this->filtroCampoTela = $filtroCampoTela;

        return $this;
    }

    /**
     * Get the value of filtroApelido
     */ 
    public function getFiltroApelido()
    {
        return $this->filtroApelido;
    }

    /**
     * Set the value of filtroApelido
     *
     * @return  self
     */ 
    public function setFiltroApelido($filtroApelido)
    {
        $this->filtroApelido = $filtroApelido;

        return $this;
    }
}

?>