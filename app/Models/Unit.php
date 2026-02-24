<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['property_id', 'user_id', 'unit_number', 'floor', 'size', 'status', 'price'];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function utilityConfigs()
    {
        return $this->hasMany(UnitUtilityConfig::class);
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

    public function entries()
    {
        return $this->hasMany(UtilityEntry::class);
    }
}
