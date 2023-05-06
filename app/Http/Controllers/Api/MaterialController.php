<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\MaterialFilter;
use App\Http\Resources\MaterialResource;
use App\Http\Validations\MaterialValidation;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $validated = Validator::make($request->all(), MaterialValidation::index());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        $materials = Material::filter(new MaterialFilter($request))->where('company_id', $user->company->id)->paginate($request->input('per_page', 10));

        return MaterialResource::collection($materials);
    }


    public function all(Request $request)
    {
        $validated = Validator::make($request->all(), MaterialValidation::all());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        // Perlu diseragamkan return responsenya
        $materials = Material::filter(new MaterialFilter($request))->where('company_id', $user->company->id)->get();

        return MaterialResource::collection($materials);
    }


    public function show(Request $request, $id)
    {

        $request['id'] = $id;

        $validated = Validator::make($request->all(), MaterialValidation::show());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $material = Material::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($material)
        {
            return $this->responseSuccess(new MaterialResource($material), 'Get detail');
        }

        return $this->responseError([], 'Not found');
    }


    public function store(Request $request)
    {

        $validated = Validator::make($request->all(), MaterialValidation::store());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $material = Material::Create([
            'company_id'            => $user->company->id,
            'material_category_id'  => $request->material_category_id,
            'name'                  => $request->name,
            'number'                => $request->number,
            'description'           => $request->description,
            'uom'                   => $request->uom,
            'price'                 => $request->price,
            'stock'                 => $request->stock,
        ]);

        return $this->responseSuccess($material, 'Add new account');
    }


    public function update(Request $request, $id)
    {
        $request['id'] = $id;

        $validated = Validator::make($request->all(), MaterialValidation::update());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $material = Material::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($material)
        {
            $material->material_category_id = $request->material_category_id;
            $material->name                 = $request->name;
            $material->number               = $request->number;
            $material->description          = $request->description;
            $material->uom                  = $request->uom;
            $material->price                = $request->price;
            $material->stock                = $request->stock;

            $material->save();

            return $this->responseSuccess(new MaterialResource($material), 'Update detail');
        }

        return $this->responseError([], 'Not found');
    }


    public function destroy($id)
    {

        $validated = Validator::make(['id' => $id], MaterialValidation::destroy());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $material = Material::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($material)
        {
            // income dijadikan null atau di ubah ke kategori lainnya

            $material->delete();

            return $this->responseSuccess($material, 'Delete Record', 204);
        }

        return $this->responseError([], 'Not found');
    }
}
