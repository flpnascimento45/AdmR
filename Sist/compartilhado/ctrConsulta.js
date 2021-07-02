var ctrConsulta = {
    paginaAtual:  1,
    ordenacao:    ['', ''],
    filtros:      [],
    numRegistros: 50
};

var defTipoFiltros = {
    "int": ["igual","maior_igual","menor_igual","maior","menor","diferente"],
    "str": ["igual","contem","inicio_igual","diferente"],
    "dat": ["igual","maior_igual","menor_igual","maior","menor","diferente"],
    "num": ["igual","maior_igual","menor_igual","maior","menor","diferente"]
};

function definirNumPaginas(totalPaginas){

    let arrPag = [];
    let pagMax = (ctrConsulta.paginaAtual + 3);
    let pagMin = (ctrConsulta.paginaAtual - 3);

    for (let i = pagMin; (i <= pagMax && i <= totalPaginas); i++){
        arrPag.push(i);
    }

    /* remover qtdes negativas */

        arrPag.forEach(n => {

            if (n <= 0){
                pagMax++;
                if (pagMax <= totalPaginas){
                    arrPag.push(pagMax);
                }
            }

        });

        let qtdRemove = pagMax - (ctrConsulta.paginaAtual + 3);
        arrPag.splice(0, qtdRemove);

    /* ----------------------- */

    /* adicionar no começo do array */
    
        let primPos = arrPag[0];

        for (let i = arrPag.length; (i < 7); i++){

            if (primPos > 1){
                primPos--;
                arrPag.unshift(primPos);
            }
            
        }

    /* ----------------------- */

    return arrPag;

}

function montaPaginacao(numTotRegistros){

    let textoPaginacao = '';

    if (numTotRegistros > 0){

        let totalPaginas = 0;

        totalPaginas = parseInt(numTotRegistros / ctrConsulta.numRegistros);

        totalPaginas += (numTotRegistros % ctrConsulta.numRegistros) > 0 ? 1 : 0;

        let txtPagAnt = '<li class="page-item' + 
                            (ctrConsulta.paginaAtual == 1 ? ' disabled' : '') +
                        '"><a class="page-link" pag="ant">Anterior</a></li>';

        let txtPagPrx = '<li class="page-item' + 
                            (ctrConsulta.paginaAtual == totalPaginas ? ' disabled' : '') +
                        '"><a class="page-link" pag="prx">Próximo</a></li>';

        let txtPag = '';

        let arrPaginas = definirNumPaginas(totalPaginas);

        arrPaginas.forEach(vPag => {

            txtPag += '<li class="page-item"><a class="page-link' + 
                          (ctrConsulta.paginaAtual == vPag ? ' pageSel' : '') +
                      '" pag="'+vPag+'">'+vPag+'</a></li>';

        });

        textoPaginacao = `
        <nav>
            <ul class="pagination justify-content-center">
                ${txtPagAnt}
                ${txtPag}
                ${txtPagPrx}
            </ul>
        </nav>
        `;
    }

    $("div.paginacaoTela").html(textoPaginacao);

}

function retornaTextoFiltrar(arrayFiltros){

    let txtFiltros = '<select class="form-control form-control-sm selectFiltro">'+
                        '<option value="">Selecionar</option>';
    arrayFiltros.forEach(filtro => {
        txtFiltros += '<option tipo="'+filtro.tipo+'" value="'+filtro.campo+'">'+filtro.descricao+'</option>';
    });

    txtFiltros += '</select>';

    return txtFiltros;

}

function retornaTextoFiltrado(){

    let txtFiltrado = '';

    ctrConsulta.filtros.forEach((filtro, index) => {
        txtFiltrado += `
        <div class="btn-group ml-1 mt-1">
            <button type="button" class="btn btn-sm btn-outline-danger removeFiltro" posFil="${index}">X</button>
            <button type="button" class="btn btn-sm btn-outline-success">${filtro.descricao} ${filtro.tipo.replace('_',' ')} ${filtro.valor}</button>
        </div>
        `;
    });

    return txtFiltrado;

}

function criarFiltro(arrayFiltros){

    let txtFiltrar  = retornaTextoFiltrar(arrayFiltros);
    let txtFiltrado = retornaTextoFiltrado();

    let txtFiltros = `
        <div class="row">
            <div class="row">
                <div>
                    <h5><span class="oi oi-magnifying-glass"></span> Filtrar</h5><br>
                </div>
                <div class="ml-2">
                    ${txtFiltrar}
                </div>
                <div class="ml-2 criaNovoFiltro">

                </div>
            </div>
            <div class="row">
                ${txtFiltrado}
            </div>
        </div>
    `;

    $("div.filtrosTela").html(txtFiltros);

}

