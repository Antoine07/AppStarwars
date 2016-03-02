<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{

    protected $dates = ['published_at'];

    protected $fillable = [
        'slug',
        'name',
        'abstract',
        'content',
        'status',
        'category_id',
        'price',
        'published_at',
        'quantity'
    ];

    public function picture()
    {
        return $this->hasOne('App\Picture');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function score()
    {
        return $this->hasOne('App\Score');
    }

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getPublishedAtAttribute($value)
    {
        if ($value == '0000-00-00 00:00:00') return 'no date';

        return Carbon::parse($value)->format('d/m/Y h:i:s');


    }

    public function scopeOnline($query)
    {
        return $query->where('status', '=', 'opened');
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = (empty($value)) ? str_slug($this->name) : str_slug($value);
    }

    public function setCategoryIdAttribute($value)
    {
        $this->attributes['category_id'] = ($value == 0) ? null : $value;
    }

    public function setPublishedAtAttribute($value)
    {
        // Carbon\Carbon::createFromFormat('d/m/Y', '13/01/2016');
        $this->attributes['published_at'] = (empty($value)) ? '0000-00-00 00:00:00' : date('Y-m-d h:i:s');
    }

    public function setQuantityAtAttribute($value)
    {
        $this->attributes['quantity'] = ($value >= $this->quantity)? 0 : ($this->quantity-$value);
    }

    public function hasTag($id)
    {
        foreach ($this->tags as $tag) {
            if ($tag->id == $id) return true;
        }

        return false;
    }

}