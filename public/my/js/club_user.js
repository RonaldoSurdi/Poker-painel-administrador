function SaveUser() {
    var sHtml = $('#btn_saveUser').html();
    $('#btn_saveUser').html('<i class="fa fa-spinner fa-spin"></i>')
    $('#btn_saveUser').prop('disabled','disabled');


    //Salvar
    $.ajax({
        url: '/poker/clubs/user/save',
        type: 'post',
        dataType: 'json',
        data: $('#frm_user').serialize(),
        success: function(data) {
            //console.log(data);
            $('#btn_saveUser').html(sHtml)
            $('#btn_saveUser').prop('disabled',false);

            if (data.result=='N'){
                aviso('warning',data.message);
                return false;
            }
            aviso('success',data.message);
        }
        ,error: function(XMLHttpRequest, textStatus, errorThrown){
            /**** deu erro na requisição web *****/
            aviso('warning',tratarErroAjax(XMLHttpRequest),'Atenção!');
            //
            $('#btn_saveUser').html(sHtml)
            $('#btn_saveUser').prop('disabled',false);
        }
    });
}


function DelUser() {
    var sHtml = $('#btn_delUser').html();
    $('#btn_delUser').html('<i class="fa fa-spinner fa-spin"></i>')
    $('#btn_delUser').prop('disabled','disabled');

    //Salvar
    $.ajax({
        url: '/poker/clubs/user/del',
        type: 'post',
        dataType: 'json',
        data: $('#frm_user').serialize(),
        success: function(data) {
            //console.log(data);
            $('#btn_delUser').html(sHtml)
            $('#btn_delUser').prop('disabled',false);

            if (data.result=='N'){
                aviso('warning',data.message);
                return false;
            }
            aviso('success',data.message);
            $('#lic_user').val('');
            $('#lic_pass').val('');
        }
        ,error: function(XMLHttpRequest, textStatus, errorThrown){
            /**** deu erro na requisição web *****/
            aviso('warning',tratarErroAjax(XMLHttpRequest),'Atenção!');
            //
            $('#btn_delUser').html(sHtml)
            $('#btn_delUser').prop('disabled',false);
        }
    });
}

function ResetUser() {
    $('#email').val($('#lic_user').val());

    var sHtml = $('#btn_sendUser').html();
    $('#btn_sendUser').html('<i class="fa fa-spinner fa-spin"></i>')
    $('#btn_sendUser').prop('disabled','disabled');

    //Salvar
    $.ajax({
//                url: 'http://localhost:8001/api/user_club/reset/',
        url: 'http://pokerclubsapp.com.br/api/user_club/reset',
//                url: 'http://beta.pokerclubsapp.com.br/api/user_club/reset/',
        type: 'post',
        dataType: 'json',
        data: $('#frm_user').serialize(),
        success: function(data) {
            //console.log(data);
            $('#btn_sendUser').html(sHtml)
            $('#btn_sendUser').prop('disabled',false);

            if (data.result=='N'){
                aviso('warning',data.message);
                return false;
            }
            aviso('success',data.message);
        }
        ,error: function(XMLHttpRequest, textStatus, errorThrown){
            /**** deu erro na requisição web *****/
            aviso('warning',tratarErroAjax(XMLHttpRequest),'Atenção!');
            //
            $('#btn_sendUser').html(sHtml)
            $('#btn_sendUser').prop('disabled',false);
        }
    });
}

function askDelUser(){
    swal({
            text: 'Deseja excluir o Usuário do Clube?',
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
            return DelUser();
}
});
}
function askResetUser(){
    swal({
            text: 'Deseja enviar o link para a troca de senha?',
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
            return ResetUser();
}
});
}
