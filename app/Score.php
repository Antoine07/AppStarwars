<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = ['number_command', 'product_id', 'score'];


    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
