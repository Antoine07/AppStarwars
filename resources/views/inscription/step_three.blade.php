@extends('layouts.master')

@section('content')
    <h2>Step One</h2>
    @if(!empty($user))
        <p><a href="{{url('inscription/step-two')}}">suivant</a></p>
    @endif
    <form method="POST" action="{{url('inscription/step-one')}}">
        {{ csrf_field() }}
        <div class="form-text">
            <label class="label" for="password">{{trans('app.password')}}</label>
            <input class="input-text" id="password" name="password" type="password"  >
            @if($errors->has('password')) <span class="error">{{$errors->first('password')}}</span> @endif
        </div><div class="form-text">
            <label class="label" for="password_confirmation">{{trans('app.password_confirmation')}}</label>
            <input class="input-text" id="password_confirmation" name="password_confirmation" type="password"  >
            @if($errors->has('password_confirmation')) <span class="error">{{$errors->first('password_confirmation')}}</span> @endif
        </div>
        <div class="form-submit">
            <input type="submit" value="login" >
        </div>
    </form>
@stop