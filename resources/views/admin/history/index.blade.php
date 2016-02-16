@extends('layouts.admin')

@section('content')
    <table class="table">
        <thead>
        <tr>
            <th>status</th>
            <th>Name</th>
            <th>Price</th>
            <th>quantity</th>
            <th>date</th>
            <th>category</th>
            <th>client</th>
            <th>action</th>
        </tr>
        </thead>
        @forelse($histories as $history)
            <tr>
                <td><a href="{{url('history',['status', $history->id])}}" class="btn btn-{{$history->status}}" > {{$history->status}}</a></td>
                <td>{{$history->product->name}}</td>
                <td>{{$history->product->price}}</td>
                <td>{{$history->quantity}}</td>
                <td>{{$history->command_at}}</td>
                <td>{{($cat = $history->product->category)? $cat->title : 'No category'}}</td>
                <td>{{$history->user->name}}</td>
                <td>
                    <form method="POST" action="{{url('history', $history->id)}}">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input class="btn btn-closed" type="submit" value="delete" >
                    </form>
                </td>
            </tr>
        @empty
        @endforelse
        {{$histories->links()}}
    </table>
@stop
