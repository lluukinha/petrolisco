<?php

use App\Models\FuelType;
use App\Models\GasStation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuelTypeGasStationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel_type_gas_station', function (Blueprint $table) {
            $table->foreignIdFor(FuelType::class);
            $table->foreignIdFor(GasStation::class);
            $table->primary(['gas_station_id', 'fuel_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gas_stations_fuel_types');
    }
}
