@extends('layouts.edit')
@php
if ($cad->id>0)
    $title = 'Alterar Cadastro';
else
    $title = 'Novo Cadastro';
$route = 'rev';
@endphp

@section('script')
    <script type="text/javascript" src="{{asset('layouts/app/assets/js/plugins/forms/inputs/formatter.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('layouts/app/assets/js/plugins/forms/selects/select2.min.js')}}"></script>

    <script type="text/javascript" src="{{asset('layouts/app/assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('layouts/app/assets/js/plugins/forms/styling/switchery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('layouts/app/assets/js/plugins/forms/styling/switch.min.js')}}"></script>

    <script type="text/javascript" src="{{asset('my/js/switch.js')}}"></script>

    <script type="text/javascript" src="{{asset('layouts/app/assets/js/plugins/editors/summernote/summernote.min.js')}}"></script>
@endsection

@section('form')

    {{--Dados cadastrais--}}
    <div class="panel panel-default">
        <div class="panel-heading">Dados da Revenda</div>
        <div class="panel-body">

            <fieldset class="content-group">
                <legend class="text-bold"><i class=" icon-location4"></i> Dados cadastrais</legend>

                <div class="col-sm-3">
                    <div class="form-group {!! hasErrorClass($errors,'cnpj') !!}">
                        <label class="control-label" for="cnpj">CNPJ/CPF</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="cnpj" name="cnpj" maxlength="21"
                                   value="{{ old('cnpj') ? old('cnpj') : $cad->cnpj }}"
                                   placeholder="" required autofocus>
                            {!! helpBlock($errors,'cnpj') !!}
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-primary" onclick="ConsultaDoc('cnpj')" id="btn_cnpj"> <i class="icon-search4" id="ico_cnpj"></i> </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    {!! myInputText($errors,'ie','IE / RG',$cad->ie,'required') !!}
                </div>

                <div class="col-sm-3">
                    {!! myInputText($errors,'im','IM', ( $cad->juridic ? $cad->juridic->im : '') ) !!}
                </div>

                <div class="col-sm-3">
                    {!! myInputText($errors,'responsavel','Responsável', ( $cad->juridic ? $cad->juridic->responsavel : '') ) !!}
                </div>

                <div class="col-sm-7">
                    {!! myInputText($errors,'nome','Nome / Razão Social',$cad->nome,'required') !!}
                </div>

                <div class="col-sm-5">
                    {!! myInputText($errors,'fantasia','Nome Fantasia',( $cad->juridic ? $cad->juridic->fantasia : '')) !!}
                </div>
            </fieldset>

            <fieldset class="content-group">
                <legend class="text-bold"><i class=" icon-location4"></i> Endereço</legend>

                <div class="form-group col-sm-3 {!! hasErrorClass($errors,'cep') !!}">
                    <label class="control-label" for="cep" id="lbl_cep">CEP</label>
                    <div class="input-group">
                        <input type="text" class="form-control cep" id="cep" name="cep" maxlength="9"
                               value="{{ old('cep') ? old('cep') : $cad->cep }}"
                               placeholder="" required>
                        {!! helpBlock($errors,'cep') !!}
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary" onclick="ConsultaCep('')" id="btn_cep"> <i class="icon-search4" id="ico_cep"></i> </button>
                        </div>
                    </div>
                </div>

                <div class="form-group col-sm-2 address {!! hasErrorClass($errors,'uf') !!}">
                    <label class="control-label">Estado:</label>

                    <select class="form-control select" name='uf' id='uf' onchange='ListarCidades("cidade",this.value);' required>
                        <option></option>
                        {!!CarregarEstados( old('uf') ? old('uf') : $cad->uf )!!}
                    </select>
                    {!! helpBlock($errors,'uf') !!}
                </div>

                <div class="form-group col-sm-7 address {!! hasErrorClass($errors,'cidade') !!}">
                    <label class="control-label">Cidade:</label>

                    <select class="form-control select" name='cidade' id='cidade' required>
                        <option></option>
                        {!!CarregarCidades(old('uf') ? old('uf') : $cad->uf
                        , old('cidade') ? old('cidade') : $cad->cidade)!!}
                    </select>

                    {!! helpBlock($errors,'cidade') !!}
                </div>

                <div class="col-sm-10 address">
                    {!! myInputText($errors,'logradouro','Logradouro / Endereço',$cad->logradouro,'required') !!}
                </div>

                <div class="col-sm-2 address">
                    {!! myInputText($errors,'nro','Número',$cad->nro) !!}
                </div>

                <div class="col-sm-6 address">
                    {!! myInputText($errors,'bairro','Bairro',$cad->bairro,'required') !!}
                </div>

                <div class="col-sm-6 address">
                    {!! myInputText($errors,'complemento','Complemento',$cad->complemento) !!}
                </div>

            </fieldset>

        </div>
    </div>

    {{--Dados do MEI--}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="checkbox checkbox-switchery">
                <label>
                    <input type="checkbox" class="switchery" onchange="verDadosMei()" id="mei" name="mei">
                    Empresa emquadrada no MEI
                </label>
            </div>
        </div>

        <div class="panel-body" id="dadosmei">

            <fieldset class="content-group">
                <legend class="text-bold"><i class=" icon-location4"></i> Dados do Responsável</legend>

                <div class="col-sm-12">
                    {!! myInputText($errors,'mei_responsible','Responsável', ( $cad->mei ? $cad->mei->people->name : '') ) !!}
                </div>

                <div class="col-sm-3">
                    {!! myInputText($errors,'mei_cpf','CPF:',( $cad->mei ? $cad->mei->people->cnpj : '') ) !!}
                </div>

                <div class="col-sm-3">
                    {!! myInputText($errors,'mei_rg','RG',( $cad->mei ? $cad->mei->people->ie : '') ) !!}
                </div>
                <div class="col-sm-3">
                    {!! myInputText($errors,'mei_rg_org','Orgão Exp./UF',( $cad->mei ? $cad->mei->rg_org : '') ) !!}
                </div>

                <div class="col-sm-3">
                    <div class="form-group {!! hasErrorClass($errors,'mei_civil') !!}">
                        <label class="control-label">Estado Civil:</label>

                        <select class="form-control select" name='mei_civil' id='mei_civil'>
                            <option></option>
                        </select>
                        {!! helpBlock($errors,'mei_civil') !!}
                    </div>
                </div>

            </fieldset>


            <fieldset class="content-group">
                <legend class="text-bold"><i class=" icon-location4"></i> Endereço do responsável</legend>

                <div class="form-group col-sm-3 {!! hasErrorClass($errors,'mei_cep') !!}">
                    <label class="control-label" for="mei_cep" id="lbl_mei_cep">CEP</label>
                    <div class="input-group">
                        <input type="text" class="form-control cep" id="mei_cep" name="mei_cep" maxlength="9"
                               value="{{ old('mei_cep') ? old('mei_cep') : $cad->cep }}">
                        {!! helpBlock($errors,'mei_cep') !!}
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary" onclick="ConsultaCep('mei_')" id="btn_mei_cep"><i class="icon-search4" id="ico_mei_cep"></i> </button>
                        </div>
                    </div>
                </div>

                <div class="form-group col-sm-2 mei_address {!! hasErrorClass($errors,'mei_uf') !!}">
                    <label class="control-label">Estado:</label>

                    <select class="form-control select" name='mei_uf' id='mei_uf' onchange='ListarCidades("mei_cidade",this.value);'
                            required>
                        <option></option>
                        {!!CarregarEstados( old('mei_uf') ? old('mei_uf') : $cad->uf )!!}
                    </select>
                    {!! helpBlock($errors,'mei_uf') !!}
                </div>

                <div class="form-group col-sm-7 mei_address {!! hasErrorClass($errors,'mei_cidade') !!}">
                    <label class="control-label">Cidade:</label>

                    <select class="form-control select" name='mei_cidade' id='mei_cidade'>
                        <option></option>
                        {!!CarregarCidades(old('mei_uf') ? old('mei_uf') : $cad->uf
                        , old('mei_cidade') ? old('mei_cidade') : $cad->cidade)!!}
                    </select>

                    {!! helpBlock($errors,'mei_cidade') !!}
                </div>

                <div class="col-sm-10 mei_address">
                    {!! myInputText($errors,'mei_logradouro','Logradouro / Endereço') !!}
                </div>

                <div class="col-sm-2 mei_address">
                    {!! myInputText($errors,'mei_nro','Número',$cad->nro) !!}
                </div>

                <div class="col-sm-6 mei_address">
                    {!! myInputText($errors,'mei_bairro','Bairro',$cad->bairro) !!}
                </div>

                <div class="col-sm-6 mei_address">
                    {!! myInputText($errors,'mei_complemento','Complemento',$cad->complemento) !!}
                </div>

            </fieldset>

        </div>
    </div>

    {{--Contatos--}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-title"><i class="icon-address-book3 position-left"></i> Contatos</a></h5>
            <div class="heading-elements">
                <button type="button" class="btn btn-info btn-xs heading-btn" onclick="IncluirLinha()"><i class="icon-plus-circle2"></i> Adicionar</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover {{ $errors->has('contatos') ? ' has-error' : '' }}"  id="table-data">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Obs</th>
                </tr>
                </thead>
                <tbody>
                @for ($i=1;$i<=1;$i++)
                    <tr class="tr_clone">
                        <td class="p-5 col-xs-3">
                            <input name="contato_id[]" type="hidden" value="">
                            <input type="text"  class="form-control" name="contato_nome[]" id="contato_nome[]" value="" placeholder="Nome do contato">
                        </td>
                        <td class="p-5 col-xs-3">
                            <input type="text"  class="form-control" name="contato_fone[]" value="" placeholder="Nº telefone">
                        </td>
                        <td class="p-5 col-xs-3">
                            <input type="email" class="form-control" name="contato_email[]" value="" placeholder="E-mail">
                        </td>
                        <td class="p-5 col-xs-3">
                            <input type="text"  class="form-control" name="contato_obs[]"  value="" placeholder="Observação">
                        </td>
                        <td class="no-padding">
                            <a href="#" class="btn btn-danger btn-xs heading-btn removerCampo no-margin" style="display: none"><i class="icon-trash"></i> </a>
                        </td>
                    </tr>
                @endfor
                </tbody>
            </table>
        </div>

    </div>


    {{--Campos adicionais--}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-title"><i class="icon-info3 position-left"></i> Informações adicionais</a></h5>
        </div>
        <div class="panel-body">
            @for($i=1;$i<=6;$i++)
            <div class="col-sm-6">
                {!! myInputText($errors,'info'.$i,'Campo Adicional '.$i,'') !!}
            </div>
            @endfor
        </div>
    </div>


    {{--Observação--}}
    <div class="tabbable tab-content-bordered mb-20">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="active"><a href="#tab-obs1" data-toggle="tab">Observação</a></li>
            <li><a href="#tab-obs2" data-toggle="tab">Perfil do Cliente</a></li>
            <li><a href="#tab-obs3" data-toggle="tab">Aviso Interno</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane has-padding active" id="tab-obs1">
                {!! helpBlock($errors,'obs1') !!}
                <textarea name="obs1" id="obs1" class="note">{{ old('obs1') ? old('obs1') : $cad->obs1 }}</textarea>
            </div>

            <div class="tab-pane has-padding" id="tab-obs2">
                {!! helpBlock($errors,'obs2') !!}
                <textarea name="obs2" id="obs2" class="note">{{ old('obs2') ? old('obs2') : $cad->obs2 }}</textarea>
            </div>

            <div class="tab-pane has-padding" id="tab-obs3">
                {!! helpBlock($errors,'obs3') !!}
                <textarea name="obs3" id="obs3" class="note">{{ old('obs3') ? old('obs3') : $cad->obs3 }}</textarea>
            </div>
        </div>
    </div>

@endsection

@section('script_footer')
    <script src="{{ asset('my/js/rep/edit.js') }}"></script>

    <script>
        @if (old('cep') ? old('cep') : $cad->cep)
             $('.address').show();
        @endif

        @if (old('uf') ? old('uf') : $cad->uf)
            ListarCidades("cidade",'{{old('uf') ? old('uf') : $cad->uf}}','{{old('cidade') ? old('cidade') : $cad->cidade}}');
        @endif

        @if (old('mei_cep') ? old('mei_cep') : $cad->cep)
             $('.mei_address').show();
        @endif
        @if (old('mei_uf') ? old('mei_uf') : $cad->uf)
            ListarCidades("mei_cidade"
            ,'{{old('mei_uf') ? old('mei_uf') : $cad->mei->people->uf}}'
            ,'{{old('mei_cidade') ? old('mei_cidade') : $cad->mei->people->cidade}}'
        );
        @endif

    </script>
@endsection