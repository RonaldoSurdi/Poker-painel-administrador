@extends('layouts.app')
@php
$title = 'Ficha do Usuário';
$route = 'config.user';
@endphp

@section('script_head')
@endsection

@section('content')

    {{--<ul class="nav nav-tabs" role="tablist">--}}
        {{--<li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Resumo</span></a> </li>--}}
        {{--<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#profile" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Dados</span></a> </li>--}}
        {{--<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#lic" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Licença</span></a> </li>--}}
        {{--<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#contato" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Contato</span></a> </li>--}}
    {{--</ul>--}}
    <!-- Tab panes -->
    <div class="tab-content tabcontent-border">
        {{--<div class="tab-pane active" id="home" role="tabpanel">--}}
            {{--<div class="p-20">--}}
                {{--<h3>{{$cad->name}}</h3>--}}
                {{--<p>Localizado em: {{ $cad->city() }} </p>--}}
            {{--</div>--}}
        {{--</div>--}}
        <!-- Dados cadastrais -->
        <div class="tab-pane p-0 floating-labels active" id="profile" role="tabpanel">
            <div class="ribbon-wrapper card m-0 border-0">
                <div class="ribbon ribbon-primary">
                    Dados Cadastrais
                </div>
                <div class="text-right" style="margin-top: -40px">
                    <a href="{{route($route.'.edit',['id'=>$cad->id])}}" class="btn btn-primary"><i class="fa fa-pencil"></i> Editar</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            {!! MylabelView('Nome', $cad->name ) !!}
                        </div>

                        <div class="col-md-12">
                            {!! MylabelView('E-mail',$cad->email) !!}
                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>

@endsection