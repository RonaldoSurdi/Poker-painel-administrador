@extends('layouts.auth')

@section('content')
    <form class="form-horizontal form-material" id="loginform" action="{{ route('password.request') }}" method="POST" >
        {{ csrf_field() }}

        <span class="text-muted text-center">Redefinir Senha</span>

        <div class="form-group  m-t-20 {{hasErrorClass($errors,'email')}}">
            <div class="col-xs-12">
                <input type="email" name="email" class="form-control" placeholder="Seu e-mail de acesso" value="{{ $email or old('email') }}"  required  autofocus>
                {!! helpBlock($errors,'email') !!}
            </div>
        </div>
        <div class="form-group {{hasErrorClass($errors,'password')}}">
            <div class="col-xs-12">
                <input type="password" name="password" class="form-control" placeholder="Sua senha" required>
                {!! helpBlock($errors,'password') !!}
            </div>
        </div>
        <div class="form-group ">
            <div class="col-xs-12">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirme sua senha" required>
            </div>
        </div>
        <div class="form-group text-center m-t-20">
            <div class="col-xs-12">
                <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Salvar</button>
                <a href="/login" class="btn btn-secondary btn-lg btn-block text-uppercase waves-effect waves-light">Cancelar</a>
            </div>
        </div>

    </form>
@endsection


