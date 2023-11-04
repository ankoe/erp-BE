<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\UnitFilter;
use App\Http\Resources\UnitResource;
use App\Http\Validations\UnitValidation;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $validated = Validator::make($request->all(), UnitValidation::index());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        $units = Unit::filter(new UnitFilter($request))->where('company_id', $user->company->id)->paginate($request->input('per_page', 10));

        return UnitResource::collection($units);
    }


    public function all(Request $request)
    {
        $validated = Validator::make($request->all(), UnitValidation::all());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        // Perlu diseragamkan return responsenya
        $units = Unit::filter(new UnitFilter($request))->where('company_id', $user->company->id)->get();

        return UnitResource::collection($units);
    }


    public function show(Request $request, $id)
    {

        $request['id'] = $id;

        $validated = Validator::make($request->all(), UnitValidation::show());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $unit = Unit::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($unit)
        {
            return $this->responseSuccess(new UnitResource($unit), 'Get unit detail');
        }

        return $this->responseError([], 'Not found');
    }


    public function store(Request $request)
    {

        $validated = Validator::make($request->all(), UnitValidation::store());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $unit = Unit::create([
            'company_id'            => $user->company->id,
            'name'                  => $request->name,
        ]);

        return $this->responseSuccess($unit, 'Add new unit');
    }


    public function update(Request $request, $id)
    {
        $request['id'] = $id;

        $validated = Validator::make($request->all(), UnitValidation::update());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $unit = Unit::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($unit)
        {
            $unit->name                   = $request->name;

            $unit->save();

            return $this->responseSuccess(new UnitResource($unit), 'Update unit');
        }

        return $this->responseError([], 'Not found');
    }


    public function destroy($id)
    {

        $validated = Validator::make(['id' => $id], UnitValidation::destroy());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $unit = Unit::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($unit)
        {
            // material dijadikan null atau di ubah ke kategori lainnya

            $unit->delete();

            return $this->responseSuccess($unit, 'Delete unit', 204);
        }

        return $this->responseError([], 'Not found');
    }
}
