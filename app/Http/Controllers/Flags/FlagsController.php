<?php

namespace App\Http\Controllers\Flags;

use App\Exceptions\ApiExceptions\Http404;
use App\Exceptions\ApiExceptions\Http422;
use App\Exceptions\Flag\FlagAlreadyExistsException;
use App\Exceptions\Flag\FlagNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Flag\CreateFlagRequest;
use App\Http\Requests\Flag\UpdateFlagRequest;
use App\Http\Resources\FlagResource;
use App\Models\Flag;

class FlagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $flags = Flag::all();
        return FlagResource::collection($flags);
    }

    /**
     * Store a newly created resource in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateFlagRequest $request)
    {
        try {
            $attributes = $request->validated();

            $flagName = $attributes['name'];

            $flagAlreadyExists = Flag::where('name', '=', $flagName)->exists();
            if ($flagAlreadyExists) {
                throw new FlagAlreadyExistsException();
            }

            $flag = new Flag();
            $flag->name = $flagName;
            $flag->img = null;
            $flag->save();

            return new FlagResource($flag);

        } catch (FlagAlreadyExistsException $e) {
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
            $flag = Flag::find($id);
            if (!$flag) {
                throw new FlagNotFoundException();
            }

            return new FlagResource($flag);

        } catch (FlagNotFoundException $e) {
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
    public function update(UpdateFlagRequest $request, $id)
    {
        try {
            $flag = Flag::find($id);
            if (!$flag) {
                throw new FlagNotFoundException();
            }

            $attributes = $request->validated();

            if ($this->hasAttribute('name', $attributes)) {
                $newName = $attributes['name'];
                $existingFlag = Flag::where('name', '=', $newName)
                    ->where('id', '<>', $id)
                    ->exists();

                if ($existingFlag) {
                    throw new FlagAlreadyExistsException();
                }

                $flag->name = $attributes['name'];
            }

            if ($this->hasAttribute('img', $attributes)) {
                $flag->img = $attributes['img'];
            }

            $flag->save();

            return new FlagResource($flag);

        } catch (FlagNotFoundException $e) {
            throw Http404::makeForField('flag', 'flag-not-found');
        } catch (FlagAlreadyExistsException $e) {
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
