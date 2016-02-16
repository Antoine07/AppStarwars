@if(Session::has('message'))
<div class="alert {{Session::get('alert')}}">
    <p>{{Session::get('message')}}</p>
</div>
@endif