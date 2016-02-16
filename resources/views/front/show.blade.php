@extends('layouts.master')

@section('content')
<h2 class="">{{$product->name}}</h2>
<div class="product clearfix">
    {{ $product->abstract }}
    @if($picture = $product->picture)
        <figure class="fl figure">
            <img src="{{url('uploads',$picture->uri)}}">
        </figure>
    @endif
    @include('front.partials.meta', compact('product'))
    @if(!empty($pop))
    <p>{{trans('app.pop')}} {{$pop->number_command}}, {{trans('app.note')}} {{$pop->score}} </p>
    @endif
    @include('front.partials.command', compact('product', 'quantity'))
</div>
@stop