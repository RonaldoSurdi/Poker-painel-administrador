
function aviso(tipo,mensagem,titulo){
    Alert_Close();

    $.toast({
        heading: titulo,
        text: mensagem,
        position: 'top-right',
        loaderBg:'#f1f1ff',
        icon: tipo, /** info, success, warning, error **/
        hideAfter: 5000,
        stack: 6
    });

}


function ask_url(mensagem,url){
    swal({
//            title: mensagem,
            text: mensagem,
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
            preConfirm: (email) => {
            return new Promise((resolve) => {
                    open = window.open(url,'_parent');
                })
            },
            allowOutsideClick: false
    }).then((result) => {
        if (result.value) {
        }
    })
}


function Alert_load(title){
    swal({
        title: 'Aguarde',
        html: title,
        onOpen: () => {
            swal.showLoading()
        },
    });
}
function Alert_Close() {
    swal.close();
}

function Alert_error(msg) {
    swal({
        type: 'error',
        title: 'Oops...',
        html: msg
    })
}


function Alert_ok(msg) {
    swal({
        type: 'success',
        title: msg,
    })
}

function Alert_time(msg,time=3000) {
    swal({
        type: 'success',
        title: msg,
        showConfirmButton: false,
        timer: time
    })
}