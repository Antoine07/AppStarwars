@extends('layouts.master')

@section('content')
    @forelse($histories as $history)
        <div class="product clearfix offset">
            <h2>{{$history->product->name}}, {{trans('app.quantity')}} {{$history->quantity}}</h2>
        </div>
    @empty
        <p>No product</p>
    @endforelse
@endsection