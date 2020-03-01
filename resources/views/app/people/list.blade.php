@extends('layouts.list')

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
        {{--<a href="{{route($route.'.home')}}" class="btn waves-effect waves-light btn-outline-info"><i class="mdi mdi-chart-areaspline"></i> Gráficos</a>--}}
    </div>


@endsection

@section('th')
    <th >Nome</th>
    <th >CPF/CNPJ</th>
    <th >Contato</th>
    <th >Telefone</th>
    <th >Cidade / UF</th>
    <th ></th>
@endsection

@section('tbody')
    @foreach($lista as $cad)
        <tr class="hover-visible">
            <td>
                <a href="{{route($route.'.show',['id'=>$cad->id])}}" title="Visualizar ficha completa">
                    {{$cad->name}}
                </a>
            </td>
            <td>{{ FormataCpfCnpj($cad->doc1) }}</td>
            <td>{{$cad->contact}}</td>
            <td>{{$cad->phone}}</td>
            <td>{{$cad->city_()}}</td>
            <td class="text-nowrap" width="10px">
                <div class="action">
                    <a class="btn btn-sm btn-secondary" href="{{route($route.'.edit',['id'=>$cad->id])}}" data-toggle="tooltip" data-original-title="Alterar"> <i class="fa fa-pencil"></i> </a>
                    @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
                    <a class="btn btn-sm btn-danger" onclick="ask_url('Deseja excluir este cadastro?','{{route($route.'.del',['id'=>$cad->id])}}','{{\Illuminate\Support\Facades\Auth::user()->email}}')"
                       data-toggle="tooltip" data-original-title="Excluir"> <i class="fa fa-trash text-white"></i> </a>
                    @endif
                </div>
            </td>
        </tr>
    @endforeach

@endsection

@section('script_footer')
    {{--<script src="{{ asset('my/js/listas.js') }}"></script>--}}
@endsection