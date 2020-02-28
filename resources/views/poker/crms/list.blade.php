@extends('layouts.list')
@php
    $title = 'Notificações';
    $route = 'poker.crm';
@endphp

@section('nav_title')
@endsection

@section('filter')
    <select class="form-control" id="filter" onchange="filtro()" style="width: 200px">
        <option value="0">Todos</option>
        <option value="1">Ativos</option>
        <option value="2">Retornos Positivos</option>
        <option value="3">Retornos Negativos</option>
        <option value="4">Observaços</option>
        <option value="5">Cancelados</option>
        <option value="6">Arquivados</option>
    </select>
@endsection

@section('th')
    <th >ID</th>
    <th >Clube</th>
    <th >Departamento</th>
    <th >Status</th>
    <th >Detalhes</th>
    <th >Data Retorno</th>
    <th >Data Cadastro</th>
    <th ></th>
@endsection

@section('tbody')
    @foreach($lista as $cad)
        @php($lic = $cad->status)
        <tr class="hover-visible todos
        @if ($lic==0) ativo
        @elseif ($lic==1) positivo
        @elseif ($lic==2) negativo
        @elseif ($lic==3) observacao
        @elseif ($lic==4) cancelado
        @elseif ($lic==5) arquivado
        @endif

                ">
            <td>{{$cad->id}}</td>
            <td>{{$cad->club_id.' - '.$cad->club}}</td>
            <td>{{$cad->user_id.' - '.$cad->adm}}</td>
            <td>
                @if ($lic==0) ATIVO
                @elseif ($lic==1) RETORNO POSITIVO
                @elseif ($lic==2) RETORNO NEGATIVO
                @elseif ($lic==3) OBSERVAÇÃO
                @elseif ($lic==4) CANCELADO
                @elseif ($lic==5) ARQUIVADO
                @endif
            </td>
            <td>
                {{ $cad->obs }}
                @if (($cad->status<>0)&&($cad->status<>3))
                    <br/>Retorno:
                    <br>Deaprtamento: {{ $cad->adm2 }}
                    <br/>Nota: {{ $cad->obs_return }}
                    <br/>Data: {{ date('d/m/y H:m',strtotime($cad->update_at)) }}
                @endif
            </td>
            <td>
                @if ($cad->notify_admin==0) -
                @elseif ($cad->notify_admin==1) {{date('d/m/y H:m',strtotime($cad->notify_at))}}
                @endif
            </td>
            <td>
                {{ date('d/m/y H:m',strtotime($cad->created_at)) }}
            </td>
            <td class="text-nowrap" width="10px">
                @if ( ($cad->status==0) || ($cad->status==3) )
                    <div class="action">
                        <a onclick="CrmReturn('Alterar Status','{{$cad->status}}','{{$cad->id}}')" data-toggle="tooltip" data-original-title="Alterar Status">
                            <i class="fa fa-info text-success"></i> Alterar</a>
                    </div>
                @endif
            </td>
        </tr>
    @endforeach
@endsection

@section('script_footer')
    <script src="{{asset('my/js/crm.js')}}"></script>
    <script>
        function filtro() {
            var sel = $('#filter').val();
            var classe = '';

            $('.todos').hide();
            if (sel == 0) classe = '.todos';
            if (sel == 1) classe = '.ativo';
            if (sel == 2) classe = '.positivo';
            if (sel == 3) classe = '.negativo';
            if (sel == 4) classe = '.observacao';
            if (sel == 5) classe = '.cancelado';
            if (sel == 6) classe = '.arquivado';
            $(classe).show();
            var qtd = $(classe).length;
            $('#qtd').html(qtd);
        }
    </script>
@endsection