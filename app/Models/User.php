<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Http\Controllers\UsersController;

class User extends Authenticatable
{

  use Notifiable;
  // Don't add create and update timestamps in database.
  public $table = 'users';
  public $timestamps  = false;

  public $fillable = ['username', 'email', 'password', 'img', 'birthdate', 'phone', 'banned'];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  public function writesReview()
  {
    return $this->hasMany('App\Models\Review');
  }

  public function receiveReview()
  {
    return $this->hasMany('App\Models\Review');
  }

  public function livesIn()
  {
    return $this->belongsToMany('App\Models\Address');
  }

  public function tickets()
  {
    return $this->hasMany('App\Models\Ticket');
  }

  public function writesMessage()
  {
    return $this->hasMany('App\Models\Message');
  }

  public function ownsProduct()
  {
    return $this->hasMany('App\Models\Product');
  }

  public function makesOrder()
  {
    return $this->hasMany('App\Models\Order');
  }

  public function wishes()
  {
    return $this->hasOne('App\Models\Wishlist');
  }

  /*
  //////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////
  ///////////////////////TEMPLATES//////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////

  one to many
  public function cars(){return $this->hasMany('App\Models\Car');}

  public function owner(){return $this->belongsTo('App\Models\Person');}
  
  one to one
  public function car(){return $this->hasOne('App\Models\Car');}

  public function owner(){return $this->belongsTo('App\Models\Person');}


  many to many 
  public function owners(){return $this->belongsToMany('App\Models\Person');}

  public function cars(){return $this->belongsToMany('App\Models\Car');}


  many to many with attributes 
  public function owners(){return $this->belongsToMany('App\Models\Person')->withPivot('purchase_date');}

  public function cars(){return $this->belongsToMany('App\Models\Car')->withPivot('purchase_date');}

    
  $person = App\Models\Person::find(1);

  foreach ($person->cars as $car) {
    echo $car->pivot->purchase_date;
  }*/
}
