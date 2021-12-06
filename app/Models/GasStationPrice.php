<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GasStationPrice extends Model
{
    protected $table = 'gas_station_prices';
    use HasFactory;

    public function details() {
        return $this->hasMany(GasStationPriceDetail::class);
    }

    public function gasStation() {
        return $this->belongsTo(GasStation::class);
    }
}
