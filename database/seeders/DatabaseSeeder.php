<?php

namespace Database\Seeders;

use App\Models\Flag;
use App\Models\FuelType;
use App\Models\GasStation;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        DB::table('fuel_types')->truncate();
        DB::table('flags')->truncate();
        DB::table('gas_stations')->truncate();
        DB::table('fuel_type_gas_station')->truncate();
        DB::table('gas_station_prices')->truncate();
        DB::table('gas_station_price_detail')->truncate();

        User::factory()->create([
            'id' => 1,
            'name' => 'Lucas',
            'email' => 'lucas.prog07@gmail.com',
        ]);

        FuelType::factory()->createMany([
            [ 'id' => 1, 'name' => 'Etanol' ],
            [ 'id' => 2, 'name' => 'Gasolina' ],
        ]);

        Flag::factory()->createMany([
            [ 'id' => 1, 'name' => 'Sem Bandeira' ],
            [ 'id' => 2, 'name' => 'Ipiranga' ],
            [ 'id' => 3, 'name' => 'Texaco' ]
        ]);

        GasStation::factory()->createMany([
            [ 'id' => 1, 'flag_id' => 1 ],
            [ 'id' => 2, 'flag_id' => 2 ],
            [ 'id' => 3, 'flag_id' => 3 ]
        ]);

        DB::table('fuel_type_gas_station')->insert([
            ['fuel_type_id' => 1, 'gas_station_id' => 1],
            ['fuel_type_id' => 2, 'gas_station_id' => 1],
            ['fuel_type_id' => 1, 'gas_station_id' => 2],
            ['fuel_type_id' => 2, 'gas_station_id' => 2],
            ['fuel_type_id' => 1, 'gas_station_id' => 3],
            ['fuel_type_id' => 2, 'gas_station_id' => 3],
        ]);

        DB::table('gas_station_prices')->insert([
            'id' => 1,
            'gas_station_id' => 1,
            'user_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('gas_station_price_detail')->insert([
            [
                'gas_station_price_id' => 1,
                'fuel_type_id' => 1,
                'price' => '6.980'
            ],
            [
                'gas_station_price_id' => 1,
                'fuel_type_id' => 2,
                'price' => '49.80'
            ],
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
