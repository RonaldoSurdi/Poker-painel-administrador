@extends('layouts.list')
@php
$title = 'Clubes Cadastrados';
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
        <a href="{{route($route.'.home')}}" class="btn waves-effect waves-light btn-outline-info"><i class="mdi mdi-chart-areaspline"></i> Gráficos</a>
    </div>


@endsection

@section('filter')
    <select class="form-control" id="filter" onchange="filtro()" style="width: 200px">
        <option value="1">Todos</option>
        @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
            <option value="8">Aceitou Contrato</option>
        @endif
        <option value="2">Free</option>
        @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
        <option value="3">Premium</option>
        <option value="4">Anual</option>
        @endif
        <option value="5">30 dias</option>
        @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
        <option value="7">Vencido</option>
        <option value="6">Clube Bloqueado</option>
        @endif
    </select>
@endsection

@section('th')
    <th >ID</th>
    <th >Nome</th>
    <th >Responsável</th>
    <th >Telefone</th>
    <th >Cidade / UF</th>
    <th >Licença</th>
    @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
    <th >Contrato</th>
    @endif
    <th ></th>
@endsection

@section('tbody')
    @foreach($lista as $cad)
        @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
            @php($lic = $cad->licenca())
        <tr class="hover-visible todos
            @if ($cad->status==0) text-danger bloqueada
            @endif
            @if ($lic=='---') free
            @elseif ($lic=='Anual') anual premium
            @elseif ($lic=='30 dias') trinta premium
            @elseif ($lic=='Vencida') vencida premium
            @endif
            @if ($cad->contract==1) contrato
            @endif

        ">
            {{--<td class="">--}}

            {{--</td>--}}
            <td>{{$cad->id}}</td>
            <td>
                <a href="{{route('poker.club.show',['id'=>$cad->id])}}" title="Visualizar ficha completa">
                    {{$cad->name}}
                </a>
            </td>
            <td>{{$cad->responsible}}</td>
            <td>{{$cad->phone}}</td>
            <td>{{$cad->city()}}</td>
            <td>{{$lic}}</td>
            @if ($cad->contract==1) text-danger bloqueada
            <td class="text-center">Aceitou</td>
            @else
            <td class="text-center">-</td>
            @endif
            <td class="text-nowrap" width="10px">
                <div class="action">
                    <a class="btn btn-sm btn-primary" href="{{route('poker.club.show',['id'=>$cad->id])}}" data-toggle="tooltip" data-original-title="Visualizar"> <i class="fa fa-eye"></i> </a>
                    <a class="btn btn-sm btn-danger" onclick="ask_url('Deseja excluir este cadastro?','{{route($route.'.del',['id'=>$cad->id])}}','{{\Illuminate\Support\Facades\Auth::user()->email}}')"
                       data-toggle="tooltip" data-original-title="Excluir"> <i class="fa fa-trash text-white"></i> </a>
                </div>
            </td>
        </tr>
        @endif
        @if (\Illuminate\Support\Facades\Auth::user()->id !== 1)
            @php($lic = $cad->licenca())
            @if (($lic=='---') || ($lic=='30 dias'))
            <tr class="hover-visible todos
            @if ($cad->status==0) text-danger bloqueada
            @endif

            @if ($lic=='---') free
            @elseif ($lic=='Anual') anual premium
            @elseif ($lic=='30 dias') trinta premium
            @elseif ($lic=='Vencida') vencida premium
            @endif

                    ">
                {{--<td class="">--}}

                {{--</td>--}}
                <td>
                    <a href="{{route('poker.club.show',['id'=>$cad->id])}}" title="Visualizar ficha completa">
                        {{$cad->name}}
                    </a>
                </td>
                <td>{{$cad->responsible}}</td>
                <td>{{$cad->phone}}</td>
                <td>{{$cad->city()}}</td>
                <td>{{$lic}}</td>
                <td class="text-nowrap" width="10px">
                    <div class="action">
                        <a class="btn btn-sm btn-primary" href="{{route('poker.club.show',['id'=>$cad->id])}}" data-toggle="tooltip" data-original-title="Visualizar"> <i class="fa fa-eye"></i> </a>
                    </div>
                </td>
            </tr>
            @endif
        @endif
    @endforeach

@endsection

@section('script_footer')
    {{--<script src="{{ asset('my/js/listas.js') }}"></script>--}}
    <script>
        function filtro() {
            var sel = $('#filter').val();
            var classe = '';

            $('.todos').hide();
            if (sel == 1) classe = '.todos';
            @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
            if (sel == 8) classe = '.contrato';
            @endif
            if (sel == 2) classe = '.free';
            @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
            if (sel == 3) classe = '.premium';
            if (sel == 4) classe = '.anual';
            @endif
            if (sel == 5) classe = '.trinta';
            @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
            if (sel == 6) classe = '.bloqueado';
            if (sel == 7) classe = '.vencida';
            @endif
            $(classe).show();
            var qtd = $(classe).length;
            $('#qtd').html(qtd);
        }
    </script>
@endsection