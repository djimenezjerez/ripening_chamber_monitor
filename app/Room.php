<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
  use Traits\EloquentGetTableNameTrait;

  public $guarded = ['id'];
  protected $fillable = ['name', 'display_name', 'device_id'];

  public function measurements() {
    return $this->hasMany(Measurement::class);
  }

  public function device() {
    return $this->belongsTo(Device::class);
  }

  public function magnitudes() {
		return $this->belongsToMany(Magnitude::class)->withPivot('min_limit', 'max_limit', 'interval');
	}
}
