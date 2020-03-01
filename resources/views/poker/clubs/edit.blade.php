@extends('layouts.edit')
@php
$title = 'Clubes';
$route = 'poker.club';
@endphp

@section('script_head')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCu-GdAWOloRPGNQwCIDx4HjRk3MiDrki8"></script>
@endsection

@section('form')
    @if (isset($ind))
        <input type="hidden" name="ind_id" value="{{$ind->id}}">
    @endif
            <div class="row">
                <div class="col-md-12">
                    {!! MyTextField($errors,'name','Nome do Clube *',$cad->name,'required autofocus maxlength=100') !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    {!! MyTextBtn($errors,'doc1','CNPJ/CPF',$cad->doc1) !!}
                </div>

                <div class="col-md-3">
                    {!! MyTextField($errors,'doc2','RG/IE',$cad->doc2,'maxlength=20') !!}
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    {!! MyTextField($errors,'responsible','Responsável *',$cad->responsible,'required maxlength=100') !!}
                </div>

                <div class="col-md-4">
                    {!! MyTextField($errors,'phone','Telefone',$cad->phone,'maxlength=15') !!}
                </div>

                <div class="col-md-4">
                    {!! MyTextField($errors,'whats','Whatszapp',$cad->whats,'maxlength=15') !!}
                </div>

                <div class="col-md-6">
                    {!! MyTextField($errors,'email','E-mail',$cad->email,'maxlength=255') !!}
                </div>

                <div class="col-md-6">
                    {!! MyTextField($errors,'site','Site',$cad->site,'maxlength=255') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="ribbon-wrapper card" style="max-width:980px">
        <div class="ribbon ribbon-primary">
            Endereço
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-3">
                    {!! MyTextBtn($errors,'zipcode','CEP',FormatarCEP($cad->zipcode)) !!}
                </div>
                <div class="col-md-offset-3 col-md-6 address">
                    <div class="form-group">
                        <label>Cidade / UF *</label>
                        <select class="select2 cidade" name='city' id='city' required style="width: 100%">
                            <option></option>
                            {{--@foreach($cities as $item)--}}
                                {{--<option value="{{$item->id}}">{{$item->name}} - {{$item->uf}}</option>--}}
                            {{--@endforeach--}}
                        </select>
                    </div>
                </div>
            </div>

            <div class="row address">
                <div class="col-md-10">
                    {!! MyTextField($errors,'address','Endereço *',$cad->address,'maxlength=191') !!}
                </div>
                <div class="col-md-2">
                    {!! MyTextField($errors,'number','Número',$cad->number,'maxlength=10') !!}
                </div>
                <div class="col-md-6">
                    {!! MyTextField($errors,'district','Bairro *',$cad->district,'maxlength=50') !!}
                </div>
                <div class="col-md-6">
                    {!! MyTextField($errors,'complement','Complemento',$cad->complement,'maxlength=30') !!}
                </div>
            </div>

        </div>
    </div>
    <div class="ribbon-wrapper card" style="max-width:980px">
        <div class="ribbon ribbon-primary">
            Localização no Mapa *
        </div>
        <div class="card-body">

            <div class="row">
                <input type="hidden" class="form-control" id="lat" name="lat">
                <input type="hidden" class="form-control" id="lng" name="lng">
                <div class="col-sm-2">
                    <button type="button" id="btnMap" class="btn btn-primary"><i class="fa fa-search"></i> Localizar</button>
                </div>
                <div class="col-sm-10">
                    <p id="map_text">Preencha o endereço completo do cadastro e clique em Localizar.</p>
                </div>
            </div>

            <div class="map-container map-geolocation m-t-20" id="map" style="height: 400px"></div>

@endsection

@section('script_footer')
    <script src="{{asset('material/assets/plugins/select2/dist/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('my/js/club_edit.js')}}"></script>


<script type="text/javascript" src="{{ asset('my/js/map_new.js')}}"></script>
<script>
    @if (old('zipcode') ? old('zipcode') : $cad->zipcode)
        $('.address').show();
    @endif

    @if (old('city') ? old('city') : $cad->city_id)
        SelCity( '{{ old('city') ? old('city') : $cad->city_id }}' );
    @endif

    @if ($cad->id>0)
        @if (old('lat') ? old('lat') : $cad->lat)
            google.maps.event.addDomListener(window, 'load', function() {
                montarMapa( {{old('lat') ? old('lat') : $cad->lat}}, {{old('lng') ? old('lng') : $cad->lng}} );
            });
        @else
            $('#map').hide();
        @endif
    @else
        @if ( old('lat') )
            google.maps.event.addDomListener(window, 'load', function() {
                montarMapa( {{old('lat')}}, {{old('lng')}} );
            });
        @else
            $('#map').hide();
        @endif
    @endif
</script>
@endsection