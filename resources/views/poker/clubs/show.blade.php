@extends('layouts.app')
@php
$title = 'Ficha do Clube';
$route = 'poker.club';
@endphp
@section('css')
    <link href="{{asset('material/assets/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
@endsection


@section('content')
    <center>
        <div class="container" style="max-width: 980px">
            <ul class="nav nav-tabs" role="tablist">
                {{--<li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Resumo</span></a> </li>--}}
                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#profile" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Dados</span></a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#lic" role="tab"><span class="hidden-sm-up"><i class="ti-check"></i></span> <span class="hidden-xs-down">Licença</span></a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#notfy" role="tab"><span class="hidden-sm-up"><i class="ti-check"></i></span> <span class="hidden-xs-down">Atendimento ao Cliente</span></a> </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content tabcontent-border text-left">
            {{--<div class="tab-pane active" id="home" role="tabpanel">--}}
            {{--<div class="p-20">--}}
            {{--<h3>{{$cad->name}}</h3>--}}
            {{--<p>Localizado em: {{ $cad->city() }} </p>--}}
            {{--</div>--}}
            {{--</div>--}}

            <!-- Dados cadastrais -->
                <div class="tab-pane active p-0  floating-labels" id="profile" role="tabpanel">
                    <input type="hidden" name="club_id" id="club_id" value="{{$cad->id}}">
                    <div class="ribbon-wrapper card m-0 border-0">
                        <div class="ribbon ribbon-primary">
                            Dados Cadastrais
                        </div>
                        <div class="text-right" style="margin-top: -40px">
                            <a href="{{route($route.'.edit',['id'=>$cad->id])}}" class="btn btn-primary"><i class="fa fa-pencil"></i> Editar</a>
                            @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
                                @if ($cad->status==1)
                                <button onclick="ask_url('Deseja Bloquear este cadastro?','{{route($route.'.active',['id'=>$cad->id])}}','{{\Illuminate\Support\Facades\Auth::user()->email}}')"
                                        class="btn btn-success btn-sm text-white" title="Bloquear este cadastro"><i class="fa fa-check"></i> Liberado </button>
                                @else
                                <button onclick="ask_url('Deseja Liberar este cadastro?','{{route($route.'.active',['id'=>$cad->id])}}','{{\Illuminate\Support\Facades\Auth::user()->email}}')"
                                        class="btn btn-warning btn-sm text-white"  title="Liberar este cadastro"><i class="fa fa-ban "></i> Bloqueado</button>
                                @endif
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    {!! MylabelView('CNPJ/CPF', FormataCpfCnpj( $cad->doc1) ) !!}
                                </div>

                                <div class="col-md-6">
                                    {!! MylabelView('RG/IE',$cad->doc2) !!}
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    {!! MylabelView('Nome do Clube',$cad->name) !!}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    {!! MylabelView('Responsável',$cad->responsible) !!}
                                </div>

                                <div class="col-md-4">
                                    {!! MylabelView('Telefone',$cad->phone) !!}
                                </div>

                                <div class="col-md-4">
                                    {!! MylabelView('Whatszapp',$cad->whats) !!}
                                </div>

                                <div class="col-md-6">
                                    {!! MylabelView('E-mail',$cad->email) !!}
                                </div>

                                <div class="col-md-6">
                                    {!! MylabelView('Site',$cad->site) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ribbon-wrapper card m-0 border-0">
                        <div class="ribbon ribbon-primary">
                            Endereço
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-3">
                                    {!! MylabelView('CEP', FormatarCEP($cad->zipcode) ) !!}
                                </div>
                                <div class="col-md-offset-3 col-md-6 address">
                                    {!! MylabelView('Cidade / UF',$cad->city()) !!}
                                </div>
                            </div>

                            <div class="row address">
                                <div class="col-md-10">
                                    {!! MylabelView('Endereço',$cad->address) !!}
                                </div>
                                <div class="col-md-2">
                                    {!! MylabelView('Número',$cad->number) !!}
                                </div>
                                <div class="col-md-6">
                                    {!! MylabelView('Bairro',$cad->district) !!}
                                </div>
                                <div class="col-md-6">
                                    {!! MylabelView('Complemento',$cad->complement) !!}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Licencas -->
                <div class="tab-pane p-20" id="lic" role="tabpanel">
                    <h4>Licença Atual: <b id="lic_now">@if($cad->premium()) Premium @else Free @endif </b></h4>

                    <div class="ribbon-wrapper card border-0" style="margin:-20px;margin-top: 0;">
                        <div class="ribbon ribbon-primary">
                            Acesso ao Painel Gerencial do clube
                        </div>
                        <div class="card-body p-0">
                            <form class="form-group row" id="frm_user">
                                {{ csrf_field() }}
                                <input type="hidden" name="club" value="{{$cad->id}}">
                                <input type="hidden" name="email" id="email" value="{{$cad->id}}">
                                <div class="col-sm-6">
                                    <label for="exampleInputuname3" class="control-label">E-mail</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="ti-user"></i></div>
                                        <input type="text" class="form-control" id="lic_user" name="lic_user" placeholder="E-mail para acesso" value="{{ $cad->User_email() ? $cad->User_email() : $cad->email  }}">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="inputPassword4" class="control-label">Senha</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="ti-lock"></i></div>
                                        <input type="text" class="form-control" id="lic_pass" name="lic_pass">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="btn-group pull-right" style="padding-top: 32px">
                                        <button onclick="SaveUser()" id="btn_saveUser" type="button" class="btn btn-success" data-toggle="tooltip" data-original-title="Salvar"><i class="fa fa-floppy-o"></i></button>
                                        <button onclick="ResetUser()" id="btn_sendUser" type="button" class="btn btn-info" data-toggle="tooltip" data-original-title="Enviar Link para Trocar a Senha"><i class="fa fa-send-o"></i></button>
                                        @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
                                        <button onclick="askDelUser()" id="btn_delUser" type="button" class="btn btn-danger"  data-toggle="tooltip" data-original-title="Excluir Usuário"><i class="fa fa-trash-o"></i> </button>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="ribbon-wrapper card border-0" style="margin:-20px;margin-top: 0;">
                        <div class="ribbon ribbon-primary">
                            Licenças deste clube
                        </div>

                        <div class="text-right" style="margin-top: -40px">
                            <button type="button" onclick="LicAdd()" class="btn btn-success"><i class="fa fa-plus-circle"></i> Adicionar</button>
                        </div>

                        <div class="card-body p-0" id="lista_license">

                        </div>
                    </div>

                </div>

                <!-- Atendimento ao Cliente -->
                <div class="tab-pane p-0" id="notfy" role="tabpanel">
                    <div class="ribbon-wrapper card border-0" style="margin-top: 0;">
                        <div class="ribbon ribbon-primary">
                            Atendimento ao Cliente
                        </div>

                        <div class="text-right" style="margin-top: -40px">
                            <button type="button" onclick="CrmNotify('Adicionar Anotação','{{$cad->id}}')" class="btn btn-success" id="buttoncrm"><i class="fa fa-plus-circle"></i> Adicionar Nota</button>
                        </div>


                        <div class="card-body p-0" id="lista_notes">

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </center>

@endsection

@section('script_footer')
    <!-- sample modal content -->
    <div id="modalLic" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Adicionar nova Licença</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="modal-body" style="padding-right:20px" id="frm_new">
                    {{ csrf_field() }}
                    <input type="hidden" name="club" value="{{$cad->id}}">
                    <div class="form-group row">
                        <label for="lic_type" class="control-label text-right col-md-4  m-t-5">Tipo:</label>
                        <div class="col-md-8">
                            <select class="form-control select2" id="lic_type" name="lic_type"  style="width: 100%">
                                <option value="2">30 dias gratis</option>
                                @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
                                <option value="1">Licença Anual</option>
                                @endif
                            </select>
                        </div>
                    </div>

                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onclick="addLic()" id="btn_saveLic">Salvar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


    <!-- sample modal content -->
    <div id="modalPay"  class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg" style="max-width: 95%">
            <div class="modal-content">
                {{--<div class="modal-header">--}}
                    {{--<h4 class="modal-title" id="myLargeModalLabel">Pagamentos da Licença</h4>--}}
                    {{--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>--}}
                {{--</div>--}}
                <div class="modal-body p-0">
                    <iframe src="" width="100%" height="500" name="frame" id="frame"></iframe>
                </div>
                <div class="modal-footer m5">
                    <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Fechar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


    <script src="{{asset('material/assets/plugins/select2/dist/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('my/js/license.js')}}"></script>
    <script src="{{asset('my/js/crm2.js')}}"></script>
    <script src="{{asset('my/js/club_user.js')}}"></script>
    <script>

    </script>
@endsection