<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    public $table = 'message';
    public $timestamps  = false;


    public function ticketAssociated()
    {
        return $this->belongsTo('App\Models\Ticket');
    }

    public function writenBy()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function refersTo()
    {
        return $this->belongsTo('App\Models\Order');
    }
}
