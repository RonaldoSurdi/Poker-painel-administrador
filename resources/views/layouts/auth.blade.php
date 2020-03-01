@extends('layouts.app_blank')

@section('main')

<section id="wrapper" class="login-register login-sidebar"  style="background-image:url({{ asset('material//assets/images/background/login-register.jpg') }});">
    <div class="login-box card">
        <div class="card-body">
            <a href="/" class="text-center db"><img src="{{asset('my/imgs/logo.png')}}" alt="Home" />
            </a>
            <div class="divider m-t-40">
            </div>

            {{--@if($errors->any())--}}
                {{--<div class="alert alert-warning">--}}
                    {{--<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>--}}
                    {{--<h3 class="text-warning"><i class="fa fa-exclamation-triangle"></i> Warning</h3>--}}
                    {{--<ul >--}}
                        {{--@foreach( $errors->all() as $erro)--}}
                            {{--<li>{{$erro}}</li>--}}
                        {{--@endforeach--}}
                    {{--</ul>--}}
                {{--</div>--}}
            {{--@endif--}}

            <Center>
                @if (Session::has('ok'))
                    <div class="alert alert-success" style="max-width: 500px">
                        {!!  Session::pull('ok') !!}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                    </div>
                @endif
                @if (Session::has('info'))
                    <div class="alert alert-info" style="max-width: 500px">
                        {!!  Session::pull('info') !!}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                    </div>
                @endif
                @if (Session::has('aviso'))
                    <div class="alert alert-warning" style="max-width: 500px">
                        {!!  Session::pull('aviso') !!}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                    </div>
                @endif
                @if (Session::has('erro'))
                    <div class="alert alert-danger" style="max-width: 500px">
                        {!!  Session::pull('erro') !!}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                    </div>
                @endif
            </Center>

            @yield('content')

        </div>
    </div>
</section>
@endsection

