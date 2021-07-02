<?php

$GLOBALS['pasta_raiz'] = $_SERVER['DOCUMENT_ROOT'].'/AdmR';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    if (!in_array($_POST['oper'], array(1,2,3))){

        if (!isset($_SESSION)){
            session_start();
        }
    
        if (!isset($_SESSION['usuario'])){
            session_destroy();
            echo '<meta http-equiv="refresh" content="0; URL='."'".'/AdmR/Login'."'".'"/>';
        }
    
    }
    
    require_once $GLOBALS['pasta_raiz'].'/Servidor/Controle/ControleUsuario.php'; 
    require_once $GLOBALS['pasta_raiz'].'/Servidor/Controle/ControleDespesa.php'; 
    require_once $GLOBALS['pasta_raiz'].'/Servidor/Controle/ControlePessoa.php'; 
    require_once $GLOBALS['pasta_raiz'].'/Servidor/Controle/ControlePonto.php'; 
    require_once $GLOBALS['pasta_raiz'].'/Servidor/Controle/ControleFechamento.php'; 
    require_once $GLOBALS['pasta_raiz'].'/Servidor/Controle/ControleContasPagar.php'; 

    $operacao = $_POST['oper'];

    switch ($operacao) {
        case 1:
            entrarSistema();
        break;
        case 2:
            verificaLogado();
        break;
        case 3:
            finalizarSessao();
        break;
        case 4:
            retornaAcessos();
        break;
        case 5:
            listaUsuarios();
        break;
        case 6:
            alterarUsuario();
        break;
        case 7:
            consultarUsuario();
        break;
        case 8:
            altIncUsuario();
        break;
        case 9:
            listarDespesas();
        break;
        case 10:
            consultarDespesa();
        break;
        case 11:
            altIncDespesa();
        break;
        case 12:
            listarPessoas();
        break;
        case 13:
            consultarPessoa();
        break;
        case 14:
            altIncPessoa();
        break;
        case 15:
            altIncPessoaDespesa();
        break;
        case 16:
            inativaPessoaDespesa();
        break;
        case 17:
            listarPonto();
        break;
        case 18:
            consultarPonto();
        break;
        case 19:
            altIncPonto();
        break;
        case 20:
            confirmaPonto();
        break;
        case 21:
            listarFechamento();
        break;
        case 22:
            consultarFechamento();
        break;
        case 23:
            altIncFechto();
        break;
        case 24:
            cancelaFechto();
        break;
        case 25:
            listarPessoasDespesas();
        break;
        case 26:
            listarContasPagar();
        break;
        case 27:
            consultarContasPagar();
        break;
        case 28:
            incluirFechtoPessoa();
        break;
        case 29:
            listarFechamentoPessoa();
        break;
        case 30:
            deletarFechtoPessoa();
        break;
        case 31:
            finalizarFechto();
        break;
        case 32:
            listarPessoasAtivas();
        break;
        case 33:
            alteraConta();
        break;
        case 34:
            incluirConta();
        break;
    }

} else {
    http_response_code(403);
    die('<h2>Erro 403</h2>');
}

function entrarSistema(){

    $controleUsuario = new ControleUsuario();

    $retorno = $controleUsuario->entrarSistema($_POST);

    echo json_encode($retorno);

}

function verificaLogado(){

    $retVld = false;

    if (!isset($_SESSION)){
        session_start();
    }

    if (isset($_SESSION)){
        
        if (isset($_SESSION['usuario'])){

            if (isset($_SESSION['usuario']['usrId'])){
                    
                if ($_SESSION['usuario']['usrId'] > 0){
                    $retVld = true;
                } else {
                    session_destroy();
                }

            } else {
                session_destroy();
            }

        } else {
            session_destroy();
        }

    }

    echo json_encode($retVld);

}

function finalizarSessao() {

    if(!isset($_SESSION)) {
        session_start();
    }
    
    session_destroy();

}

function retornaAcessos() {

    $retornoAce = array();

    if (!isset($_SESSION)){
        session_start();
    }

    if (isset($_SESSION)){
        
        if (isset($_SESSION['usuario'])){
            $retornoAce = $_SESSION['usuario']['acessos'];
        }

    }

    echo json_encode($retornoAce);

}

function listaUsuarios(){

    $controleUsuario = new ControleUsuario();

    $retorno = $controleUsuario->listarUsuarios($_POST);

    echo json_encode($retorno);

}

function alterarUsuario(){

    $controleUsuario = new ControleUsuario();

    $retorno = $controleUsuario->altIncUsuario($_POST);

    echo json_encode($retorno);

}

