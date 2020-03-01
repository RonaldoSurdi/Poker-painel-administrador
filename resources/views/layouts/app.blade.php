@extends('layouts.app_blank')

@section('main')
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="{{route('app.home')}}">
                        <!-- Logo icon -->
                        <b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="{{asset('material/assets/images/logo-icon.png')}}" alt="homepage" class="dark-logo" />
                            <!-- Light Logo icon -->
                            <img src="{{asset('material/assets/images/logo-light-icon.png')}}" alt="homepage" class="light-logo" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <span>
                         <!-- dark Logo text -->
                         <img src="{{asset('my/imgs/logo_blue.png')}}" alt="homepage" class="dark-logo" />
                            <!-- Light Logo text -->
                         <img src="{{asset('my/imgs/logo_light.png')}}" class="light-logo" alt="homepage" /></span> </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <li class="nav-item"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">

                        <!-- ============================================================== -->
                        <!-- Profile -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href=""
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{{asset('my/imgs/sem_avatar.png')}}" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right scale-up">
                                <ul class="dropdown-user">
                                    @if (\Illuminate\Support\Facades\Auth::check())
                                    <li>
                                        <div class="dw-user-box">
                                            <div class="u-text">
                                                <h4>{{ \Illuminate\Support\Facades\Auth::user()->name }}</h4>
                                                <p class="text-muted">{{ \Illuminate\Support\Facades\Auth::user()->email }}</p>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    {{--<li role="separator" class="divider"></li>--}}
                                    {{--<li><a href="#"><i class="ti-user"></i> Meu cadastro</a></li>--}}
                                    {{--<li><a href="#"><i class="ti-email"></i> E-mail</a></li>--}}
                                    <li role="separator" class="divider"></li>
                                    <li><a href="{{route('logout')}}"><i class="fa fa-power-off"></i> Sair</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li>
                            <a  href="{{route('poker.club.home')}}" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard </span></a>
                        </li>
                        <li>
                            <a  href="{{route('poker.club.all')}}" aria-expanded="false"><i class="mdi mdi-home"></i><span class="hide-menu">Cadastro de Clubes </span></a>
                        </li>
                        <li>
                            <a  href="{{route('poker.club.list.premium')}}" aria-expanded="false"><i class="mdi mdi-certificate"></i><span class="hide-menu">Licenças </span></a>
                        </li>
                        <li>
                            <a  href="{{route('poker.crm',['count'=>'100'])}}" aria-expanded="false"><i class="mdi mdi-eye"></i><span class="hide-menu">Notificações </span></a>
                        </li>
                        @if (\Illuminate\Support\Facades\Auth::user()->id === 1)
                        <li>
                            <a  href="{{route('poker.audit',['count'=>'100'])}}" aria-expanded="false"><i class="mdi mdi-bookmark"></i><span class="hide-menu">Log de Atividades </span></a>
                        </li>
                        @endif
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
            <!-- Bottom points-->
            <div class="sidebar-footer">
                <!-- item-->
                <a href="{{route('config.user')}}" class="link" data-toggle="tooltip" title="Configurações"><i class="ti-settings"></i></a>
                <!-- item-->
                <a href="https://saintec.com.br/webmail" class="link" data-toggle="tooltip" title="E-mail"><i class="mdi mdi-gmail"></i></a>
                <!-- item-->
                <a href="{{route('logout')}}" class="link" data-toggle="tooltip" title="Sair"><i class="mdi mdi-power"></i></a>
            </div>
            <!-- End Bottom points-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                @if (isset($title))
                <div class="row page-titles">
                    <div class="col-md-5 col-6 align-self-center">
                        <h3 class="text-themecolor m-b-0 m-t-0">{!! $title !!}</h3>
                    </div>
                    <div class="col-md-7 col-6 align-self-center">
                        <div class="d-flex m-t-10 justify-content-end">
                            @yield('nav_title')
                        </div>

                    </div>
                </div>
                @endif

                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->

                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <Center>
                    @if (Session::has('ok'))
                        <div class="alert bg-success alert-styled-left" style="max-width: 500px">
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                            {!!  Session::pull('ok') !!}
                        </div>
                    @endif
                    @if (Session::has('info'))
                        <div class="alert bg-info alert-styled-left" style="max-width: 500px">
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                            {!!  Session::pull('info') !!}
                        </div>
                    @endif
                    @if (Session::has('aviso'))
                        <div class="alert alert-warning alert-styled-left" style="max-width: 500px">
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                            {!!  Session::pull('aviso') !!}
                        </div>
                    @endif
                    @if (Session::has('erro'))
                        <div class="alert bg-danger-400 alert-styled-left" style="max-width: 500px">
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                            {!!  Session::pull('erro') !!}
                        </div>
                    @endif
                </Center>

                @yield('content')

                <!-- ============================================================== -->
                <!-- End Page Content -->
                <!-- ============================================================== -->

            </div>

        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
@endsection
