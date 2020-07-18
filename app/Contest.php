<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
  use UuidForKey;
  /**
    * Indicates if the IDs are auto-incrementing.
    *
    * @var bool
    */
    public $incrementing = false;

  protected $fillable = [
    'title', 'reg_fee','vote_fee',
    'image',
    'status','started_at','ended_at',
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
    'started_at'=>'datetime',
    'ended_at'=>'datetime',
  ];

  /**
   * returns the contests the user belongs to.
   *
   * @var app
   */
  public function contestants()
  {
    return $this->belongsToMany(User::class,  'contest_user', 'contest_id','user_id');
  }

  // public function votes()
  // {
  //   return $this->hasManyThrough(Vote::class ,User::class);
  // }

  public function is_active()
  {
    return ($this->ended_at > now());
  }
}
