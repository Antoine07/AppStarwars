<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = ['product_id', 'user_id', 'quantity'];

    public function setCommandAtAttribute($value)
    {
        $this->attributes['command_at'] = Carbon::now();
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
