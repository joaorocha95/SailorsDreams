<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;


    public function message(){return $this->belongsTo('App\Models\Message');}

    public function writenBy(){return $this->belongsTo('App\Models\User');}

    public function refersTo(){return $this->belongsTo('App\Models\Order');}
}
