@extends('layouts.auth')

@section('content')
    <form class="form-horizontal form-material" action="{{ route('register') }}" method="POST" >
        {{ csrf_field() }}
        <div class="text-center">
            <div class="icon-object border-success text-success"><i class="icon-plus3"></i></div>
            <h5 class="content-group">Criar uma nova conta
                <br><small class="display-block">Preencha todos os campos</small></h5>
        </div>

        <div class="form-group  m-t-40 {{hasErrorClass($errors,'name')}}">
            <div class="col-xs-12">
                <input type="text" name="name" class="form-control" placeholder="Seu nome" value="{{old('name')}}" required autofocus>
                {!! helpBlock($errors,'name') !!}
            </div>
        </div>
        <div class="form-group {{hasErrorClass($errors,'email')}}">
            <div class="col-xs-12">
                <input type="email" name="email" class="form-control" placeholder="Seu e-mail" value="{{old('email')}}" required>
                {!! helpBlock($errors,'email') !!}
            </div>
        </div>
        <div class="form-group {{hasErrorClass($errors,'password')}}">
            <div class="col-xs-12">
                <input type="password" name="password" class="form-control" placeholder="Sua senha" required>
                {!! helpBlock($errors,'password') !!}
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirme sua senha" required>
            </div>
        </div>

        <div class="form-group text-center m-t-20">
            <div class="col-xs-12">
                <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Cadastrar</button>
                <a href="/login" class="btn btn-secondary btn-lg btn-block text-uppercase waves-effect waves-light">Cancelar</a>
            </div>
        </div>

    </form>


@endsection
