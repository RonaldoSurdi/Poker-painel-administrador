@if(isset($lista))
    @if ($lista->count()>0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th >ID</th>
                    <th >Departamento</th>
                    <th >Status</th>
                    <th >Detalhes</th>
                    <th >Data Retorno</th>
                    <th >Data Cadastro</th>
                    <th ></th>
                </tr>
                </thead>
                <tbody>
                @foreach($lista as $item)
                    <tr class="hover-visible
                        @if ($item->status==0) text-muted
                        @elseif ($item->status==1) text-success
                        @elseif ($item->status==2) text-danger
                        @elseif ($item->status==3) text-info
                        @elseif ($item->status==4) text-warning
                        @elseif ($item->status==5) text-warning
                        @endif
                            ">
                        <td>
                            {{ $item->id }}
                        </td>
                        <td>{{$item->user_id.' - '.$item->adm}}</td>
                        <td>
                            @if ($item->status==0) ATIVO
                            @elseif ($item->status==1) RETORNO POSITIVO
                            @elseif ($item->status==2) RETORNO NEGATIVO
                            @elseif ($item->status==3) OBSERVAÇÃO
                            @elseif ($item->status==4) CANCELADO
                            @elseif ($item->status==5) ARQUIVADO
                            @endif
                        </td>
                        <td>
                            {{ $item->obs }}
                            @if (($item->status<>0)&&($item->status<>3))
                                <br/>Retorno:
                                <br>Deaprtamento: {{ $item->adm2 }}
                                <br/>Nota: {{ $item->obs_return }}
                                <br/>Data: {{ date('d/m/y H:m',strtotime($item->update_at)) }}
                            @endif
                        </td>
                        <td>
                            @if ($item->notify_admin==0) -
                            @elseif ($item->notify_admin==1) {{date('d/m/y H:m',strtotime($item->notify_at))}}
                            @endif
                        </td>
                        <td>
                            {{ date('d/m/y H:m',strtotime($item->created_at)) }}
                        </td>
                        <td class="text-nowrap" width="10px">
                            @if ( ($item->status==0) || ($item->status==3) )
                                <div class="action">
                                    <a onclick="CrmReturn('Alterar Status','{{$item->status}}','{{$item->id}}')" data-toggle="tooltip" data-original-title="Alterar Status">
                                        <i class="fa fa-info text-success"></i> Alterar</a>
                                </div>
                            @endif
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info" style="max-width: 500px">
            Nenhuma nota encontrada
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        </div>
    @endif
@endif
