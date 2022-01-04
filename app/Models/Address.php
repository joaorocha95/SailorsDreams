<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    public $table = 'addresses';
    public $timestamps  = false;

    /**
     * Varios utilizadores podem ter a mesma morada
     */
    public function ownedBy()
    {
        return $this->belongsToMany('App\Models\User');
    }
}
