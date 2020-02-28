<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Daniel Carus">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('material/assets/images/favicon.png')}}">
    @if (isset($page_title))
        <title>{{ $page_title }}</title>
    @else
        <title>{{ config('app.name', 'PokerClubs') }}</title>
    @endif
    <link href="{{asset('material/assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('material/assets/plugins/toast-master/css/jquery.toast.css')}}" rel="stylesheet">
    <link href="{{asset('material/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('material/css/colors/purple-dark.css')}}" id="theme" rel="stylesheet">
    <link href="{{asset('my/css/ripples.min.css')}}" rel="stylesheet">
    <link href="{{asset('material/assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}" rel="stylesheet">
    @yield('css')
    <link href="{{asset('my/css/my.css')}}" rel="stylesheet">
    <link href="{{asset('my/css/sweetalert2.min.css')}}" rel="stylesheet" type="text/css">
    <!--[if lt IE 9]>
    <script src="{{asset('my/js/html5shiv.min.js')}}"></script>
    <script src="{{asset('my/js/respond.min.js')}}"></script>
    <![endif]-->
    @yield('script_head')
    <script type="text/javascript" src="{{asset('my/js/sweetalert2.min.js')}}"></script>
</head>
<body class="fix-sidebar fix-header card-no-border mini-sidebar">
    <!-- Preloader - style you can find in spinners.css -->
        <div class="preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
            </svg>
        </div>
    <!-- Main wrapper - style you can find in pages.scss -->
    @yield('main')
    <script src="{{asset('material/assets/plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('material/assets/plugins/bootstrap/js/popper.min.js')}}"></script>
    <script src="{{asset('material/assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('material/js/jquery.slimscroll.js')}}"></script>
    <script src="{{asset('material/js/waves.js')}}"></script>
    <script src="{{asset('material/js/sidebarmenu.js')}}"></script>
    <script src="{{asset('material/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js')}}"></script>
    <script src="{{asset('material/assets/plugins/sparkline/jquery.sparkline.min.js')}}"></script>
    <script src="{{asset('material/js/custom.js')}}"></script>
    <script src="{{asset('material/assets/plugins/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('my/js/ripples.min.js')}}"></script>
    <script src="{{asset('my/js/material.min.js')}}"></script>
    <script src="{{asset('my/js/moment-with-locales.min.js')}}"></script>
    <script src="{{asset('material/assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>
    <script src="{{asset('my/js/msg.js')}}"></script>
    <script src="{{asset('my/js/my.js')}}"></script>
    @yield('script_footer')
    @if (\Illuminate\Support\Facades\Auth::check())
    {{--<script src="{{asset('material/assets/plugins/session-timeout/jquery.sessionTimeout.min.js')}}"></script>--}}
    {{--<script src="{{asset('material/assets/plugins/session-timeout/session-timeout-init.js')}}"></script>--}}
    @endif
    <script src="{{asset('material/assets/plugins/styleswitcher/jQuery.style.switcher.js')}}"></script>
    <script>
        setTimeout(function(){
            @if (Session::has('Sok'))
                aviso('success','{!!  Session::pull('Sok') !!}','Sucesso!');
            @endif
            @if (Session::has('Sinfo'))
                aviso('info','{!!  Session::pull('Sinfo') !!}','Informação!');
            @endif
            @if (Session::has('Saviso'))
                aviso('warning','{!!  Session::pull('Saviso') !!}','Alerta!');
            @endif
            @if (Session::has('Serro'))
                aviso('error','{!!  Session::pull('Serro') !!}','Erro!');
            @endif
        }, 50);
    </script>
</body>
</html>