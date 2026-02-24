<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnitUtilityConfig extends Model
{
    use HasFactory;

    protected $fillable = ['unit_id', 'utility_category_id', 'year', 'price_per_unit', 'calculation_method'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function category()
    {
        return $this->belongsTo(UtilityCategory::class, 'utility_category_id');
    }

    public function entries()
    {
        return $this->hasMany(UtilityEntry::class, 'unit_id', 'unit_id')
            ->where('utility_category_id', $this->utility_category_id);
    }
}
