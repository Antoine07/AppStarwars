@extends('layouts.master')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('content')
<div id="main-content">
    @include('front.partials.products')
</div>
@stop