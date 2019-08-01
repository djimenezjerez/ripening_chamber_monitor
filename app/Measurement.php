<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
	use Traits\EloquentGetTableNameTrait;

    public $timestamps = true;
	public $guarded = ['id'];
	protected $fillable = ['room_id', 'magnitude_id', 'value'];

	public function room() {
		return $this->belongsTo(Room::class);
	}

	public function magnitude() {
		return $this->belongsTo(Magnitude::class);
	}
}
