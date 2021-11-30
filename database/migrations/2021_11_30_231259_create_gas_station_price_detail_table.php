<?php

use App\Models\FuelType;
use App\Models\GasStationPrice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGasStationPriceDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gas_station_price_detail', function (Blueprint $table) {
            $table->foreignIdFor(GasStationPrice::class);
            $table->foreignIdFor(FuelType::class);
            $table->decimal('price', 10, 3);
            $table->primary(['gas_station_price_id', 'fuel_type_id'], 'station_fuel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gas_station_price_detail');
    }
}
