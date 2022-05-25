<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $guarded = [];
    public $timestamps = true;

    public function persons()
    {
        return $this->hasMany(Person::class);
    }
}
