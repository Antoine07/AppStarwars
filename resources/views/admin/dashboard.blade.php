@extends('layouts.admin')

@section('content')
Hello Admin
<ul>
    <li>    <a class="link" href="{{url('product')}}">{{trans('app.allProduct')}}</a>
    </li>
    <li>    <a class="link" href="{{url('history')}}">{{trans('app.allHistory')}}</a>
    </li>
</ul>
@stop