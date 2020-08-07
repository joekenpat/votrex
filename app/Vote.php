<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
  use UuidForKey;
  /**
   * Indicates if the IDs are auto-incrementing.
   *
   * @var bool
   */
  public $incrementing = false;


  protected $fillable = [
    'first_name', 'last_name','email',
    'transaction_ref','status','quantity',
    'user_id','contest_id','gateway'
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
    return $this->belongsTo(User::class,  'user_id');
  }

}
