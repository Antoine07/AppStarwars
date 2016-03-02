@extends('layouts.master')

@section('content')
    <h2>Step two {{$user->email}}</h2>
    <p><a href="{{url('inscription/step-one')}}">précédent</a></p>
    <form method="POST" action="{{url('inscription/step-two')}}">
        {{ csrf_field() }}
        <div class="form-text">
            <label class="label" for="address">{{trans('app.nameAddress')}}</label>
            <textarea name="address" id="address" cols="30" rows="10">{{old('address')}}</textarea>
            @if($errors->has('address')) <span class="error">{{$errors->first('address')}}</span> @endif
        </div>
        <div class="form-submit">
            <input type="submit" value="login" >
        </div>
    </form>
@stop