<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Magnitude extends Model
{
	use Traits\EloquentGetTableNameTrait;

    public $timestamps = true;
	public $guarded = ['id'];
	protected $fillable = ['name', 'display_name', 'measure'];

	public function measurements() {
		return $this->hasMany(Measurement::class);
	}

	public function rooms() {
		return $this->belongsToMany(Room::class)->withPivot('min_limit', 'max_limit', 'interval');
	}
}