function criarNovoFiltro(campo){

    let opcoesFiltro = defTipoFiltros[campo.tipo];

    let txtOpc = '<div class="row"><select class="form-control form-control-sm opFiltro" style="width: 120px;">';

    opcoesFiltro.forEach(opcao => {
        txtOpc += "<option value='"+opcao+"'>"+opcao.replace('_',' ')+"</option>";
    });

    txtOpc += '</select>';

    if (campo.tipo == 'int'){
        txtOpc += `
            <input type="number" class="form-control form-control-sm valInputFiltro ml-1" style="width: 150px;"/>
        `;
    } else if (campo.tipo == 'dat'){
        txtOpc += `
            <input type="date" class="form-control form-control-sm valInputFiltro ml-1" style="width: 150px;"/>
        `;
    } else if (campo.tipo == 'num') {
        txtOpc += `
            <input type="text" class="form-control form-control-sm valInputFiltro maskdinheiro ml-1" style="width: 150px;"/>
        `;
    } else {
        txtOpc += `
            <input type="text" class="form-control form-control-sm valInputFiltro ml-1" style="width: 150px;"/>
        `;
    }

    txtOpc += '<button type="button" class="btn btn-sm btn-outline-success confirmaFiltro ml-1"><span class="oi oi-check"></span></button></div>';

    $(".filtrosTela .criaNovoFiltro").html(txtOpc);

}

function removeFiltro(posicao){
    
    posicao = parseInt(posicao);

    ctrConsulta.filtros = ctrConsulta.filtros.filter(function(item, index) { 
        
        return posicao !== index; 
    });

    ctrConsulta.paginaAtual = 1;
    
    processaPagina();

}

function alteraOrdenacao(){
    
    $('.montaTabela table thead td.ordenacao').each(function() {
        
        if ($(this).attr('tipo') == ctrConsulta.ordenacao[0]){
            
            if (ctrConsulta.ordenacao[1] == 'desc'){
                $(this).attr('tipord','C');
                $(this).append(' <span style="font-size: 10px;" class="oi oi-chevron-bottom ordenacaoSpan"></span>');
            } else {
                $(this).attr('tipord','D');
                $(this).append(' <span style="font-size: 10px;" class="oi oi-chevron-top ordenacaoSpan"></span>');
            }

        }

    });

}

$(document).ready(function(){

    $(".paginacaoTela").on('click','.page-link',function(){

        let pagClick = $(this).attr('pag');
        
        if (pagClick !== ctrConsulta.paginaAtual){

            if (pagClick == 'ant' && ctrConsulta.paginaAtual > 1){
                ctrConsulta.paginaAtual--;
            } else if (pagClick == 'prx'){
                ctrConsulta.paginaAtual++;
            } else {
                ctrConsulta.paginaAtual = parseInt(pagClick);
            }

            processaPagina();
        }

    });

    $('.filtrosTela').on('change','select.selectFiltro',function(){
    
        let opSel = $(this).find('option:selected');

        if (opSel.val() !== ''){
            
            let campo = {campo: opSel.val(), tipo: opSel.attr('tipo'), descricao: opSel.html()};

            criarNovoFiltro(campo);

        } else {
            $(".filtrosTela .criaNovoFiltro").html('');
        }

    });

    $('.filtrosTela').on('click','button.confirmaFiltro',function(){

        let opSel = $('.filtrosTela select.selectFiltro option:selected');
        let opFil = $('.filtrosTela select.opFiltro option:selected');
        let vlSel = $('.filtrosTela .valInputFiltro').val();

        let campo = {campo: opSel.val(), tipo: opFil.val(), descricao: opSel.html(), valor: vlSel};
        
        ctrConsulta.filtros.push(campo);

        ctrConsulta.paginaAtual = 1;

        processaPagina();

    });

    $('.filtrosTela').on('click','button.removeFiltro', function(){
        removeFiltro($(this).attr('posFil'));
    });

    $('.montaTabela').on('click','td.ordenacao',function(){
        
        $(this).find('span.ordenacaoSpan').remove();

        var ord            = $(this).attr('tipo');
        var tipoOrd        = $(this).attr('tipord');

        ctrConsulta.ordenacao = [ord, (tipoOrd == 'D' ? 'desc' : 'asc')];

        processaPagina();

    });

});