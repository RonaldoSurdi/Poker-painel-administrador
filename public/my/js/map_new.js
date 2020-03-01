document.getElementById('btnMap').addEventListener('click', function() {
    LatitudePeloEndereco();
});

function LatitudePeloEndereco(){
    // if ($('#zipcode').val()==''){
    //     aviso('warning','Preencha seu endereço completo!','Localizar no Mapa');
    //     limparMapa();
    //     $('#zipcode').focus();
    //     return false;
    // }
    var address = $('#address').val()+','
        +$('#number').val()+','
        +$('#zipcode').val()+','
        +$('#district').val()+','
        +$("#city option:selected").text();
    ;

    var cidade = $('#zipcode').val()+','
        +$('#district').val()+','
        +$("#city option:selected").text();
    ;

    $('#map').show();

    GoogleMapsApi_geocoder(address,cidade);
}

function montarMapa(vLat,vLng){
    if (vLat==0){
        $('#map').hide();
    }else {
        $('#map').show();
        // $('#btnMap').hide();
        var pos = {lat: vLat, lng: vLng};
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 19,
            center: (pos)
        });
        CriarMarker(map, pos);
    }
}

function handleEvent(event) {
    $('#lat').val(event.latLng.lat());
    $('#lng').val(event.latLng.lng());
}
function CriarMarker(map,pos){
    var marker = new google.maps.Marker({
        map: map,
        position: pos,
        draggable: true,
        title: 'Seu Club'
    });
    marker.addListener('drag', handleEvent);
    marker.addListener('dragend', handleEvent)
    $('#lat').val(pos.lat);
    $('#lng').val(pos.lng);
    ClubLocalizado();
}


function GoogleMapsApi_geocoder(address,cidade) {
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 3, /**Zoon 3 para ficar mostrando o map da america latina**/
        center: {lat: -27.231295, lng: -52.023031} /**Coord de Concordia-SC**/
    });
    var geocoder = new google.maps.Geocoder();

    geocoder.geocode({'address': address}, function(results, status) {
        if (status === 'OK') {
            $('#lat').val(results[0].geometry.location.lat);
            $('#lng').val(results[0].geometry.location.lng);

            map.setZoom(19);
            map.setCenter(results[0].geometry.location);

            CriarMarker(map, results[0].geometry.location);

        }else if (cidade!=''){
            /** se não localizou tenta somente pela cidade **/
            aviso('warning','O google não localizou o endereço: <br> Ajsute manualmente a localização!','Falha ao localizar');
            console.log('Endereco: '+address);
            GoogleMapsApi_geocoder(cidade,'');
        }else if (status === 'ZERO_RESULTS') {
            aviso('warning','O google não localizou o endereço:\n"'+address+'"!','Falha ao localizar');
            console.log('Endereco: '+address);
            limparMapa();
        } else {
            aviso('warning','Ops! Ocorreu algo de errado na localização!\nRetorno:'+status,'Falha ao localizar');
            console.log('Geocode reason: ' + status);
            limparMapa();
        }
    });
}

function limparMapa() {
    $('#map').hide();
    $('#btnMap').show();
    $('#lat').val('');
    $('#lng').val('');
    $('#map_text').html('Preencha o endereço completo do cadastro e clique em Localizar');
}

function ClubLocalizado() {
    $('#map_text').html('<p class="text-success"> <i class="fa fa-check"></i> Endereço Localizado, se não estiver correto arraste o marcador!</p>');
    // $('#btnMap').hide();
}