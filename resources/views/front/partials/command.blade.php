<form method="POST" action="{{url('command')}}">
    {{csrf_field()}}
    <input type="hidden" name="id" value="{{$product->id}}"/>
    <input type="hidden" name="price" value="{{$product->price}}"/>
    <div class="div-select">
        <label for="quantity">{{trans('app.choiceQuantity')}}</label>
        <select name="quantity" class="select">
            @foreach($quantities as $quantity)
                <option value="{{$quantity}}">{{$quantity}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-submit">
        <input type="submit" value="{{trans('app.command')}}">
    </div>
</form>