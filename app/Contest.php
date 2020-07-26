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
    'title', 'minimum_vote','vote_fee',
    'image',
    'started_at','ended_at',
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
    return $this->belongsToMany(User::class,  'contest_user', 'contest_id','user_id')->withPivot('status');
  }

  /**
   * returns the contests the user belongs to.
   *
   * @var app
   */

  public function votes()
  {
    return $this->hasMany(Vote::class, 'contest_id');
  }

  public function is_active()
  {
    return ($this->ended_at > now());
  }
}
