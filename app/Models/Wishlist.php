<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    public $table = 'wishlist';
    public $timestamps  = false;

    public function wishBy()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function wishProduct()
    {
        return $this->belongsToMany('App\Models\Product');
    }
}
