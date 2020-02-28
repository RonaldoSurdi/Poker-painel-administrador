@if(isset($lista))
    @if ($lista->count()>0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th >Tipo</th>
                    <th >Valor</th>
                    <th >Inicio</th>
                    <th >Validade</th>
                    <th >Status</th>
                    <th >Pagamento</th>
                    <th ></th>
                </tr>
                </thead>
                <tbody>
                @foreach($lista as $item)
                    <tr class="hover-visible
                        @if ($item->status==1) text-success
                        @elseif ($item->status==2) text-muted
                        @elseif ($item->status==9) text-muted
                        @endif
                            ">
                        <td>{{$item->Type()}}</td>
                        <td>
                            @if ($item->type==2)
                                - - -
                            @else
                                {{'R$ '.number_format($item->value, 2, ',', '.')}}
                            @endif
                        </td>
                        <td>
                            @if ($item->start_date)
                                {{ date('d/m/Y', strtotime($item->start_date) ) }}
                            @endif
                        </td>
                        <td>
                            @if ($item->due_date)
                                {{ date('d/m/Y', strtotime($item->due_date) ) }}
                            @endif    
                        </td>
                        <td>
                            {{ $item->Status() }}
                        </td>
                        <td>
                            @if ($item->type==2)
                                - - -
                            @elseif($item->type==2999999)
                                <a href="#" id='btn_pay_{{$item->id}}' onclick="getPay({{$item->id}})" class="btn btn-sm
                                    @if ($item->payment)
                                        btn-success"> Ver pagamentos
                                    @else
                                        btn-info"> PAGAR
                                    @endif
                                </a>
                            @endif
                        </td>
                        @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
                            <td class="text-nowrap" width="10px">
                                @if ( ($item->status==0) )
                                    <div class="action">
                                        <a onclick="lic_del('{{$item->id}}')" data-toggle="tooltip" data-original-title="Excluir">
                                            <i class="fa fa-trash text-danger"></i> </a>
                                    </div>
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info" style="max-width: 500px">
            Nenhuma licença encontrada
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        </div>
    @endif
@endif
