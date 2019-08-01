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
}
