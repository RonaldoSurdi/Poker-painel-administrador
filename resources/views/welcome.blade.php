@extends('layouts.front')
@section('css')
    <style>
        html, body {
            /*background-color: #fff;*/
            color: #A0A0A0;
        }
    </style>
@endsection

@section('content')
    <BR><BR><BR>
    <!-- Error title -->
    <div class="text-center content-group">
        <h1 class="error-title offline-title">{{ config('app.name', 'Laravel') }}</h1>
        <h4></h4>
    </div>
    <!-- /error title -->
@endsection