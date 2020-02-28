@extends('layouts.auth')

@section('content')
    <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
        {{ csrf_field() }}
        <div class="form-group">
            <div class="col-xs-12">
                <h3>Recuperar senha</h3>
                <p class="text-muted">Digite seu e-mail e as instruções serão enviadas para você! </p>
            </div>
        </div>
        <div class="form-group ">
            <div class="col-xs-12 {{hasErrorClass($errors,'email')}}">
                <input name="email" class="form-control" type="email" required  autofocus placeholder="Seu E-mail">
                {!! helpBlock($errors,'email') !!}
            </div>
        </div>
        <div class="form-group text-center m-t-20">
            <div class="col-xs-12">
                <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Redefinir Senha</button>
                <a href="/login" class="btn btn-secondary btn-lg btn-block text-uppercase waves-effect waves-light">Cancelar</a>
            </div>
        </div>
    </form>

@endsection
