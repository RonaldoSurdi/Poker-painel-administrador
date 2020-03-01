@extends('layouts.edit')
@php
$title = 'Usuários';
$route = 'config.user';
if ($cad->id>0){
    $routeSave = 'config.user.update';
}
@endphp

@section('form')
    <div class="row">
        <div class="col-sm-12">
            {!! MyTextField($errors,'name','Nome',(Old('name')? Old('name') : $cad->name),'required autofocus') !!}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            {!! MyTextField($errors,'email','E-mail', (Old('email')? Old('email') : $cad->email ) ,'required') !!}
        </div>
    </div>

    @if ($cad->id==0)
    <div class="row">
        <div class="col-sm-6">
            {!! MyField('password',$errors,'password','Senha','') !!}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            {!! MyField('password',$errors,'password_confirmation','Confirme a Senha','') !!}
        </div>
    </div>
    @endif
@endsection