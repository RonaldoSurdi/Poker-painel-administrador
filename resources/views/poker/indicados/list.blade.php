@extends('layouts.list')
@php
$title = 'Clubes Indicados';
$route = 'poker.indic';
@endphp

@section('th')
    <th >Nome</th>
    <th >Responsável</th>
    <th >Telefone</th>
    <th >Status</th>
    <th >Clube Cadastrado</th>
    <th ></th>
@endsection

@section('tbody')
    @foreach($lista as $cad)
        <tr class="hover-visible cadastro @if ($cad->status>2) text-muted bloqueado @else ativo @endif">
            {{--<td class="">--}}

            {{--</td>--}}
            <td>{{$cad->name}}</td>
            <td>{{$cad->responsible}}</td>
            <td>{{$cad->phone}}</td>
            <td>{{$cad->Status()}}</td>
            <td>
                @if ($cad->club_id>0)
                    {{$cad->Club()}}
                @else
                    <a href="{{route($route.'.create',['id'=>$cad->id])}}" class="btn btn-info btn-labeled btn-xs"><b><i class="icon-plus2"></i></b> Cadastrar</a>
                @endif
            </td>
            <td class="text-nowrap" width="10px">
                <div class="action">
                    <a class="btn btn-sm btn-primary" href="{{route($route.'.create',['id'=>$cad->id])}}" data-toggle="tooltip" data-original-title="Cadastrar Club"> <i class="fa fa-plus-circle"></i> </a>
                    <a class="btn btn-sm btn-secondary" href="#" data-toggle="tooltip" data-original-title="Alterar"> <i class="fa fa-pencil"></i> </a>
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