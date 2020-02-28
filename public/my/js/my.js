function tratarErroAjax(XMLHttpRequest){
    message = 'Ops! O servidor não respondeu a sua solicitação';

    if (XMLHttpRequest.responseText){
        console.log(XMLHttpRequest.responseText);
        response =  JSON.parse( XMLHttpRequest.responseText );
        if (response.message) {
            message = response.message;
        }
    }else{
        console.log(XMLHttpRequest);
    }
    return message;
}

function numberToReal(numero) {
    var numero = numero.toFixed(2).split('.');
    numero[0] = numero[0].split(/(?=(?:...)*$)/).join('.');
    return numero.join(',');
}

