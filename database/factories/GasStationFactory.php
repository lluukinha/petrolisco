<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Flag;

class GasStationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'address' => $this->faker->name(). " - No " . rand(100, 9000),
            'flag_id' => Flag::factory()->create()->id
        ];
    }
}
