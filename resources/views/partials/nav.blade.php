<nav id="navigation" role="navigation">
    <ul class="navigation">
        <li><a href="{{url('/')}}">{{trans('app.home')}}</a></li>
        @forelse($categories as $id => $title)
            <li><a href="{{url('cat',[$id, str_slug($title)] )}}">{{$title}}</a></li>
        @empty
            <li>{{trans('app.noCategory')}}</li>
        @endforelse
        <li><a href="{{url('contact')}}">{{trans('app.contact')}}</a></li>

        @if(Auth::check() && Auth::user()->role=='administrator')
            <li><a href="{{url('dashboard')}}">{{trans('app.dashboard')}}</a></li>
            <li><a href="{{url('logout')}}">{{trans('app.logout')}}</a></li>
            <li><a href="{{ route('customer.show', [Auth::user()->id]) }}">{{Auth::user()->name}}</a></li>
        @else
            @if(Auth::user() && Auth::user()->role == 'visitor')
                <li><a href="{{url('logout')}}">{{trans('app.logout')}}</a></li>
                <li><a href="{{ route('customer.show', [Auth::user()->id]) }}">{{Auth::user()->name}}</a></li>

            @else
                <li><a href="{{url('login')}}">{{trans('app.login')}}</a></li>
                <li><a href="{{url('inscription/step-one')}}">{{trans('app.inscription')}}</a></li>
            @endif
        @endif
    </ul>
</nav>