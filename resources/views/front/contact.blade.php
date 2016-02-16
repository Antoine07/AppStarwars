@extends('layouts.master')

@section('content')
 @if(Session::has('message'))
     @include('front.partials.flash')
 @else
 <form method="POST" action="{{url('send')}}">
    {{ csrf_field() }}
    <div class="form-text">
        <label class="label" for="email">{{trans('app.emailAddress')}}</label>
        <input class="input-text" id="email" name="email" type="text" value="{{old('email')}}" >
        @if($errors->has('email')) <span class="error">{{$errors->first('email')}}</span> @endif
    </div>
     <div class="content">
         <label>{{trans('app.messageContact')}}</label>
         <textarea row="50" cols="50" name="content">{{old('content')}}</textarea>
         <span><small>{{trans('app.maxCharContent')}}</small></span>
         @if($errors->has('content')) <span class="error">{{$errors->first('content')}}</span> @endif
     </div>
     </div>
     <div class="form-submit">
        <input type="submit" value="ok" >
     </div>
 </form>
@endif
@stop