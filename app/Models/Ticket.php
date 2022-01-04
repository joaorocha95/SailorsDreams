<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public $table = 'ticket';
    public $timestamps  = false;

    public function createdBy()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function ticket()
    {
        return $this->hasMany('App\Models\Message');
    }
}
