@extends('layouts.app')

@section('css')
    <link href="{{asset('material/assets/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    <div class="col-sm-12 text-center">
        @if($errors->any())
            <div class="container text-left"  style="max-width:500px">
                <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                    <h3 class="text-warning"><i class="fa fa-exclamation-triangle"></i> Atenção</h3>
                    <ul >
                        @foreach( $errors->all() as $erro)
                            <li>{{$erro}}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="container text-left" style="max-width:980px">

            <form class="form-horizontal input-form " method="POST"
                  @if (isset($routeSave))
                    action="{{route($routeSave)}}">
                  @else
                    action="{{route($route.'.save')}}">
                  @endif
                {{ csrf_field() }}
                <input type="hidden" id="id" name="id" value="{{$cad->id}}">

                @if (isset($redirect))
                    <input type="hidden" name="redirect" value="{{$redirect}}"/>
                @endif


                <div class="ribbon-wrapper card">
                    <div class="ribbon ribbon-primary">
                        @if ($cad->id>0)
                            Alterar Cadastro
                        @else
                            Novo Cadastro
                        @endif
                    </div>
                    <div class="card-body">
                        @yield('form')


                    </div>
                </div>

                <div class="text-right">
                    <a href="{{route($route.'.home')}}" class="btn btn-inverse">Cancelar</a>
                    <button type="submit" class="btn btn-primary ">Salvar <i class="icon-floppy-disk position-right"></i></button>
                </div>
            </form>

        </div>
    </div>
@endsection


@section('script_footer')
    {{--<script src="{{ asset('my/js/listas.js') }}"></script>--}}

@endsection