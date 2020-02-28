@extends('layouts.list')
@php
$title = 'Usuários do Interno';
$route = 'config.user';
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

    <a href="{{route($route.'.new')}}" class="btn btn-success heading-btn"><i class="icon-add"></i> Novo</a>
@endsection

@section('th')
    <th >Nome</th>
    <th >E-mail</th>
    <th >Status</th>
    <th ></th>
@endsection

@section('tbody')
    @if (isset($lista))
        @foreach($lista as $cad)
            <tr class="hover-visible cadastro @if ($cad->status==0) text-danger bloqueado @else ativo @endif">
                {{--<td class="">--}}

                {{--</td>--}}
                <td>
                    <a href="{{route($route.'.show',['id'=>$cad->id])}}" title="Visualizar ficha completa">
                        {{$cad->name}}
                    </a>
                </td>
                <td>{{$cad->email}}</td>
                @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
                    <td>
                        @if ($cad->status==1)
                            <button onclick="ask_url('Deseja Bloquear este cadastro?','{{route($route.'.active',['id'=>$cad->id])}}','{{\Illuminate\Support\Facades\Auth::user()->email}}')"
                               class="btn btn-success btn-sm text-white" title="Bloquear este cadastro"><i class="fa fa-check"></i> Liberado </button>
                        @else
                            <button onclick="ask_url('Deseja Liberar este cadastro?','{{route($route.'.active',['id'=>$cad->id])}}','{{\Illuminate\Support\Facades\Auth::user()->email}}')"
                               class="btn btn-warning btn-sm text-white"  title="Liberar este cadastro"><i class="fa fa-ban "></i> Bloqueado</button>
                        @endif
                    </td>
                @endif
                <td class="text-nowrap" width="10px">
                    <div class="action">
                        <a class="btn btn-sm btn-secondary"  href="{{route($route.'.edit',['id'=>$cad->id])}}" data-toggle="tooltip" data-original-title="Alterar"> <i class="fa fa-pencil"></i> </a>
                        @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
                            <a class="btn btn-sm btn-secondary"  href="{{route($route.'.roles',['id'=>$cad->id])}}" data-toggle="tooltip" data-original-title="Permissões">
                                <i class="fa fa-check-square"></i> </a>
                            @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
                            <a class="btn btn-sm btn-danger"  onclick="ask_url('Deseja excluir este cadastro?','{{route($route.'.del',['id'=>$cad->id])}}','{{\Illuminate\Support\Facades\Auth::user()->email}}')"
                               data-toggle="tooltip" data-original-title="Excluir"> <i class="fa fa-trash text-white"></i> </a>
                            @endif
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    @endif
@endsection

@section('home1')
    @if (isset($cards))
        <div class="row">
        @foreach($cards as $cad)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <!-- Card -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{$cad->name}}</h4>
                        <p class="card-text">{{$cad->email}}</p>
                        <div class="btn-group" role="group">
                            <a class="btn btn-sm btn-secondary" href="{{route($route.'.edit',['id'=>$cad->id])}}" data-toggle="tooltip" data-original-title="Alterar"
                                ><i class="fa fa-edit"></i> </a>
                            @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
                            <a class="btn btn-sm btn-secondary" data-toggle="tooltip" data-original-title="Permissões"><i class="fa fa-check-square"></i> </a>

                            @if ($cad->status==1)
                                <button onclick="ask_url('Deseja Bloquear este cadastro?','{{route($route.'.active',['id'=>$cad->id])}}','{{\Illuminate\Support\Facades\Auth::user()->email}}')"
                                        class="btn btn-success btn-sm text-white" title="Bloquear este cadastro"><i class="fa fa-check"></i> Liberado </button>
                            @else
                                <button onclick="ask_url('Deseja Liberar este cadastro?','{{route($route.'.active',['id'=>$cad->id])}}','{{\Illuminate\Support\Facades\Auth::user()->email}}')"
                                        class="btn btn-warning btn-sm text-white"  title="Liberar este cadastro"><i class="fa fa-ban "></i> Bloqueado</button>
                            @endif
                            <a class="btn btn-sm btn-danger text-white" onclick="ask_url('Deseja excluir este cadastro?','{{route($route.'.del',['id'=>$cad->id])}}','{{\Illuminate\Support\Facades\Auth::user()->email}}')"
                               data-toggle="tooltip" data-original-title="Excluir"><i class="fa fa-trash"></i> </a>
                            @endif
                        </div>

                    </div>
                </div>
                <!-- Card -->
            </div>
        @endforeach
        </div>
    @endif
@endsection