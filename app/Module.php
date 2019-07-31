<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    public $timestamps = true;
    public $guarded = ['id'];
    protected $fillable = ['name'];

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
