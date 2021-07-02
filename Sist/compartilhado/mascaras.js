$(document).on("focus", ".maskdinheiro", function() { 
    $(this).maskMoney({thousands:'', decimal:',', allowZero:true, allowNegative: false});
});

$(document).on("focus", ".maskdinheironeg", function() { 

   $(this).maskMoney({thousands:'', decimal:',', allowZero:true, allowNegative: true});
   
});

 $(document).on("focus", ".maskcpf", function() { 
    $(this).mask('000.000.000-00');
 });

 $(document).on("focus", ".maskcnpj", function() { 
   $(this).mask('00.000.000/0000-00');
});

 $(document).on("focus", ".maskcpfcnpj", function() { 
   
   let vltmp = $(this).val().replace(/[//\.-]/g,'');

   if (vltmp.length > 11) {
      $(this).mask('00.000.000/0000-00');
   } else {
      $(this).mask('000.000.000-00');
   }
   
});

$(document).on("keydown", ".maskcpfcnpj", function() {
   $(this).unmask();
});

$(document).on("keyup", ".maskcpfcnpj", function() {

   let vltmp = $(this).val().replace(/[//\.-]/g,'');
   
   if (vltmp.length > 11) {
      $(this).mask('00.000.000/0000-00');
   } else {
      $(this).mask('000.000.000-00');
   }
   
});

 $(document).on("focus", ".masktelefone", function() { 
    $(this).mask('(00)0000-0000');
 });

 $(document).on("focus", ".maskcelular", function() { 
    $(this).mask('(00)00000-0000');
 });

 $(document).on("focus", ".maskcep", function() { 
    $(this).mask('00000-000');
 });

 $(document).on("focus", ".maskdata", function() { 
    $(this).mask('00/00/0000');
 });

 $(document).on("focus", ".masktelsemarea", function() { 
    $(this).mask('0 0000-0000');
 });