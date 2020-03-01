@extends('layouts.app')
@section('content')

    @yield('header')

    @if(isset($lista))
        @if ($lista->count()>0)
            <div class="card" style="width: 100%">
                <div class="card-body">
                    <div class="pull-right" style="margin-top:-15px;margin-right:-15px;">
                        @yield('filter')
                    </div>

                    {{--<h4 class="card-title"></h4>--}}
                    <h6 class="card-subtitle">Listando <span id="qtd">{{$lista->count()}}</span> cadastros @if (isset($busca))<span class="text-primary"> - Pesquisando por <i>{{$busca}}</i> </span> @endif</h6>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                @yield('th')
                            </tr>
                            </thead>
                            <tbody>
                                @yield('tbody')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info" style="max-width: 500px">
                Nenhum cadastro encontrado @if (isset($busca)) com <i>{{$busca}}</i> @endif
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
            </div>
        @endif
    @endif

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

    @yield('home')

@endsection


@section('script_footer')
    {{--<script src="{{ asset('my/js/listas.js') }}"></script>--}}
@endsection