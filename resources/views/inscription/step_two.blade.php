@extends('layouts.master')

@section('content')
    <h2>Step two {{$user->email}}</h2>
    <p><a href="{{url('inscription/step-one')}}">précédent</a></p>
    <form method="POST" action="{{url('inscription/step-two')}}">
        {{ csrf_field() }}
        <div class="form-text">
            <label class="label" for="name">{{trans('app.nameAddress')}}</label>
            <input class="input-text" id="name" name="name" type="name" value="{{old('name')}}" >
            @if($errors->has('name')) <span class="error">{{$errors->first('name')}}</span> @endif
        </div><div class="form-text">
            <label class="label" for="address">{{trans('app.nameAddress')}}</label>
            <input class="input-text" id="address" name="address" type="address" value="{{old('address')}}" >
            @if($errors->has('name')) <span class="error">{{$errors->first('name')}}</span> @endif
        </div>
        <div class="form-submit">
            <input type="submit" value="login" >
        </div>
    </form>
@stop