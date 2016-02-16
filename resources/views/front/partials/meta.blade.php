<p>{{trans('app.price')}}: {{$product->price}}</p>
<p>{{trans('app.tag')}}
    @forelse($product->tags as $tag)
        <small>{{$tag->name}}</small>
    @empty
        <small>{{trans('app.noTag')}}</small>
    @endforelse
</p>
<p>{{trans('app.dateCreate')}} {{$product->published_at}}</p>
<p>{{trans('app.quantity')}} {{$product->quantity}}, {{$product->status}} </p>

