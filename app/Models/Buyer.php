<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buyer extends Model
{
    use HasFactory;

    /** @var array<int, string> */
    protected $fillable = [
        'buyer_name',
    ];

    public function styles(): HasMany
    {
        return $this->hasMany(Style::class);
    }

    public function productionBundles(): HasMany
    {
        return $this->hasMany(ProductionBundle::class);
    }
}
