@extends('layouts.admin')

@section('content')
<table class="table">
    <thead>
    <tr>
        <th>status</th>
        <th>Name</th>
        <th>Price</th>
        <th>quantity</th>
        <th>date</th>
        <th>category</th>
        <th>tags</th>
        <th>action</th>
    </tr>
    </thead>
    <p><a class="btn btn-add" href="{{url('product/create')}}">Add product</a></p>
@forelse($products as $product)
    <tr>
    <td><a href="{{url('product',['status', $product->id])}}" class="btn btn-{{$product->status}}" > {{$product->status}}</a></td>
    <td><a class="btn btn-opened" href="{{url('product', [$product->id, 'edit'])}}">{{$product->name}}</a></td>
    <td>{{$product->price}}</td>
    <td>{{$product->quantity}}</td>
    <td>{{$product->published_at}}</td>
    <td>{{($cat = $product->category)? $cat->title : 'No category'}}</td>
    <td>@if(count($product->tags)>0) @foreach($product->tags as $tag ) {{$tag->name}} @endforeach @else No tags @endif</td>
    <td>
    <form method="POST" action="{{url('product', $product->id)}}">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}
       <input class="btn btn-closed" type="submit" value="delete" >
    </form>
    </td>
    </tr>
@empty
@endforelse
    {{$products->links()}}
</table>
@stop
