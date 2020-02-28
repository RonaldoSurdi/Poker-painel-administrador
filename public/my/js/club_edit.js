//$('.address').hide();

$('#btn_doc1').click(function() {
    ConsultaDoc('doc1');
});

// $('#zipcode').keyup(function() {
//     if (!$('.address').is( ":visible" ) ) {
//         ConsultaCep('zipcode');
//     }
// });
$('#btn_zipcode').click(function() {
    ConsultaCep('zipcode');
});


//troca o enter pelo TAB entre os campos
// Simulate tab key when enter is pressed
$(document).ready(function() {
    $('input:text:first').focus();
    $('input:text').bind("keydown", function(e) {
        if (e.which == 13) { //tecla enter keycode =13
            e.preventDefault(); //nao executa evento padrao
            var nextIndex = $('input:text').index(this) + 1;
            $('input:text')[nextIndex].focus();
        }
    });
    $('#btnReset').click(
        function() {
            $('form')[0].reset();
        });
});


function iconSpinner(mostrar,obj) {
    if (mostrar) {
        $("#ico_"+obj).addClass('fa-spin fa-spinner').removeClass('fa-search');
    }else{
        $("#ico_"+obj).removeClass('fa-spin fa-spinner').addClass('fa-search');
    }
}

function ConsultaCep(obj){
    //carregar os dados
    var cep = $("#"+obj).val();

    if (cep.length<8) {
        //$("."+obj+"address").hide();
        $("#lbl_"+obj).html('Informe um CEP Válido');
    }else {
        iconSpinner(true,obj);
        //$('.address').hide();

        $("#lbl_"+obj).html('Consultando CEP...');
        //
        $.getJSON(
            "https://viacep.com.br/ws/" + cep + "/json/ ",
            function (ret) {
                console.log(ret);
                if (ret.erro){
                    $(".address").show();
                    aviso('warning','CEP não encontrado, ajuste o CEP ou informe manualmente os dados de endereço.','Consulta CEP')
                    //
                    iconSpinner(false,obj);
                    $("#city").focus();
                }else {
                    $(".address").show();
                    $("#address").val(ret.logradouro);
                    $("#district").val(ret.bairro);
                    $("#complement").val(ret.complemento);
                    //
                    SelCity(ret.ibge);
                    //
                    iconSpinner(false,obj);
                    $("#nro").focus();
                }
            }
        ).fail(function (ret) {
            console.log(ret);
            $(".address").show();
            aviso('warning','Não foi possivel consultar o cep, informe manualmente os dados de endereço.','Consulta CEP')
            //
            iconSpinner(false,obj);
        });

        $("#lbl_"+obj).html('CEP');
    }
};



function ConsultaDoc(obj){
    //carregar os dados
    var doc = $("#"+obj).val();

    if ( (doc.length!=11) && (doc.length!=14) ) {
        $("#"+obj).focus();
        aviso('warning', 'Informe um CPF/CNPJ válido', 'Consulta');
        return false;
    }else {
        iconSpinner(true,obj);

        $.getJSON(
            "/api/cnpj/"+doc,
            function (ret) {
                //ocorreu algo erro na consulta
                if (ret.status=='ERROR'){
                    iconSpinner(false,obj);
                    aviso('warning',ret.message,'Consulta CNPJ');
                    return false;
                }

                //todos os dados no log
                console.log(ret);

                //Situação não é ativa
                if (ret.dados.situacao!='ATIVA'){
                    iconSpinner(false,obj);
                    aviso('warning',ret.dados.nome+'\n não está mais ATIVA,\n situação atual: '+ret.dados.situacao,'Situação inválida');
                    return false;
                }

                aviso('success',ret.dados.nome,'Encontrou');

                //
                $("#name").val(ret.dados.nome);
                $("#responsible").val(ret.dados.responsavel);
                $("#doc2").val(ret.dados.ie);

                $('.address').show();
                $("#zipcode").val(ret.endereco.cep);
                $("#address").val(ret.endereco.logradouro);
                $("#number").val(ret.endereco.numero);
                $("#district").val(ret.endereco.bairro);
                $("#complement").val(ret.endereco.complemento);
                $("#phone").val(ret.dados.telefone);
                $("#email").val(ret.dados.email);
                //se tem codigo do municipio
                if (ret.endereco.municipio_cod!='') {
                    SelCity(ret.endereco.municipio_cod);
                }else{
                    /** busca pelo CEP **/
                    $.getJSON(
                        "https://viacep.com.br/ws/" + ret.endereco.cep + "/json/ ",
                        function (retCEP) {
                            if (!retCEP.erro) {
                                SelCity(retCEP.ibge);
                            }
                        }
                    );
                }
                //
                iconSpinner(false,obj);

                //geolocalizacao no mapa
                LatitudePeloEndereco();
            }
        ).fail(function () {
            aviso('warning','Não foi possivel consultar o cnpj, informe os demais dados manualmente.','Consulta CNPJ');
            iconSpinner(false,obj);
            $("#"+obj).focus();
        });
    }
};


$('#city').select2({
    language: "pt-br",
    placeholder: 'Informe sua cidade',
    minimumInputLength: 3,
    language: $.extend({},
        $.fn.select2.defaults.defaults.language, {
            inputTooShort: function () {
                return "Informe o nome da cidade";
            },
            noResults: function() {
                return "Nenhum resultado, informe o nome da cidade.";
            },
        })
    ,ajax: {
        url: '/api/cities',
        method:'post',
        data: function (params) {
            var query = {
                search: params.term,
                type: 'public'
            }
            // Query parameters will be ?search=[term]&type=public
            return query;
        },
        processResults: function (data) {
//                console.log(data.items);
            // Tranforms the top-level key of the response object from 'items' to 'results'
            return {
                results: data.items
            };
        }
    }
});

function SelCity(cityId) {
    var objSelect = $('#city');
    //clear selection
    objSelect.val(null).trigger('change');
    $.getJSON(
        '/api/city/'+cityId,
        function (data) {
            // create the option and append to Select2
            var option = new Option(data.text, data.id, true, true);
            objSelect.append(option).trigger('change');

            // manually trigger the `select2:select` event
            objSelect.trigger({
                type: 'select2:select',
                params: {
                    data: data
                }
            });
        }
    ).fail(function (ret) {
        console.log(ret);
        aviso('warning','Não foi possivel consultar o cidade','Consulta Cidade')
    });

}