$(document).ready(function () {

	function isMobile(){

		var userAgent = navigator.userAgent.toLowerCase();
		
		if( userAgent.search(/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i)!= -1 )
			return true;

	}

	new gnMenu( document.getElementById( 'gn-menu' ));
	
	$("#sairSistema").click(function(){

        $.ajax({         
            url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
            dataType: 'json',
            type: 'POST',
            data: {oper: 3},
            beforeSend: function(){
                $.blockUI({ message: '<p>Aguarde...</p>' });
            },
            success: function(dados){

                $.unblockUI();
                
                if (dados){
                    $(location).attr('href', '../Login/');
                }
                
            },
            error: function(dadosErro){
                $.unblockUI();
                console.log(dadosErro);
                $(location).attr('href', '/AdmR/Login/');
            }
        });

    });

	if (isMobile()){
		$(".some-mobile").remove();
	} else {
		$(".some-desktop").remove();
    }
    
    $.ajax({         
        url: '/AdmR/Servidor/Requisicao/requisicaoAdm.php',
        dataType: 'json',
        type: 'POST',
        data: {oper: 4},
        success: function(dados){
            
            dados.forEach(acesso => {
                
                if (acesso.acessoValor == 'N'){
                    $("#menu"+acesso.acessoCodigo).remove();
                }

            });
            
        },
        error: function(dadosErro){
            console.log(dadosErro);
            $(location).attr('href', '/AdmR/Login/');
        }
    });

});