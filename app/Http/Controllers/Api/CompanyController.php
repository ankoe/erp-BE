<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\CompanyFilter;
use App\Http\Resources\CompanyResource;
use App\Http\Validations\CompanyValidation;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    // public function index(Request $request)
    // {
    //     $validated = Validator::make($request->all(), CompanyValidation::index());

    //     if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

    //     $user = auth()->user();

    //     $companies = Company::filter(new CompanyFilter($request))->where('user_id', $user->id)->paginate($request->input('per_page', 10));

    //     return CompanyResource::collection($companies);
    // }


    // public function all(Request $request)
    // {
    //     $validated = Validator::make($request->all(), CompanyValidation::all());

    //     if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

    //     $user = auth()->user();

    //     // Perlu diseragamkan return responsenya
    //     $companies = Company::filter(new CompanyFilter($request))->where('user_id', $user->id)->get();

    //     return CompanyResource::collection($companies);
    // }


    // public function show(Request $request, $id)
    // {

    //     $request['id'] = $id;

    //     $validated = Validator::make($request->all(), CompanyValidation::show());

    //     if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

    //     $user = auth()->user();

    //     $company = Company::where(['id' => $id, 'user_id' => $user->id])->first();

    //     if ($company)
    //     {
    //         return $this->responseSuccess(new CompanyResource($company), 'Get detail');
    //     }

    //     return $this->responseError([], 'Not found');
    // }


    // public function store(Request $request)
    // {

    //     $validated = Validator::make($request->all(), CompanyValidation::store());

    //     if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

    //     $user = auth()->user();

    //     $company = Company::Create([
    //         'user_id'         => $user->id,
    //         'label'           => $request->label,
    //     ]);

    //     return $this->responseSuccess($company, 'Add new account');
    // }


    // public function update(Request $request, $id)
    // {
    //     $request['id'] = $id;

    //     $validated = Validator::make($request->all(), CompanyValidation::update());

    //     if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

    //     $user = auth()->user();

    //     $company = Company::where(['id' => $id, 'user_id' => $user->id])->first();

    //     if ($company)
    //     {
    //         $company->label                 = $request->label;

    //         $company->save();

    //         return $this->responseSuccess(new CompanyResource($company), 'Update detail');
    //     }

    //     return $this->responseError([], 'Not found');
    // }


    // public function destroy($id)
    // {

    //     $validated = Validator::make(['id' => $id], CompanyValidation::destroy());

    //     if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

    //     $user = auth()->user();

    //     $company = Company::where(['id' => $id, 'user_id' => $user->id])->first();

    //     if ($company)
    //     {
    //         // income dijadikan null atau di ubah ke kategori lainnya

    //         $company->delete();

    //         return $this->responseSuccess($company, 'Delete Record', 204);
    //     }

    //     return $this->responseError([], 'Not found');
    // }
}
