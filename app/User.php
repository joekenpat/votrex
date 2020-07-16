<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use Notifiable, UuidForKey;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

  protected $fillable = [
    'first_name', 'last_name', 'middle_name','state',
    'avatar', 'media', 'email', 'password', 'age',
    'sch_id', 'sch_level', 'sch_faculty',
    'gender', 'phone', 'bio', 'last_login',
    'last_ip',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'media' => 'Array',
  ];

  /**
   * returns the school the user belongs to.
   *
   * @var app
   */
  public function school()
  {
    return $this->belongsTo(School::class,  'sch_id');
  }

  /**
   * returns the contests the user belongs to.
   *
   * @var app
   */
  public function contests()
  {
    return $this->belongsToMany(Contest::class,  'contests_user', 'contest_id', 'user_id');
  }

  public function votes()
  {
    return $this->hasMany(Vote::class, 'user_id');
  }

  public function is_profile_complete()
  {
    foreach ($this->toArray() as $name => $value) {
      if (!in_array($name, ['created_at', 'updated_at', 'deleted_at', 'last_login', 'last_ip', 'email_verified_at', 'media']) && isEmptyOrNullString($value)) {
        return false;
      }
    }
  }

}
