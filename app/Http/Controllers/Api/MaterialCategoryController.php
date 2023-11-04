<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\MaterialCategoryFilter;
use App\Http\Resources\MaterialCategoryResource;
use App\Http\Validations\MaterialCategoryValidation;
use App\Models\MaterialCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaterialCategoryController extends Controller
{
    public function index(Request $request)
    {
        $validated = Validator::make($request->all(), MaterialCategoryValidation::index());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        $materialCategories = MaterialCategory::filter(new MaterialCategoryFilter($request))->where('company_id', $user->company->id)->paginate($request->input('per_page', 10));

        return MaterialCategoryResource::collection($materialCategories);
    }


    public function all(Request $request)
    {
        $validated = Validator::make($request->all(), MaterialCategoryValidation::all());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        // Perlu diseragamkan return responsenya
        $materialCategories = MaterialCategory::filter(new MaterialCategoryFilter($request))->where('company_id', $user->company->id)->get();

        return MaterialCategoryResource::collection($materialCategories);
    }


    public function show(Request $request, $id)
    {

        $request['id'] = $id;

        $validated = Validator::make($request->all(), MaterialCategoryValidation::show());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $materialCategory = MaterialCategory::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($materialCategory)
        {
            return $this->responseSuccess(new MaterialCategoryResource($materialCategory), 'Get material location detail');
        }

        return $this->responseError([], 'Not found');
    }


    public function store(Request $request)
    {

        $validated = Validator::make($request->all(), MaterialCategoryValidation::store());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $materialCategory = MaterialCategory::Create([
            'company_id'     => $user->company->id,
            'name'           => $request->name,
            'taxonomy'       => $request->taxonomy,
        ]);

        return $this->responseSuccess($materialCategory, 'Add new material category');
    }


    public function update(Request $request, $id)
    {
        $request['id'] = $id;

        $validated = Validator::make($request->all(), MaterialCategoryValidation::update());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $materialCategory = MaterialCategory::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($materialCategory)
        {
            $materialCategory->name                 = $request->name;
            $materialCategory->taxonomy             = $request->taxonomy;

            $materialCategory->save();

            return $this->responseSuccess(new MaterialCategoryResource($materialCategory), 'Update material category');
        }

        return $this->responseError([], 'Not found');
    }


    public function destroy($id)
    {

        $validated = Validator::make(['id' => $id], MaterialCategoryValidation::destroy());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $materialCategory = MaterialCategory::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($materialCategory)
        {
            // income dijadikan null atau di ubah ke kategori lainnya

            $materialCategory->delete();

            return $this->responseSuccess($materialCategory, 'Delete material category', 204);
        }

        return $this->responseError([], 'Not found');
    }
}
