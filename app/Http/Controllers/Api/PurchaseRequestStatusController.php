<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\PurchaseRequestItemFilter;
use App\Http\Resources\PurchaseRequestItemResource;
use App\Http\Validations\PurchaseRequestItemValidation;
use App\Models\PurchaseRequestStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PurchaseRequestStatusController extends Controller
{
    public function all(Request $request)
    {
        // Perlu diseragamkan return responsenya
        $purchaseRequestStatus = PurchaseRequestStatus::get();

        return $purchaseRequestStatus;
    }
}
