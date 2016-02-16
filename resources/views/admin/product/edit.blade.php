@extends('layouts.admin')


@section('menu')
    <li><a href="{{url('product')}}">{{trans('app.listProduct')}}</a></li>
@stop

@section('content')
<form method="POST" action="{{url('product', $product->id)}}" enctype="multipart/form-data">
{{ csrf_field() }}
{{ method_field('PUT') }}
<section class="row">
<aside class="col w33">
    <div class="form-text">
        <label class="label" for="name">{{trans('app.name')}}</label>
        <input class="input-text" id="name" name="name" type="text" value="{{$product->name}}" >
        @if($errors->has('name')) <span class="error">{{$errors->first('name')}}</span> @endif
    </div>
    <div class="form-text">
        <label class="label" for="slug">{{trans('app.slugName')}}</label>
        <input class="input-text" id="slug" name="slug" type="text" value="{{$product->slug}}" >
        @if($errors->has('slug')) <span class="error">{{$errors->first('slug')}}</span> @endif
    </div>
    <div class="form-text">
        <label class="label" for="price">{{trans('app.price')}}</label>
        <input class="input-text  size" id="price" name="price" type="text" value="{{$product->price}}">
        @if($errors->has('price')) <span class="error">{{$errors->first('price')}}</span> @endif
    </div>
    <div class="form-text">
        <label class="label" for="quantity">{{trans('app.quantity')}}</label>
        <input class="input-text quantity" id="quantity" name="quantity" type="text" value="{{$product->quantity}}">
        @if($errors->has('quantity')) <span class="error">{{$errors->first('quantity')}}</span> @endif
    </div>
    <div class="content">
        <label>{{trans('app.abstract')}}</label>
        <textarea row="50" cols="50" name="abstract">{{$product->abstract}}</textarea>
        @if($errors->has('abstract')) <span class="error">{{$errors->first('abstract')}}</span> @endif
    </div>
    <div class="content">
    <label>{{trans('app.content')}}</label>
    <textarea row="100" cols="50" name="content">{{$product->content}}</textarea>
    @if($errors->has('content')) <span class="error">{{$errors->first('content')}}</span> @endif
    </div>
</aside>
<aside class="col w33">
<div class="form-select">
<label for="tag">{{trans('app.catName')}}</label>
<select name="category_id">
@foreach($categories as $id => $title)
    <option value="{{$id}}" {{$product->category_id==$id? 'selected' : ''}}>{{$title}}</option>
@endforeach
    <option value="0">{{trans('app.noCat')}}</option>
</select>
</div>
<div class="form-select">
    <label for="tag">{{trans('app.TagName')}}</label>
    <select id="tag" name="tags[]" multiple>
        @foreach($tags as $id => $name)
            <option value="{{$id}}" {{$product->hasTag($id)? 'selected' : ''}}>{{$name}}</option>
        @endforeach
    </select>
</div>
<div class="form-text">
    <label class="label" for="published_at">{{trans('app.date')}}</label>
    <input class="input-text" id="published_at" name="published_at" type="radio"
           value="true" {{($product->published_at!='0000-00-00 00:00:00')? 'checked' : ''}}>
    @if($errors->has('published_at')) <span class="error">{{$errors->first('published_at')}}</span> @endif
</div>
<div class="input-radio">
    <h2>{{trans('app.status')}}</h2>
    <input type="radio" name="status" value="opened" {{($product->status=='opened')? 'checked' : ''}}> opened<br>
    <input type="radio" name="status" value="closed" {{($product->status=='closed')? 'checked' : ''}}> closed<br>
</div>
<div class="input-file">
    <h2>{{trans('app.image')}}</h2>
    @if($product->picture)
        <img src="{{url('uploads',$product->picture->uri)}}" width="100">
        <p><label for="delete">{{trans('app.deleteImage')}}</label> <input id="delete" type="radio" name="delete" value="true" /></p>
        <h2>{{trans('app.removeImage')}}</h2>
    @endif
    <input class="file" type="file" name="thumbnail">

    @if($errors->has('image')) <span class="error">{{$errors->first('thumbnail')}}</span> @endif
</div>
</aside>
<div class="form-submit">
    <input class="btn btn-opened" type="submit" value="{{trans('app.updateProduct')}}">
</div>
</section>
</form>
@stop