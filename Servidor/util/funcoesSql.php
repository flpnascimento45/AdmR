<?php

require_once $GLOBALS['pasta_raiz'].'/Servidor/Modelo/ModFiltro.php'; 

function retornaFiltros($dadosFiltro, $tabela){

    $retorno = array("Ok",array());

    $arrayFiltros = array();

    foreach($dadosFiltro as $filtro){
        $modFiltro  = new ModFiltro($tabela, $filtro['campo'], '', $filtro['tipo'], $filtro['valor']);
        array_push($arrayFiltros, $modFiltro);
    }
    
    if (count($arrayFiltros) > 0){

        ModFiltro::organizaFiltros($arrayFiltros);

        $msgRet = '';

        foreach($arrayFiltros as $filtro){
            
            $arrSit = $filtro->getFiltroSituacaoValida();

            if ($arrSit[0] == "Erro"){
                $msgRet .= $arrSit[1].'<br>';
            }

        }

        if (strlen($msgRet) > 0){
            $retorno  = array("Erro", $msgRet);
        }

    }
    
    if ($retorno[0] == "Ok"){
        $retorno[1] = $arrayFiltros;
    }
    
    return $retorno;

}

function retornaOrdenacaoLimit($paramsConsulta, $tabela){

    $sqlCompl = '';

    if (strlen($paramsConsulta['ordenacao'][0]) > 0){
        $campo = ModFiltro::$arrayCampos[$tabela][$paramsConsulta['ordenacao'][0]];
        $sqlCompl .= ' order by '.$campo['campo'].' '.$paramsConsulta['ordenacao'][1];
    }

    $paginacao = $paramsConsulta['numRegistros'];
    $limite1   = $paramsConsulta['paginaAtual'] * $paginacao - $paginacao;

    $sqlCompl .= ' LIMIT '.$limite1.','.$paginacao.';';
    
    return $sqlCompl;

}

?>