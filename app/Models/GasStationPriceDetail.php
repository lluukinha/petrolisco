<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GasStationPriceDetail extends Model
{
    public $timestamps = false;
    protected $table = 'gas_station_price_detail';
    use HasFactory;

    public function fuelType() {
        return $this->belongsTo(FuelType::class);
    }

    public function priceDetail() {
        return $this->belongsTo(GasStationPrice::class);
    }
}
