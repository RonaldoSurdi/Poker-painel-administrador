$('#people').select2({
    language: "pt-br",
    placeholder: 'Informe uma pessoa',
    minimumInputLength: 3,
    language: $.extend({},
        $.fn.select2.defaults.defaults.language, {
            inputTooShort: function () {
                return "Informe o nome da pessoa";
            },
            noResults: function() {
                return "Nenhum resultado.";
            },
        })
    ,ajax: {
        url: '/api/peoples',
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


function SelPeople(pID,Obj) {
    if (!Obj) Obj='people';
    var objSelect = $('#'+Obj);
    //clear selection
    objSelect.val(null).trigger('change');
    $.getJSON(
        '/api/people/'+pID,
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
        aviso('warning','Não foi possivel consultar a pessoa','Consulta Pessoa');
    });

}