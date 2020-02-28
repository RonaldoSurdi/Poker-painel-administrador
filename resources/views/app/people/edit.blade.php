@extends('layouts.edit')

@section('form')
            <input type="hidden" name="type" value="{{$cad->type}}">

            <div class="row">
                <div class="col-md-12">
                    {!! MyTextField($errors,'name','Nome *',$cad->name,'required autofocus maxlength=100') !!}
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
                    {!! MyTextField($errors,'contact','Pessoa para Contato',$cad->contact,' maxlength=30') !!}
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
            </div>
            {{--<div class="row">--}}
                {{--<div class="col-md-12">--}}
                    {{--<div class="form-group">--}}
                        {{--<label>Descrição do clube</label>--}}
                        {{--<textarea class="form-control" rows="5" name="obs">{{ old('obs') ? old('obs') : $cad->obs() }}</textarea>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
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

@endsection

@section('script_footer')
    <script src="{{asset('material/assets/plugins/select2/dist/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('my/js/people_edit.js')}}"></script>

    <script>
        @if (old('zipcode') ? old('zipcode') : $cad->zipcode)
            $('.address').show();
        @endif

        @if (old('city') ? old('city') : $cad->city_id)
            SelCity( '{{ old('city') ? old('city') : $cad->city_id }}' );
        @endif
    </script>
@endsection