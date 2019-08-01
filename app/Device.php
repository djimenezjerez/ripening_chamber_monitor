<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
	use Traits\EloquentGetTableNameTrait;

    public $timestamps = true;
	public $guarded = ['id'];
	protected $fillable = ['name', 'display_name', 'mac', 'ip', 'alive'];

	public function rooms() {
		return $this->hasMany(Room::class);
	}
}
