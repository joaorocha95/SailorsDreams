<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryNames extends Model
{
    use HasFactory;

    public $table = 'categorynames';
    public $timestamps  = false;

    /**
     * Uma categoria pode ter vários produtos
     */
    public function categorynames()
    {
        return $this->belongsToMany('App\Models\Category');
    }
}
