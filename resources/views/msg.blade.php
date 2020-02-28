@extends('layouts.app_blank')

@section('main')
    <BR><BR><BR>
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
@endsection