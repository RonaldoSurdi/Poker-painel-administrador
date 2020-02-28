$('.select2').select2();
$('.div_ok').hide();
$('#btn_fim').hide();
$('#lbl_tot').html( numberToReal( parseFloat( $('#val_tot').val() ) ));



function PayClear() {
    $('#type_id').val(0);
    $('#pay_type').val(1).trigger('change');
    $('#pay_value').val( $('#val_falta').val() );
    //
    HideExCard();
    $('#card_nro').val('');
    $('#card_name').val('');
    $('#card_validate').val('');
    $('#card_ccv').val('');
    $('#card_parc').val(10).trigger('change');

    $('#btn_del').hide();

    if ($('#val_falta').val()>0){
        $('.div_ok').hide();
        $('#btn_fim').hide();
        $('#div_falta').show();
        $('#div_pagamento').show();
    }else{
        $('.div_ok').show();
        $('#btn_fim').show();
        $('#div_falta').hide();
        $('#div_pagamento').hide();

        if ($('#temboleto').val()>0)
            $('.boleto').show();
        if ($('#temdeposito').val()>0)
            $('.deposito').show();

        if ($('#tapago').val()==1) {
            $('.mPago').show();
            $('.mPendente').hide();
            $('#LblTotalizado').hide();
        }else{
            $('.mPago').hide();
            $('.mPendente').show();
            $('#LblTotalizado').show();
        }
    }
}
PayClear();

function PayType() {
    // $('.boleto').hide();
    $('.deposito').hide();
    $('.cartao').hide();
    $('.email').hide();
    $('.due_date').hide();


    var opt = $('#pay_type').val();

    if ( (opt==1) ) {
        $('.cartao').show();
        $('#card_parc').val(10).trigger('change');
        $('.card_parc').show();
        $('#card_brand').prop('disabled', false);
    }
    if ( (opt==5) ) {
        $('.cartao').show();
        $('#card_parc').val(1).trigger('change');
        $('.card_parc').hide();
        // $('#card_brand').prop('disabled', 'disabled');
    }

    if (opt==2) {
        $('.email').show();
        $('.due_date').show();
    }
    if (opt==3) {
        $('.deposito').show();
        $('.due_date').show();
    }

    $('.ico_pay').removeClass('use');
    $('#ico_pay'+opt).addClass('use');
}

function payChange(opt){
    $('#pay_type').val(opt).trigger('change');
}


function CardParc() {
    var parc = ( $('#card_parc').val() );
    var pago = ( $('#pay_value').val() );
    var mes = pago / parc;
    mes = parseFloat(mes.toFixed(2)); //2 casas decimais
    $('#card_val_parc').val( numberToReal(mes) );
}

function HideExCard() {
    $('#ex_card').hide();
}

$( ".cardEx" ).focusin(function() {
    $('#ex_card').show();
}).focusout(function() {
    $('#ex_card').hide();
});

function return_path() {
    if ($('#btn_url').length!=0){
        $('#btn_url').trigger('click');
    }
}
//
function Paylist(id) {
    if (!id) id=0;
    $('.boleto').hide();
    $('.deposito').hide();
    $.getJSON(
        '/api/pay/'+$('#idd').val()+'/types'
        ,function (data) {
            $('#lista').html(data.html);

            //Calcula o valor restante
            var total = ( $('#val_tot').val() );
            var pago = ( $('#tot_pago').val() );
            var rest = total - pago;
            rest = parseFloat(rest.toFixed(2)); //2 casas decimais
            $('#val_falta').val( rest );
            $('#lbl_falta').html( numberToReal( rest ) );

            PayClear();

            if (id>0)
                PayLoad(id);
        }
    ).fail(function (data) {
        console.log(data);
        aviso('warning','Não foi possivel consultar os pagamentos');
    });
}
Paylist();


function PaySubmit() {
    $('#btn_save').html('<i class="fa fa-spinner fa-spin"></i>')
    $('#btn_save').prop('disabled', 'disabled');

    //Liberar a bandeira do cartao
    $('#card_brand').prop('disabled', false);

    if ($('#type_id').val()==0) {
        var pago = parseFloat($('#pay_value').val());
        var rest = parseFloat($('#val_falta').val());
        if (pago <= 0) {
            aviso('warning', 'Informe um valor maior que zero');

            $('#btn_save').html('Pagar');
            $('#btn_save').prop('disabled', false);
            return false;
        }
    }

    if (pago > rest) {
        aviso('warning', 'Valor informado ' + pago + ' é maior que o que "Falta pagar" ' + rest);
        $('#btn_save').html('Pagar');
        $('#btn_save').prop('disabled', false);
        return false;
    }

    //Salvar
    $.ajax({
        url: '/pay/type/store',
        type: 'post',
        dataType: 'json',
        data: $('#frm_cad').serialize(),
        success: function(data) {
            if (data.result=='N'){
                aviso('warning',data.message,'Atenção');
                Paylist();
                $('#btn_save').html('Pagar');
                $('#btn_save').prop('disabled',false);
                return false;
            }

            /**** Se for Cartão ****/
            if ( ($('#pay_type').val() == 1) || ($('#pay_type').val() == 5) ) {
                Cielo(data.id);
            }else{
                aviso('success','Pagamento adicionado');
                PayClear();
                Paylist();
                $('#btn_save').html('Pagar');
                $('#btn_save').prop('disabled',false);
            }
        }
        ,error: function(XMLHttpRequest, textStatus, errorThrown){
            /**** deu erro na requisição web *****/
            aviso('error',tratarErroAjax(XMLHttpRequest),'Atenção!');
            //
            $('#btn_save').html('Pagar');
            $('#btn_save').prop('disabled',false);
            Paylist();
        }
    });
}

