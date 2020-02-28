@extends('layouts.list')
@php
$route = 'poker.club';
@endphp

@section('css')
    <link href="{{asset('material/assets/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('nav_title')
    <form class="form-horizontal mr-5" role="form" action="{{route($route.'.search')}}" method="post">
        {{ csrf_field() }}
        {{--<div class="form-group m-0">--}}
            {{--<div class="input-group">--}}
                {{--<input type="text" class="form-control" id="busca" name="busca" placeholder="Localizar...">--}}
                {{--<span class="input-group-btn"><button type="submit" class="btn btn-info"><i class="fa fa-search"></i> </button></span>--}}
            {{--</div>--}}
        {{--</div>--}}
    </form>



    {{--<div class="button-group">--}}
        {{--<a href="{{route($route.'.list.anual')}}" class="btn waves-effect waves-light btn-outline-info"><i class="mdi mdi-chart-areaspline"></i> Anual</a>--}}
        {{--<a href="{{route($route.'.list.trinta')}}" class="btn waves-effect waves-light btn-outline-info"><i class="mdi mdi-chart-areaspline"></i> 30 dias</a>--}}
    {{--</div>--}}


@endsection

@section('filter')
    <select class="form-control" id="filter" onchange="filtro()" style="width: 200px">
        <option value="1">Todas</option>
        <option value="2">Ativas</option>
        @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
        <option value="3">Anual</option>
        @endif
        <option value="4">30 dias</option>
        <option value="5">15 dias</option>
        @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
        <option value="6">Vencidas</option>
        <option value="7">Bloqueadas</option>
        <option value="8">Pendentes</option>
        @endif
    </select>
@endsection

@section('th')
    <th >Clube</th>
    <th >Responsável</th>
    <th >Telefone</th>
    <th >Cidade / UF</th>
    <th >Licença</th>
    <th >Validade</th>
    <th >Dias</th>
@endsection

@section('tbody')
    @foreach($lista as $lic)
        @php($cad = $lic->club)
        @if($cad)
        @if ((\Illuminate\Support\Facades\Auth::user()->id === 1) || (($lic->dias()<30)&&($lic->status==1)))
        <tr class="hover-visible cadastro
            @if ($lic->dias()==0) text-danger vencida
            @elseif ($lic->status==0) text-gray-dark pendente
            @elseif ($lic->status==9) text-danger bloqueada
            @elseif ($lic->dias()<30) text-info trinta ativa
            @elseif ($lic->dias()<20) text-warning quinze ativa
            @elseif ($lic->status==1) text-success ativa
            @else

            @endif

            @if ($lic->type==1) anual
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
            <td>{{$lic->Type()}}</td>
            <td>{{ date('d/m/Y', strtotime($lic->due_date) ) }}</td>
            <td>{{ $lic->dias() }}</td>
        </tr>
        @endif
        @endif
    @endforeach

@endsection

@section('script_footer')
    <script src="{{asset('material/assets/plugins/select2/dist/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script>
        $('.select2').select2();
        function filtro() {
            var sel = $('#filter').val();
            var classe = '';

            $('.cadastro').hide();
            if (sel == 1) classe = '.cadastro';
            if (sel == 2) classe = '.ativa';
            @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
            if (sel == 3) classe = '.anual';
            @endif
            if (sel == 4) classe = '.trinta';
            if (sel == 5) classe = '.quinze';
            @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
            if (sel == 6) classe = '.vencida';
            if (sel == 7) classe = '.bloqueada';
            if (sel == 8) classe = '.pendente';
            @endif
            $(classe).show();
            var qtd = $(classe).length;
            $('#qtd').html(qtd);
        }
    </script>
@endsection