@extends('layouts.auth')

@section('content')
    <form class="form-horizontal form-material" id="loginform" action="{{ route('login') }}" method="POST" >
        {{ csrf_field() }}

        <span class="text-muted text-center">Informe suas credenciais de acesso</span>

        <div class="form-group  m-t-20 {{hasErrorClass($errors,'email')}}">
            <div class="col-xs-12">
                <input type="email" name="email" class="form-control" placeholder="Seu e-mail de acesso" value="{{old('email')}}" required  autofocus>
                {!! helpBlock($errors,'email') !!}
            </div>
        </div>
        <div class="form-group {{hasErrorClass($errors,'password')}}">
            <div class="col-xs-12">
                <input type="password" name="password" class="form-control"  required placeholder="Senha">
                {!! helpBlock($errors,'password') !!}
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12">
                <div class="checkbox checkbox-primary pull-left p-t-0">
                    <input id="checkbox-signup" type="checkbox">
                    <label for="checkbox-signup"> Lembrar me </label>
                </div>
                <a href="{{ route('password.request') }}" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> Esqueceu sua senha?</a> </div>
        </div>
        <div class="form-group text-center m-t-20">
            <div class="col-xs-12">
                <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Entrar</button>
            </div>
        </div>
        {{--<div class="row">--}}
            {{--<div class="col-xs-12 col-sm-12 col-md-12 m-t-10 text-center">--}}
                {{--<div class="social"><a href="javascript:void(0)" class="btn  btn-facebook" data-toggle="tooltip"  title="Login with Facebook"> <i aria-hidden="true" class="fa fa-facebook"></i> </a> <a href="javascript:void(0)" class="btn btn-googleplus" data-toggle="tooltip"  title="Login with Google"> <i aria-hidden="true" class="fa fa-google-plus"></i> </a> </div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="form-group m-b-0">--}}
            {{--<div class="col-sm-12 text-center">--}}
                {{--<p>Não tem uma conta? <a href="{{route('register')}}" class="text-primary m-l-5"><b>Cadastre-se</b></a></p>--}}
            {{--</div>--}}
        {{--</div>--}}
    </form>
@endsection

