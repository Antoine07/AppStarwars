@extends('layouts.master')

@section('content')
    <h2>Step three end</h2>
    @if(!empty($user))
        <p><a href="{{url('inscription/step-two')}}">{{trans('app.prec')}}</a></p>
    @endif
    <form method="POST" action="{{url('inscription/step-end')}}">
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
        <div class="form-text">
            <label class="label" for="address">{{trans('app.nameAddress')}}</label>
            <textarea name="address" id="address" cols="30" rows="10">{{!empty($customer)? $customer->address: old('address')}}</textarea>
            @if($errors->has('address')) <span class="error">{{$errors->first('address')}}</span> @endif
        </div>
        <div class="form-text">
            <h2>{{trans('app.regeneratePassword')}}</h2>
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