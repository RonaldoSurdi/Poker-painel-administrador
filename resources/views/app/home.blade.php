@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                {{ \Illuminate\Support\Facades\Auth::user()->name }}, você está logado no {{ config('app.name', 'Laravel') }}!
            </div>
        </div>
    </div>
</div>
@endsection
