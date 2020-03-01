function PagarConta(id,valor){
    $('#id').val(id);
    $('#valor1').val(valor);
    $('#valor_pay').val(valor);
    CalcRestante();
    $('#modalPag').modal('show');
}

function ContaPaga() {
    $('#ico_save').addClass('fa-spin fa-spinner').removeClass('fa-floppy-o');
    return true;
}

//
function Restante(){
    if ($('#ck_rest').is(':checked')) {
        $('.novo').show();
    }else{
        $('.novo').hide();
    }
}
function CalcRestante() {
    var valor_orig = $('#valor1').val();
    var valor_pago = $('#valor_pay').val();
    var valor_rest = valor_orig - valor_pago;
    valor_rest = valor_rest.toFixed(2);
    $('#valor_rest').val(valor_rest);
    if (valor_rest>0){
        $('.ckrest').show();
        Restante();
    }else {
        $('.ckrest').hide();
        $('.novo').hide();
    }
}
CalcRestante();