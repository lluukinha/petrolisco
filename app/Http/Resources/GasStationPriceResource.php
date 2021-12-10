<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GasStationPriceResource extends JsonResource
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
            'type' => 'gas-station-price',
            'id' => (string) $this->id,
            'date' => $this->created_at,
            'details' => GasStationPriceDetailResource::collection($this->details)
        ];
    }
}