function Cielo(id) {
    $('#type_id').val(id);
    Alert_load('Enviando venda para sua operadora de cartão');

    $.ajax({
        url: '/pay/cielo/send',
        type: 'post',
        dataType: 'json',
        data: $('#frm_cad').serialize(),
        success: function(data) {
            if (data.result=='N'){
                Alert_error(data.message);
                //
                Paylist(id);
                //
                $('#btn_save').html('Pagar');
                $('#btn_save').prop('disabled',false);
                return false;
            }
            Alert_time(data.message);
            Paylist();

            $('#btn_save').html('Pagar');
            $('#btn_save').prop('disabled',false);

        }
        ,error: function(XMLHttpRequest, textStatus, errorThrown){
            /**** deu erro na requisição web *****/
            //aviso('error',tratarErroAjax(XMLHttpRequest),'Atenção!');
            Alert_error(tratarErroAjax(XMLHttpRequest));
            //
            $('#btn_save').html('Pagar');
            $('#btn_save').prop('disabled',false);
            Paylist(id);
        }
    });

}


function DelPay(id) {
    $('#type_id').val(id);
    //Salvar
    $.ajax({
        url: '/pay/type/delete',
        type: 'post',
        dataType: 'json',
        data: $('#frm_cad').serialize(),
        success: function(data) {
//                    console.log(data);
            aviso('success','Pagamento Removido');
            Paylist();
        }
        ,error: function(XMLHttpRequest, textStatus, errorThrown){
            /**** deu erro na requisição web *****/
            aviso('warning',tratarErroAjax(XMLHttpRequest),'Atenção!');
            Paylist();
        }
    });
}

function AskDelPay(id){
    if (!id) id=0;
    if (id==0) id = $('#type_id').val();
    swal({
        text: 'Deseja excluir este pagamento?',
        type: "question",
        confirmButtonText: "Sim",
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        confirmButtonColor:'#20895e',
        cancelButtonColor: '#d33',
        showCancelButton: true,
        cancelButtonText: "Não",
        closeOnConfirm: false,
        closeOnCancel: true,
        showLoaderOnConfirm: true,
        allowOutsideClick: false,
        preConfirm: function() {
            return DelPay(id);
        }
    });
}

function PayLoad(id){
    $.getJSON(
        '/api/pay/loadtype/'+id
        ,function (data) {
            PayClear();
            if (data.result=='N'){
                aviso('warning',data.message);
                return false;
            }

            aviso('success','Pagamento carregado, efetue as alterações e clique em Pagar para salvar.');
            $('#type_id').val(id);
            $('#pay_type').val(data.pay_type).trigger('change');
            $('#pay_value').val(data.pay_value);
            $('#email').val(data.email);

            if (data.card==1){
                $('#card_nro').val(data.card_nro).trigger('keyup');
                $('#card_name').val(data.card_name);
                $('#card_validate').val(data.card_validate);
                $('#card_ccv').val(data.card_ccv);
                $('#card_brand').val(data.card_brand).trigger('change');
                $('#card_parc').val(data.card_parc).trigger('change');
            }

            $('.div_ok').hide();
            $('#btn_fim').hide();
            $('#div_pagamento').show();
            $('#btn_del').show();
        }
    ).fail(function (data) {
        console.log(data);
        aviso('warning','Não foi possivel carregar o pagamento');
    });
}

function ImprimirBoleto(){
    $.getJSON(
        '/api/pay/boleto/'+$('#temboleto').val()
        ,function (data) {
            console.log(data.hash);
            if (data.result=='N'){
                aviso('warning',data.message);
                return false;
            }
            //$('#modalFull').modal('show');
            //window.open('/boleto/'+data.hash,'frame');
            window.open('/boleto/'+data.hash,'_blank');
        }
    ).fail(function (data) {
        console.log(data);
        aviso('warning','Não foi possivel carregar o pagamento');
    });
}

$(document).ready(function() {
    $('#modalFull').on('hidden.bs.modal', function () {
        window.open('/load','frame');
        // location.reload();
})});

/////////////////////////////////////////////////////////////////////////////////////////////////////


// This is for the sticky sidebar
$(".stickyside").stick_in_parent({
    offset_top: 0
});
$('.stickyside a').click(function() {
    $('html, body').animate({
        scrollTop: $($(this).attr('href')).offset().top - 100
    }, 500);
    return false;
});
// This is auto select left sidebar
// Cache selectors
// Cache selectors
var lastId,
    topMenu = $(".stickyside"),
    topMenuHeight = topMenu.outerHeight(),
    // All list items
    menuItems = topMenu.find("a"),
    // Anchors corresponding to menu items
    scrollItems = menuItems.map(function() {
        var item = $($(this).attr("href"));
        if (item.length) {
            return item;
        }
    });

// Bind click handler to menu items


// Bind to scroll
$(window).scroll(function() {
    // Get container scroll position
    var fromTop = $(this).scrollTop() + topMenuHeight - 250;

    // Get id of current scroll item
    var cur = scrollItems.map(function() {
        if ($(this).offset().top < fromTop)
            return this;
    });
    // Get the id of the current element
    cur = cur[cur.length - 1];
    var id = cur && cur.length ? cur[0].id : "";

    if (lastId !== id) {
        lastId = id;
        // Set/remove active class
        menuItems
            .removeClass("active")
            .filter("[href='#" + id + "']").addClass("active");
    }
});

