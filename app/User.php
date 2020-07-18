<?php

namespace App;

use Hamcrest\Text\IsEmptyString;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use Notifiable, UuidForKey;

  /**
   * Indicates if the IDs are auto-incrementing.
   *
   * @var bool
   */
  public $incrementing = false;
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

  protected $fillable = [
    'first_name', 'last_name', 'middle_name', 'state',
    'avatar', 'media', 'email', 'password', 'age',
    'sch_id', 'sch_level', 'sch_faculty', 'role',
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
    return $this->belongsToMany(Contest::class,  'contest_user', 'user_id', 'contest_id');
  }

  public function votes()
  {
    return $this->hasMany(Vote::class, 'user_id');
  }

  public function is_profile_complete()
  {
    if ($this->first_name == null || "") {
      return false;
    } elseif ($this->last_name == null || "") {
      return false;
    } elseif ($this->gender == null || "") {
      return false;
    } elseif ($this->age == null || "") {
      return false;
    } elseif ($this->avatar == null || "") {
      return false;
    } elseif ($this->bio == null || "") {
      return false;
    } elseif ($this->phone == null || "") {
      return false;
    } elseif ($this->state == null || "") {
      return false;
    } elseif ($this->sch_id == null || "") {
      return false;
    } elseif ($this->sch_level == null || "") {
      return false;
    } elseif ($this->sch_faculty  == null || "") {
      return false;
    }
    return true;
  }

  public function get_full_name()
  {
    return sprintf("%s %s %s", $this->last_name, $this->first_name, $this->middle_name);
  }

  public function is_admin()
  {
    return ($this->role == 'admin');
  }
}
