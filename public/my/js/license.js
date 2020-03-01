$('.select2').select2();

function Licenses(){
    $.getJSON(
        "/poker/clubs/licenses/" + $('#club_id').val(),
        function (data) {
            if (data.result=='N'){
                aviso('warning',data.message,'Atenção');
                return false;
            }
            $('#lista_license').html(data.html);
            $('#lic_now').html(data.premium);
        }
    ).fail(function (ret) {
        console.log(ret);
        aviso('warning','Não foi possivel carregar a lista de licenças do clube','Licenças');
    });
}
Licenses();

function LicAdd() {
    $('#lic_type').val(2).trigger('change');
    $('#modalLic').modal('show');
}

function addLic() {
    $('#btn_saveLic').html('<i class="fa fa-spinner fa-spin"></i>')
    $('#btn_saveLic').prop('disabled','disabled');

    //Salvar
    $.ajax({
        url: '/poker/lic/new',
        type: 'post',
        dataType: 'json',
        data: $('#frm_new').serialize(),
        success: function(data) {
            if (data.result=='N'){
                aviso('warning',data.message,'Atenção');
                $('#btn_saveLic').html('Salvar')
                $('#btn_saveLic').prop('disabled',false);
                return false;
            }
            aviso('success','Licença adicionada');

            Licenses();

            $('#btn_saveLic').html('Salvar');
            $('#btn_saveLic').prop('disabled',false);
            $('#modalLic').modal('hide');
        }
        ,error: function(XMLHttpRequest, textStatus, errorThrown){
            /**** deu erro na requisição web *****/
            aviso('error',tratarErroAjax(XMLHttpRequest),'Atenção!');
            //
            $('#btn_saveLic').html('Salvar')
            $('#btn_saveLic').prop('disabled',false);
            Licenses();
        }
    });

}


function removerLicenca(id) {
    //Salvar
    $.ajax({
        url: '/poker/lic/del/'+id,
        type: 'get',
        dataType: 'json',
        success: function(data) {
            if (data.result=='N'){
                aviso('warning',data.message,'Atenção');
                return false;
            }
            aviso('success','Licença Removida');
            Licenses();
        }
        ,error: function(XMLHttpRequest, textStatus, errorThrown){
            /**** deu erro na requisição web *****/
            aviso('warning',tratarErroAjax(XMLHttpRequest),'Atenção!');
            Licenses();
        }
    });
}

function lic_del(id){
    swal({
            text: 'Deseja excluir esta licença?',
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
            preConfirm: () => {
               return removerLicenca(id);
            }
    });
}

function getPay(id){
    var btn_id = '#btn_pay_'+id;
    var btn_html = $(btn_id).html();
    $(btn_id).html('<i class="fa fa-spinner fa-spin"></i>')
    $(btn_id).prop('disabled','disabled');

    $.getJSON(
        "/poker/lic/pay/" + id,
        function (data) {
            //console.log(data);
            if (data.result=='N'){
                aviso('warning',data.message,'Atenção');
                $(btn_id).html(btn_html)
                $(btn_id).prop('disabled',false);
                return false;
            }
            var open = window.open(data.url,'frame');
            $('#modalPay').modal('show');
            $(btn_id).html(btn_html)
            $(btn_id).prop('disabled',false);
        }
    ).fail(function (ret) {
        console.log(ret);
        $(btn_id).html(btn_html)
        $(btn_id).prop('disabled',false);
        aviso('warning','Não foi possivel carregar a lista de licenças do clube','Licenças');
    });
}

$(document).ready(function() {
    $('#modalPay').on('hidden.bs.modal', function () {
        Licenses();
    })});