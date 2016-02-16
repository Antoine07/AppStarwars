<h2 class="best"><a href="{{url('prod', [$best->id, $best->slug])}}">{{trans('app.bestProduct')}} {{$best->name}}</a>
</h2>
{{ $best->abstract }}
@if($picture = $best->picture)
    <figure class="fl figure">
        <a href="{{url('prod', [$best->id, $best->slug])}}"> <img width="200"
                                                                  src="{{url('uploads',$picture->uri)}}"></a>
    </figure>
@endif
<p>{{trans('app.price')}} {{$best->price}}</p>