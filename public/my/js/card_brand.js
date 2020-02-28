var
    // the regular expressions check for possible matches as you type, hence the OR operators based on the number of chars
    // Visa
    visa_regex = new RegExp('^4[0-9]{0,15}$'),

    // MasterCard
    mastercard_regex = new RegExp('^5$|^5[1-5][0-9]{0,14}$'),

    // American Express
    amex_regex = new RegExp('^3$|^3[47][0-9]{0,13}$'),

    // Diners Club
    diners_regex = new RegExp('^3$|^3[068]$|^3(?:0[0-5]|[68][0-9])[0-9]{0,11}$'),

    //Discover
    discover_regex = new RegExp('^6$|^6[05]$|^601[1]?$|^65[0-9][0-9]?$|^6(?:011|5[0-9]{2})[0-9]{0,12}$'),

    //JCB
    jcb_regex = new RegExp('^2[1]?$|^21[3]?$|^1[8]?$|^18[0]?$|^(?:2131|1800)[0-9]{0,11}$|^3[5]?$|^35[0-9]{0,14}$');

// as the user types
$('#card_nro').keyup(function(){
    var cur_val = $(this).val();
    var card = 0;

    // get rid of spaces and dashes before using the regular expression
    cur_val = cur_val.replace(/ /g,'').replace(/-/g,'');

    // checks per each, as their could be multiple hits
    if ( cur_val.match(visa_regex) ) {
        card = 1;
    }

    if ( cur_val.match(mastercard_regex) ) {
        card = 2;
    }

    if ( cur_val.match(amex_regex) ) {
        card = 3;
    }

    if ( cur_val.match(jcb_regex) ) {
        card = 6;
    }

    if ( cur_val.match(diners_regex) ) {
        card = 7;
    }

    if ( cur_val.match(discover_regex) ) {
        card = 8;
    }

    $('#card_brand').val(card).trigger('change');
});

function ImgBrand(){
    $('.brands').hide();
    var card = $('#card_brand').val();
    if (card==1) $('.card_visa').show();
    if (card==2) $('.card_mastercard').show();
    if (card==3) $('.card_amex').show();
    if (card==4) $('.card_elo').show();
    if (card==5) $('.card_aura').show();
    if (card==6) $('.card_jcb').show();
    if (card==7) $('.card_diners').show();
    if (card==8) $('.card_discover').show();
    if (card==9) $('.card_hiper').show();
}
ImgBrand();