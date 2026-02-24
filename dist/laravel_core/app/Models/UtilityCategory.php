<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class UtilityCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'input_type'];

    public function unitConfigs()
    {
        return $this->hasMany(UnitUtilityConfig::class);
    }
}
