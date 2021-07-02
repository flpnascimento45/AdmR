function dataAtualFormatada(formato=0){
    let data = new Date(),
        dia  = data.getDate().toString().padStart(2, '0'),
        mes  = (data.getMonth()+1).toString().padStart(2, '0'),
        ano  = data.getFullYear();
    
    let retorno = dia+"/"+mes+"/"+ano;

    if (formato == 1){
        retorno = ano+"-"+mes+"-"+dia;
    }
    return retorno;
}