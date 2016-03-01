@forelse($histories as $history)
    <div class="clearfix">
        @if($history->product)
            <h2>{{$history->product->name}}</h2>
        @endif
        @can('update-customer', $history)
        <a href="{{url('customer',[$customer->id, 'edit'])}}">{{trans('app.edit')}}</a>
        @endcan

    </div>
@empty
    <p>No history wet</p>
@endforelse