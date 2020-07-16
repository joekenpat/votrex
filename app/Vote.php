<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
  protected $fillable = [
    'first_name', 'last_name','email',
    'paystack_ref','status','quantity',
    'user_id','contest_id'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [

  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
  ];

  /**
   * returns the contest the vote belongs to.
   *
   * @var app
   */
  public function contest()
  {
    return $this->belongsTo(Contest::class,  'contest_id');
  }

  /**
   * returns the user the vote belongs to.
   *
   * @var app
   */
  public function contestant()
  {
    return $this->belongsTo(Contestant::class,  'user_id');
  }

}
