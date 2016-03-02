@extends('layouts.master')

@section('content')
    <h2>Step One</h2>
    @if(!empty($user))
        <p><a href="{{url('inscription/step-two')}}">suivant</a></p>
    @endif
    <form method="POST" action="{{url('inscription/step-one')}}">
        {{ csrf_field() }}
        <div class="form-text">
            <label class="label" for="email">{{trans('app.emailAddress')}}</label>
            <input class="input-text" id="email" name="email" type="email" value="{{!empty($user)? $user->email: old('email')}}" >
            @if($errors->has('email')) <span class="error">{{$errors->first('email')}}</span> @endif
        </div>
        <div class="form-text">
            <label class="label" for="name">{{trans('app.username')}}</label>
            <input class="input-text" id="name" name="name" type="name" value="{{!empty($user)? $user->name: old('name')}}" >
            @if($errors->has('name')) <span class="error">{{$errors->first('name')}}</span> @endif
        </div>
        <div class="form-submit">
            <input type="submit" value="login" >
        </div>
    </form>
@stop