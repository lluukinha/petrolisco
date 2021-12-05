<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GasStation extends Model
{
    use HasFactory;

    public function fuelTypes() {
        return $this->belongsToMany(FuelType::class);
    }

    public function flag() {
        return $this->belongsTo(Flag::class);
    }

    public function prices() {
        return $this->hasMany(GasStationPrice::class);
    }
}
