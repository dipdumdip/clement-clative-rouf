@extends('layout.main')

@section('content')

<div class="row" style="margin-top:50px;">    
    <!-- forgot_pass -->
    @include('account.forgot_pass')

    <!-- signin -->
    @include('account.signin')

    <!-- create account -->
    @include('account.create')
</div>

@stop