<?php

namespace App\Http\Controllers\GasStationPrices;

use App\Http\Controllers\Controller;

use App\Http\Requests\GasStationPrice\CreateGasStationPriceRequest;

use App\Http\Resources\GasStationPriceResource;

use App\Models\FuelType;
use App\Models\GasStation;
use App\Models\GasStationPrice;
use App\Models\GasStationPriceDetail;

use App\Exceptions\ApiExceptions\Http404;
use App\Exceptions\FuelType\FuelTypeNotFoundException;
use App\Exceptions\GasStation\GasStationNotFoundException;

class GasStationPricesController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CreateGasStationPriceRequest $request, $id)
    {
        try {
            $gasStation = GasStation::find($id);
            if (!$gasStation) {
                throw new GasStationNotFoundException();
            }

            $attributes = $request->validated();
            $typeIds = array_map(function($item) {
                return $item['fuel_type_id'];
            }, $attributes['prices']);
            $qtdTypes = count($typeIds);
            $typeModels = FuelType::whereIn('id', $typeIds)->count();

            if ($qtdTypes !== $typeModels) {
                throw new FuelTypeNotFoundException();
            }

            $price = new GasStationPrice();
            $price->gas_station_id = $gasStation->id;
            $price->user_id = 1;
            $price->save();

            foreach ($attributes['prices'] as $detail) {
                $priceDetail = new GasStationPriceDetail();
                $priceDetail->gas_station_price_id = $price->id;
                $priceDetail->fuel_type_id = $detail['fuel_type_id'];
                $priceDetail->price = $detail['price'];
                $priceDetail->save();
            }

            return new GasStationPriceResource($price);

        } catch (GasStationNotFoundException $e) {
            throw Http404::makeForField('gas-station', 'gas-station-not-found');
        } catch (FuelTypeNotFoundException $e) {
            throw Http404::makeForField('fuel-type', 'one-or-more-not-found');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $gasStationPrice = GasStationPrice::where('gas_station_id', '=', $id)->get();
        return GasStationPriceResource::collection($gasStationPrice);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
