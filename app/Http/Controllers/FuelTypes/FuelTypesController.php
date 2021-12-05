<?php

namespace App\Http\Controllers\FuelTypes;

use App\Http\Controllers\Controller;

use App\Models\FuelType;

use App\Http\Requests\FuelType\CreateFuelTypeRequest;
use App\Http\Requests\FuelType\UpdateFuelTypeRequest;

use App\Http\Resources\FuelTypeResource;

use App\Exceptions\ApiExceptions\Http404;
use App\Exceptions\ApiExceptions\Http422;
use App\Exceptions\FuelType\FuelTypeAlreadyExistsException;
use App\Exceptions\FuelType\FuelTypeNotFoundException;

class FuelTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $flags = FuelType::all();
        return FuelTypeResource::collection($flags);
    }

    /**
     * Store a newly created resource in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateFuelTypeRequest $request)
    {
        try {
            $attributes = $request->validated();

            $fuelTypeName = $attributes['name'];

            $alreadyExists = FuelType::where('name', '=', $fuelTypeName)->exists();
            if ($alreadyExists) {
                throw new FuelTypeAlreadyExistsException();
            }

            $fuelType = new FuelType();
            $fuelType->name = $fuelTypeName;
            $fuelType->save();

            return new FuelTypeResource($fuelType);

        } catch (FuelTypeAlreadyExistsException $e) {
            throw Http422::makeForField('name', 'name-already-exists');
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
            $fuelType = FuelType::find($id);
            if (!$fuelType) {
                throw new FuelTypeNotFoundException();
            }

            return new FuelTypeResource($fuelType);

        } catch (FuelTypeNotFoundException $e) {
            throw Http404::makeForField('flag', 'flag-not-found');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFuelTypeRequest $request, $id)
    {
        try {
            $fuelType = FuelType::find($id);
            if (!$fuelType) {
                throw new FuelTypeNotFoundException();
            }

            $attributes = $request->validated();

            if ($this->hasAttribute('name', $attributes)) {
                $newName = $attributes['name'];
                $existingFuelType = FuelType::where('name', '=', $newName)
                    ->where('id', '<>', $id)
                    ->exists();

                if ($existingFuelType) {
                    throw new FuelTypeAlreadyExistsException();
                }

                $fuelType->name = $attributes['name'];
            }

            $fuelType->save();

            return new FuelTypeResource($fuelType);

        } catch (FuelTypeNotFoundException $e) {
            throw Http404::makeForField('fuel-type', 'flag-not-found');
        } catch (FuelTypeAlreadyExistsException $e) {
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
