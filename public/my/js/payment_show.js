function Info(id) {
    $('#modalInfo').modal('show');
    window.open('/pay/info/'+id,'frameInfo');
}

$(document).ready(function() {
    $('#modalInfo').on('hidden.bs.modal', function () {
        window.open('/load','frameInfo');
        // location.reload();
    })
});

function CheckPay(pag_id) {
    // Alert_load('Pagando...')
    //Salvar
    $.ajax({
        url: '/pay/check',
        type: 'post',
        dataType: 'json',
        data: {
            _token : $('[name="_token"]').val(),
            type_id : pag_id
        },
        success: function(data) {
            if (data.result=='N'){
                aviso('warning',data.message,'Atenção');
                return false;
            }
            aviso('success','Pagamento Recebido!');

            location.reload();
        },error: function(XMLHttpRequest, textStatus, errorThrown){
            aviso('error',tratarErroAjax(XMLHttpRequest),'Atenção!');
        }
    })
}

function askCheckPay(pag_id){
    swal({
        text: 'Deseja marcar como recebido?',
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
            return CheckPay(pag_id);
        }
    })
}