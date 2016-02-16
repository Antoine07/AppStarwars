@extends('layouts.master')

@section('content')
    @forelse($products as $product)
        <div class="product clearfix">
            <h2 class=""><a href="{{url('prod', [$product->id, $product->slug])}}">{{$product->name}}</a></h2>
            {{ $product->abstract }}
            @if($picture = $product->picture)
                <figure class="fl figure">
                    <a href="{{url('prod', [$product->id,$product->slug])}}"><img width="200"  src="{{url('uploads',$picture->uri)}}"></a>
                </figure>
            @endif
            @include('front.partials.meta', compact('product'))
        </div>
    @empty
        <p>No product</p>
    @endforelse
    {!! $products->links() !!}
@stop