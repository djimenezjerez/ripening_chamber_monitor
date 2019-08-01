<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
  use Traits\EloquentGetTableNameTrait;

  public $guarded = ['id'];
  protected $fillable = ['name', 'display_name'];

  public function measurements() {
    return $this->hasMany(Measurement::class);
  }

  public function devices() {
    return $this->belongsToMany(Device::class);
  }
}
