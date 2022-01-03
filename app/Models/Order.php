<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function madeBy(){return $this->belongsTo('App\Models\User');}

    public function messageAssociated(){return $this->hasMany('App\Models\Message');}

    public function productAssociated(){return $this->belongsTo('App\Models\Product');}
   
    public function reviewsIn(){return $this->hasMany('App\Models\Review');}

}
