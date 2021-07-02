$('body').append(
    '<div class="modal fade" id="modalOk" tabindex="-1" role="dialog" style="z-index: 9999999999;">'+
        '<div class="modal-dialog" role="document">'+
            '<div class="modal-content">'+
                '<div class="modal-body">'+
                '</div>'+
                '<div class="modal-footer">'+
                    '<button type="button" class="btn btn-outline-primary" id="btnModal">Ok</button>'+
                '</div>'+
            '</div>'+
        '</div>'+
    '</div>'+
    '<div class="modal fade" id="modalConfirmacao" tabindex="-1" role="dialog" style="z-index: 9999999999;">'+
        '<div class="modal-dialog" role="document">'+
            '<div class="modal-content">'+
                '<div class="modal-body">'+
                '</div>'+
                '<div class="modal-footer">'+
                    '<button type="button" class="btn btn-outline-primary" id="modalConfirmacaoBtnSim">Sim</button>'+
                    '<button type="button" class="btn btn-outline-primary" id="modalConfirmacaoBtnNao">Não</button>'+
                '</div>'+
            '</div>'+
        '</div>'+
    '</div>'+
    '<div class="toast" role="alert" id="toastMensagem" aria-live="assertive" aria-atomic="true" data-delay="2000" '+
    'style="position: absolute; top: 0; margin-left: 40%; margin-top: 40vh;">'+
        '<div class="toast-header">'+
            '<strong class="mr-auto"></strong>'+
            '<small class="text-muted"></small>'+
            '<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">'+
            '<span aria-hidden="true">&times;</span>'+
            '</button>'+
        '</div>'+
        '<div class="toast-body">'+
        '</div>'+
    '</div>'
);

var modalOk = function(callback, textoMensagem){
    
    $("#modalOk div.modal-body").html(textoMensagem);

    $("#modalOk").modal('show');
    
    $("#btnModal").off('click').on("click",function(){
        callback(true);
        $('#modalOk').modal('hide');
    });

};

var modalConfirm = function(callback, textoMensagem, arrParams=[], btnSim="Sim", btnNao="Não"){
    
    $("#modalConfirmacaoBtnSim").html(btnSim);
    $("#modalConfirmacaoBtnNao").html(btnNao);

    $("#modalConfirmacao div.modal-body").html(textoMensagem);

    $("#modalConfirmacao").modal('show');
    
    $("#modalConfirmacaoBtnSim").off('click').on("click", function(){

        if (arrParams.length > 0){
            callback(true, arrParams);
        } else {
            callback(true);
        }
        
        $("#modalConfirmacao").modal('hide');
    });
    
    $("#modalConfirmacaoBtnNao").off('click').on("click", function(){
        
        if (arrParams.length > 0){
            callback(false, arrParams);
        } else {
            callback(false);
        }

        $("#modalConfirmacao").modal('hide');
    });

};

var toastMensagem = function(textoMensagem, cabecalho, corCabechalho=""){

    $("#toastMensagem").css('z-index', '9999999999');

    if (corCabechalho != ""){
        $("#toastMensagem div.toast-header").css('background-color',corCabechalho);
    }

    $("#toastMensagem strong.mr-auto").html(cabecalho);
    $("#toastMensagem div.toast-body").html(textoMensagem);
    $('#toastMensagem').toast('show');
}