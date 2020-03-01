@extends('layouts.list')
@php
    $title = 'Clubes';
    $route = 'poker.club';
@endphp

@section('nav_title')
    <form class="form-horizontal mr-5" role="form" action="{{route($route.'.search')}}" method="post">
        {{ csrf_field() }}
        <div class="form-group m-0">
            <div class="input-group">
                <input type="text" class="form-control" id="busca" name="busca" placeholder="Localizar...">
                <span class="input-group-btn"><button type="submit" class="btn btn-info"><i class="fa fa-search"></i> </button></span>
            </div>
        </div>
    </form>

    <div class="button-group">
        <a href="{{route($route.'.new')}}" class="btn btn-success heading-btn"><i class="mdi mdi-plus-circle-outline"></i> Novo</a>
        <a href="{{route($route.'.all')}}" class="btn waves-effect waves-light btn-outline-info"><i class="mdi mdi-view-list"></i> Lista</a>
    </div>

@endsection

@section('home')

    <div class="row">
        <!-- Column -->
        @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
        <div class="col-lg-3 col-md-6">
        @else
        <div class="col-lg-4 col-md-6">
        @endif
            <div class="card">
                <div class="card-body">
                    <a href="{{route('poker.club.all')}}" class="d-flex flex-row">
                        <div class="round align-self-center round-success"><i class="ti-wallet"></i></div>
                        <div class="m-l-10 align-self-center">
                            <h3 class="m-b-0">{{$qtd_clubs}}</h3>
                            <h5 class="text-muted m-b-0">Cadastrados</h5></div>
                    </a>
                </div>
            </div>
        </div>
        <!-- Column -->

        <!-- Column -->
        @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <a href="{{route('poker.club.list.premium')}}" class="d-flex flex-row">
                        <div class="round align-self-center round-info"><i class="ti-user"></i></div>
                        <div class="m-l-10 align-self-center">
                            <h3 class="m-b-0">{{$qtd_clubs_premium}}</h3>
                            <h5 class="text-muted m-b-0">Premium</h5></div>
                    </a>
                </div>
            </div>
        </div>
        @endif
        <!-- Column -->

        <!-- Column -->
        @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
        <div class="col-lg-3 col-md-6">
        @else
        <div class="col-lg-4 col-md-6">
        @endif
            <div class="card">
                <a href="{{route('poker.indic')}}" class="card-body">
                    <div class="d-flex flex-row">
                        <div class="round align-self-center round-danger"><i class="ti-calendar"></i></div>
                        <div class="m-l-10 align-self-center">
                            <h3 class="m-b-0">{{$indicados}}</h3>
                            <h5 class="text-muted m-b-0">Novos Indicados</h5></div>
                    </div>
                </a>
            </div>
        </div>
        <!-- Column -->


        @if ($usuarios>0)
            <!-- Column -->
            @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
            <div class="col-lg-3 col-md-6">
            @else
            <div class="col-lg-4 col-md-6">
            @endif
                <div class="card">
                    <a href="#" class="card-body">
                        <div class="d-flex flex-row">
                            <div class="round align-self-center round-danger"><i class="fa fa-users"></i></div>
                            <div class="m-l-10 align-self-center">
                                <h3 class="m-b-0">{{$usuarios}}</h3>
                                <h5 class="text-muted m-b-0">Usuários do APP</h5></div>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Column -->
        @endif


        <!-- Column -->
        @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
        <div class="col-lg-3 col-md-4">
        @else
        <div class="col-lg-4 col-md-4">
        @endif
            <div class="card">
                <div class="card-header">
                    <h4 class="m-b-0">Clubes nos Estados</h4></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th style="text-align: center">Estado</th>
                                <th style="text-align: center">Qtd</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($estados as $uf)
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('poker.club.state',['uf'=>$uf->uf]) }}" title="Clique para ver todos os clubes deste estado">
                                            {{$uf->uf}}
                                        </a>
                                    </td>
                                    <td align="center">
                                        <a href="{{ route('poker.club.state',['uf'=>$uf->uf]) }}" title="Clique para ver todos os clubes deste estado">
                                            {{$uf->qtd}}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <!-- Column -->

        <!-- Column -->
        @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
        <div class="col-lg-9 col-md-8">
        @else
        <div class="col-lg-8 col-md-7">
        @endif
        <div class="card">
            <div class="card-header">
                <h4 class="m-b-0">Notificações</h4></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th style="text-align: center">id</th>
                            <th style="text-align: center">Clube</th>
                            <th style="text-align: center">Detalhes</th>
                            <th style="text-align: center">Data Retorno</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($crms as $crm)
                            <tr>
                                <td align="center">
                                    <a href="{{ route('poker.club.show',['id'=>$crm->club_id]) }}" title="Clique para mais detalhes">
                                        {{$crm->id}}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('poker.club.show',['id'=>$crm->club_id]) }}" title="Clique para mais detalhes">
                                        {{$crm->name}}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('poker.club.show',['id'=>$crm->club_id]) }}" title="Clique para mais detalhes">
                                        {{$crm->obs}}
                                    </a>
                                </td>
                                <td align="center">
                                    <a href="{{ route('poker.club.show',['id'=>$crm->club_id]) }}" title="Clique para mais detalhes">
                                        {{date('d/m/y H:m',strtotime($crm->notify_at))}}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- Column -->

    </div>

@endsection

@section('script_footer')
    {{--<script src="{{ asset('my/js/listas.js') }}"></script>--}}
@endsection