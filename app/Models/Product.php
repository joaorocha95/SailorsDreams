<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model{
    use HasFactory;

    public $table = 'product';
    public $timestamps  = false;

    public function ownedBy(){return $this->belongsTo('App\Models\User');}

    public function inCategory(){return $this->belongsToMany('App\Models\Category');}

    public function wishedIn(){return $this->belongsToMany('App\Models\Wishlist');}

    public function inOrder(){return $this->hasMany('App\Models\Order');}
}
