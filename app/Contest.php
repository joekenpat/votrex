<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
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
    return $this->hasMany(Contestant::class,  'contestant_contests', 'contestant_id','contest_id');
  }

  public function votes()
  {
    return $this->hasManyThrough(Vote::class ,Contestant::class);
  }

  public function is_active()
  {
    return ($this->ended_at > now());
  }
}
