<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class UtilityEntry extends Model
{
    use HasFactory;

    protected $fillable = ['unit_id', 'utility_category_id', 'date', 'value', 'cost', 'proof_image_path'];

    protected $casts = [
        'date' => 'date',
        'value' => 'decimal:4',
        'cost' => 'decimal:2',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function category()
    {
        return $this->belongsTo(UtilityCategory::class, 'utility_category_id');
    }
}
