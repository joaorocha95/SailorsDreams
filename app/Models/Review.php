<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    public $table = 'review';
    public $timestamps  = false;

    public function writer()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function aboutUser()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function aboutOrder()
    {
        return $this->belongsTo('App\Models\Order');
    }
}