function consultarUsuario(){

    $controleUsuario = new ControleUsuario();

    $retorno = $controleUsuario->consultarUsuario($_POST);

    echo json_encode($retorno);

}

function altIncUsuario(){

    $controleUsuario = new ControleUsuario();

    $retorno = $controleUsuario->altIncUsuario($_POST);

    echo json_encode($retorno);

}

function listarDespesas(){

    $controle = new ControleDespesa();

    $retorno = $controle->listar($_POST);

    echo json_encode($retorno);

}

function consultarDespesa(){

    $controle = new ControleDespesa();

    $retorno = $controle->consultar($_POST);

    echo json_encode($retorno);

}

function altIncDespesa(){

    $controle = new ControleDespesa();

    $retorno = $controle->altIncDespesa($_POST);

    echo json_encode($retorno);

}

function listarPessoas(){

    $controle = new ControlePessoa();

    $retorno = $controle->listar($_POST);

    echo json_encode($retorno);

}

function consultarPessoa(){

    $controle = new ControlePessoa();

    $retorno = $controle->consultar($_POST);

    echo json_encode($retorno);

}

function altIncPessoa(){

    $controle = new ControlePessoa();

    $retorno = $controle->altIncPessoa($_POST);

    echo json_encode($retorno);

}

function altIncPessoaDespesa(){

    $controle = new ControlePessoa();

    $retorno = $controle->altIncPessoaDespesa($_POST);

    echo json_encode($retorno);

}

function inativaPessoaDespesa(){

    $controle = new ControlePessoa();

    $retorno = $controle->inativarPessoaDespesa($_POST);

    echo json_encode($retorno);

}

function listarPonto(){

    $controle = new ControlePonto();

    $retorno = $controle->listar($_POST);

    echo json_encode($retorno);

}

function consultarPonto(){

    $controle = new ControlePonto();

    $retorno = $controle->consultar($_POST);

    echo json_encode($retorno);

}

function altIncPonto(){

    $controle = new ControlePonto();

    $retorno = $controle->altIncPonto($_POST);

    echo json_encode($retorno);

}

function confirmaPonto(){

    $controle = new ControlePonto();

    $retorno = $controle->confirmaPonto($_POST);

    echo json_encode($retorno);

}

function listarFechamento(){

    $controle = new ControleFechamento();

    $retorno = $controle->listar($_POST);

    echo json_encode($retorno);

}

function consultarFechamento(){

    $controle = new ControleFechamento();

    $retorno = $controle->consultar($_POST);

    echo json_encode($retorno);

}

function altIncFechto(){

    $controle = new ControleFechamento();

    $retorno = $controle->altIncFechto($_POST);

    echo json_encode($retorno);

}

function cancelaFechto(){

    $controle = new ControleFechamento();

    $retorno = $controle->cancelaFechto($_POST);

    echo json_encode($retorno);

}

function listarPessoasDespesas(){

    $controle = new ControleFechamento();

    $retorno = $controle->listarPessoasDespesas($_POST);

    echo json_encode($retorno);

}

function listarContasPagar(){

    $controle = new ControleContasPagar();

    $retorno = $controle->listar($_POST);

    echo json_encode($retorno);

}

function consultarContasPagar(){

    $controle = new ControleContasPagar();

    $retorno = $controle->consultar($_POST);

    echo json_encode($retorno);

}

function incluirFechtoPessoa(){

    $controle = new ControleFechamento();

    $retorno = $controle->incluirFechtoPessoa($_POST);

    echo json_encode($retorno);

}

function listarFechamentoPessoa(){

    $controle = new ControleFechamento();

    $retorno = $controle->listarFechamentoPessoa($_POST);

    echo json_encode($retorno);

}

function deletarFechtoPessoa(){

    $controle = new ControleFechamento();

    $retorno = $controle->deletarFechtoPessoa($_POST);

    echo json_encode($retorno);

}

function finalizarFechto(){

    $controle = new ControleFechamento();

    $retorno = $controle->finalizarFechto($_POST);

    echo json_encode($retorno);

}

function listarPessoasAtivas(){

    $controle = new ControlePessoa();

    $retorno = $controle->listarAtivos($_POST);

    echo json_encode($retorno);

}

function alteraConta(){

    $controle = new ControleContasPagar();

    $retorno = $controle->alteraConta($_POST);

    echo json_encode($retorno);

}

function incluirConta(){

    $controle = new ControleContasPagar();

    $retorno = $controle->incluirConta($_POST);

    echo json_encode($retorno);

}

?>