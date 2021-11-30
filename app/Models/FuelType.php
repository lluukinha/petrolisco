<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelType extends Model
{
    public $timestamps = false;
    use HasFactory;

    public function gasStations() {
        return $this->belongsToMany(GasStation::class);
    }
}
