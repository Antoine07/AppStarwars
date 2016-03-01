@forelse($products as $product)
<div class="product clearfix offset" data-offset="{{$count++}}">
    <h2><a href="{{url('prod', [$product->id,$product->slug])}}">{{$product->name}}</a></h2>
    {{ $product->abstract }}
    @if($picture = $product->picture)
        <figure class="fl figure">
            <a href="{{url('prod', [$product->id,$product->slug])}}"><img src="{{url('uploads',[$picture->uri])}}" alt="{{$picture->title}}" class="img-responsive" /></a>
        </figure>
    @endif
    @if($cat=$product->category)
        <p>{{trans('app.catName')}}<a href="{{url('cat', [ $cat->id, str_slug($cat->title)])}}">{{trans('app.category')}}{{$cat->title}}</a></p>
    @endif
    @include('front.partials.meta', compact('product'))
</div>
@empty
    <p>No product</p>
@endforelse