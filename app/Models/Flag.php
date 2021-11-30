<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flag extends Model
{
    public $timestamps = false;
    use HasFactory;

    public function gasStations() {
        return $this->hasMany(GasStation::class);
    }
}
