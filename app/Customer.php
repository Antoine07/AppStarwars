<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

    protected $fillable=['address', 'number_card', 'user_id' ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
