@extends('layouts.list')
@php
$title = 'Log de Atividades';
$route = 'audit.logs';
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
        <option value="2">Clubes</option>
        <option value="3">Licenças</option>
        <option value="4">Indicados</option>
        <option value="5">Usuários</option>
        <option value="6">Sistema</option>
        <option value="7">Logs</option>
    </select>
@endsection

@section('th')
    <th >ID</th>
    <th >Usuário</th>
    <th >Ip</th>
    <th >Ação</th>
    <th >Grupo</th>
    <th >Registro</th>
    <th >Informações</th>
    <th >Data</th>
@endsection

@section('tbody')
    @foreach($lista as $cad)
        @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
            @php($lic = $cad->model())
        <tr class="hover-visible todos
            @if ($lic=='CLUB') clube
            @elseif ($lic=='LICENSE') licenca
            @elseif ($lic=='CLUB_INDICADO') indicado
            @elseif ($lic=='CLUB_USER') usuario
            @elseif ($lic=='USER') sistema
            @elseif ($lic=='AUDIT_LOG') log
            @endif

        ">
            <td>{{$cad->id}}</td>
            <td>{{$cad->user_id}}</td>
            <td>{{$cad->ip}}</td>
            <td>{{$cad->action}}</td>
            <td>{{$cad->model}}</td>
            <td>{{$cad->reg_id}}</td>
            <td>{{$cad->info}}</td>
            <td>{{$cad->created_at}}</td>
            <!--<td class="text-nowrap" width="10px">
                <div class="action">
                    <a class="btn btn-sm btn-danger" onclick="ask_url('Deseja excluir este registro?','{{route($route.'.del',['id'=>$cad->id])}}')"
                       data-toggle="tooltip" data-original-title="Excluir"> <i class="fa fa-trash text-white"></i> </a>
                </div>
            </td>-->
        </tr>
        @endif
    @endforeach

@endsection

@section('script_footer')
    <script>
        function filtro() {
            var sel = $('#filter').val();
            var classe = '';

            $('.todos').hide();
            if (sel == 1) classe = '.todos';
            if (sel == 2) classe = '.clube';
            if (sel == 3) classe = '.licenca';
            if (sel == 4) classe = '.indicado';
            if (sel == 5) classe = '.usuario';
            if (sel == 6) classe = '.sistema';
            if (sel == 7) classe = '.log';
            @endif
            $(classe).show();
            var qtd = $(classe).length;
            $('#qtd').html(qtd);
        }
    </script>
@endsection