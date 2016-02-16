@if(Cookie::has('cart'))
    <p><a href="{{url('cart')}}">{{trans('app.numberCart')}}: {{count(Cookie::get('cart'))}} </a></p>
@else
    <p>{{trans('app.yourCartIsEmpty')}}</p>
@endif