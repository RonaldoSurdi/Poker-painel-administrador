$('.select2').select2();

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


$('#conta').select2({
    language: "pt-br",
    placeholder: 'Informe uma Conta',
    minimumInputLength: 3,
    language: $.extend({},
        $.fn.select2.defaults.defaults.language, {
            inputTooShort: function () {
                return "Informe o nome da Conta";
            },
            noResults: function () {
                var term = $('#conta')
                    .data('select2')
                    .$dropdown.find("input[type='search']")
                    .val();
                if (!term) {
                    return "Nenhum resultado, informe o nome da conta.";
                } else {
                    return $("<span>Nenhum resultado. <a href='#' onclick=CadConta() class='btn waves-effect waves-light btn-outline-primary' title='clique aqui para cadastrar'>Cadastar <b>" + term + "</b></a></span>");
                }
            }
        })
    ,ajax: {
        url: '/api/plans',
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
            // console.log(data.items);
            // Tranforms the top-level key of the response object from 'items' to 'results'
            return {
                results: data.items
            };
        }
    }
});

function CadConta(){
    var nome = $('#conta')
        .data('select2')
        .$dropdown.find("input[type='search']")
        .val();

    /*** cadastra o grupo e inclui ele no select ***/
    $.ajax({
        url: '/finan/plan/save',
        type: 'post',
        dataType: 'json',
        data: {
            name: nome ,
            type: $('#type').val(),
            _token : $('[name="_token"]').val(),
        },
        success: function(data) {
            // console.log(data);
            aviso('success','Conta '+nome+' cadastrada com sucesso!');
            var newOption = new Option(data.text, data.id, false, false);
            $('#conta').append(newOption).val(data.id).trigger('change');
            $('#conta').select2('close');
        }
        ,error: function(XMLHttpRequest, textStatus, errorThrown){
            AlertaErroAjax(XMLHttpRequest);
        }
    });
}

function SelConta(cityId) {
    var objSelect = $('#conta');
    //clear selection
    objSelect.val(null).trigger('change');
    $.getJSON(
        '/api/plan/'+cityId,
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
        aviso('warning','Não foi possivel consultar as contas','Consulta Conta')
    });

}