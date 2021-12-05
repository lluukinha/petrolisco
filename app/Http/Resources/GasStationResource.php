<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GasStationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => 'gas-station',
            'id' => (string) $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'flag_id' => $this->flag->id,
            'flag' => $this->flag->name
        ];
    }
}
