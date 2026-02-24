<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use HasFactory;

    protected $fillable = ['address', 'type'];

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}
