<?php

use App\Models\FuelType;
use App\Models\GasStation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGasStationFuelTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gas_station_fuel_types', function (Blueprint $table) {
            $table->foreignIdFor(GasStation::class);
            $table->foreignIdFor(FuelType::class);
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
