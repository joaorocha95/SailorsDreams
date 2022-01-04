<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $table = 'category';
    public $timestamps  = false;

    /**
     * Uma categoria pode ter vários produtos
     */
    public function categories()
    {
        return $this->belongsToMany('App\Models\Product');
    }
}
