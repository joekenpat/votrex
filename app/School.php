<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
  protected $fillable = [
    'name', 'type', 'state',
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
   * returns the users that belongs in a school.
   *
   * @var app
   */
  public function users()
  {
    return $this->hasMany(User::class,  'sch_id');
  }
}
