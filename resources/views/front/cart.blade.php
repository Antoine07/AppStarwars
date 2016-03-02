@extends('layouts.master')

@section('content')
<div id="main-content">
@if(Cookie::has('cart'))
<form method="POST" action="{{url('updatedCommand')}}">
{{csrf_field()}}
    <ul>
        @forelse($products as $id => $product )
            <input type="hidden" name="product_id[]" value="{{$id}}"/>
            <li> price: {{$product['price']}}, {{$product['name']}}, {{trans('app.quantity')}} {{$product['quantity']}}
                <br/>
                <label for="reset{{$id}}">{{trans('app.deleteOne')}}: </label><a class="btn btn-danger" href="{{url('deleteOne', ['product_id'=>$id])}}">{{trans('app.deleteJustOne')}}(-1)</a>
            </li>
        @empty
            <li>{{trans('app.sorryNoProductIntoCart')}}</li>
        @endforelse
    </ul>
    <div class="info">
        <p>{{trans('app.total')}}: {{$total}}, {{trans('app.numberProduct')}}: {{$number}}</p>
        <p><label for="delete">{{trans('app.resetAllProduct')}} </label><input id="delete" type="checkbox" name="delete" value="true"/></p>
    </div>
    <div class="form-submit">
       <input type="submit" value="{{trans('app.updatedCommand')}}" >
    </div>
</form>
@else
<p>{{trans('app.yourCartIsEmpty')}}</p>
@endif
</div>
@stop