<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    public function writer(){return $this->belongsTo('App\Models\Users');}

    public function aboutUser(){return $this->belongsTo('App\Models\Users');}

    public function aboutOrder(){return $this->belongsTo('App\Models\Order');}
}


