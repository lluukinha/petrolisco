<?php

namespace App\Http\Controllers\GasStations;

use App\Http\Controllers\Controller;

use App\Models\GasStation;

use App\Http\Requests\GasStation\CreateGasStationRequest;
use App\Http\Requests\GasStation\UpdateGasStationRequest;

use App\Http\Resources\GasStationResource;

use App\Exceptions\ApiExceptions\Http404;
use App\Exceptions\ApiExceptions\Http422;
use App\Exceptions\Flag\FlagNotFoundException;
use App\Exceptions\GasStation\GasStationAlreadyExistsException;
use App\Exceptions\GasStation\GasStationNotFoundException;
use App\Models\Flag;

class GasStationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gasStations = GasStation::all();
        return GasStationResource::collection($gasStations);
    }

    /**
     * Store a newly created resource in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateGasStationRequest $request)
    {
        try {
            $attributes = $request->validated();

            $name = $attributes['name'];
            $address = $attributes['address'];

            $alreadyExists = GasStation::where('name', '=', $name)->exists();
            if ($alreadyExists) {
                throw new GasStationAlreadyExistsException();
            }

            $flag = Flag::find($attributes['flag_id']);
            if (!$flag) {
                throw new FlagNotFoundException();
            }

            $gasStation = new GasStation();
            $gasStation->name = $name;
            $gasStation->address = $address;
            $gasStation->flag_id = $flag->id;
            $gasStation->save();

            return new GasStationResource($gasStation);

        } catch (GasStationAlreadyExistsException $e) {
            throw Http422::makeForField('name', 'name-already-exists');
        } catch (FlagNotFoundException $e) {
            throw Http404::makeForField('flag', 'flag-not-found');
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
        try {
            $gasStation = GasStation::find($id);
            if (!$gasStation) {
                throw new GasStationNotFoundException();
            }

            return new GasStationResource($gasStation);

        } catch (GasStationNotFoundException $e) {
            throw Http404::makeForField('gas-station', 'gas-station-not-found');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGasStationRequest $request, $id)
    {
        try {
            $gasStation = GasStation::find($id);
            if (!$gasStation) {
                throw new GasStationNotFoundException();
            }

            $attributes = $request->validated();

            if ($this->hasAttribute('name', $attributes)) {
                $newName = $attributes['name'];
                $existing = GasStation::where('name', '=', $newName)
                    ->where('id', '<>', $id)
                    ->exists();

                if ($existing) {
                    throw new GasStationAlreadyExistsException();
                }

                $gasStation->name = $attributes['name'];
            }

            if ($this->hasAttribute('address', $attributes)) {
                $gasStation->address = $attributes['address'];
            }

            $flag = null;
            if ($this->hasAttribute('flag_id', $attributes)) {
                $flag = Flag::find($attributes['flag_id']);

                if (!$flag) {
                    throw new FlagNotFoundException();
                }

                $gasStation->flag()->associate($flag);
            }

            $gasStation->save();

            return new GasStationResource($gasStation);

        } catch (GasStationNotFoundException $e) {
            throw Http404::makeForField('gas-station', 'gas-station-not-found');
        } catch (FlagNotFoundException $e) {
            throw Http404::makeForField('flag', 'flag-not-found');
        } catch (GasStationAlreadyExistsException $e) {
            throw Http422::makeForField('name', 'name-already-exists');
        }
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

    private function hasAttribute(string $key, array $attributes) {
        return array_key_exists($key, $attributes) && $attributes[$key] != null;
    }
}
