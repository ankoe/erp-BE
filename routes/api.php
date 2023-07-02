<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\ConfigApprovalController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\MaterialCategoryController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\PurchaseRequestController;
use App\Http\Controllers\Api\PurchaseRequestItemController;
use App\Http\Controllers\Api\PurchaseRequestStatusController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\Office\PurchaseRequestController as OfficePurchaseRequestController;
use App\Http\Controllers\Api\Procurement\PurchaseRequestController as ProcurementPurchaseRequestController;
use App\Http\Controllers\Api\Procurement\RequestForQuotationController as ProcurementRequestForQuotationController;
use App\Http\Controllers\Api\Procurement\PurchaseOrderController as ProcurementPurchaseOrderController;
use App\Http\Controllers\Api\Supplier\RequestForQuotationController as SupplierRequestForQuotationController;
use App\Http\Controllers\Api\Supplier\PurchaseOrderController as SupplierPurchaseOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::group(function () {

    Route::prefix('auth')
    ->controller(AuthController::class)
    ->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
        Route::get('activation/submit', 'activationSubmit');
        Route::get('activation/resend', 'activationResend');
        Route::get('password/forgot', 'passwordForgot');
        Route::post('password/reset', 'passwordReset');
        Route::get('refresh', 'refresh');
        Route::get('logout', 'logout');
    });

    Route::prefix('profile')
    ->controller(ProfileController::class)
    ->middleware('auth:api')
    ->group(function () {

        Route::get('/', 'show');
        Route::put('/', 'update');
        Route::put('password', 'passwordUpdate');
        Route::put('image', 'imageUpdate');
        Route::delete('image', 'imageRemove');
    });

    Route::prefix('branch')
    ->controller(BranchController::class)
    ->middleware('auth:api')
    ->group(function () {
        Route::get('all', 'all');
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
    });

    Route::prefix('company')
    ->controller(CompanyController::class)
    ->middleware('auth:api')
    ->group(function () {
        Route::get('all', 'all');
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
    });

    Route::prefix('config-approval')
    ->controller(ConfigApprovalController::class)
    ->middleware('auth:api')
    ->group(function () {
        Route::put('sort', 'sort');
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::delete('{id}', 'destroy');
    });

    Route::prefix('material')
    ->controller(MaterialController::class)
    ->middleware('auth:api')
    ->group(function () {
        Route::get('all', 'all');
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
    });

    Route::prefix('material-category')
    ->controller(MaterialCategoryController::class)
    ->middleware('auth:api')
    ->group(function () {
        Route::get('all', 'all');
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
    });

    Route::prefix('notification')
    ->controller(NotificationController::class)
    ->middleware('auth:api')
    ->group(function () {
        Route::get('all', 'all');
        Route::get('/', 'index');
        Route::get('count', 'count');
        Route::get('read', 'readAll');
        Route::get('read/{id}', 'readSingle');
    });

    Route::prefix('purchase-request')
    ->controller(PurchaseRequestController::class)
    ->middleware('auth:api')
    ->group(function () {
        Route::get('all', 'all');
        Route::get('get-pr-code', 'getPRCode');
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::delete('{id}', 'destroy');
        Route::get('{id}/apply', 'apply');

    });

    Route::prefix('purchase-request-item')
    ->controller(PurchaseRequestItemController::class)
    ->middleware('auth:api')
    ->group(function () {
        Route::get('all', 'all');
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
    });

    Route::prefix('purchase-request-status')
    ->controller(PurchaseRequestStatusController::class)
    ->middleware('auth:api')
    ->group(function () {
        Route::get('all', 'all');
    });

    Route::prefix('role')
    ->controller(RoleController::class)
    ->middleware('auth:api')
    ->group(function () {
        Route::get('all', 'all');
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
    });

    Route::prefix('user')
    ->controller(UserController::class)
    ->middleware('auth:api')
    ->group(function () {
        Route::get('all', 'all');
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
    });

    Route::prefix('vendor')
    ->controller(VendorController::class)
    ->middleware('auth:api')
    ->group(function () {
        Route::get('all', 'all');
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
    });

    // -----------------------------------------------------------
    //                          APROVAL
    // -----------------------------------------------------------

    Route::prefix('office')
    ->middleware('auth:api')
    ->group(function () {

        Route::prefix('purchase-request')
        ->controller(OfficePurchaseRequestController::class)
        ->group(function () {
            Route::get('all', 'all');
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('{id}', 'show');
            Route::post('{id}/approval', 'approval');
        });

    });


    Route::prefix('procurement')
    ->middleware('auth:api')
    ->group(function () {

        Route::prefix('purchase-request')
        ->controller(ProcurementPurchaseRequestController::class)
        ->group(function () {
            Route::get('all', 'all');
            Route::get('/', 'index');
            Route::get('{id}', 'show');
            Route::post('{id}/approval', 'approval');
        });

        Route::prefix('request-for-quotation')
        ->controller(ProcurementRequestForQuotationController::class)
        ->group(function () {
            Route::get('all', 'all');
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('{id}', 'show');
            Route::post('{id}/propose-vendor', 'proposeVendor');
            Route::post('{id}/propose-approval', 'proposeApproval');
            Route::get('{id}/set-approve', 'setApprove');
            Route::get('{id}/set-reject', 'setReject');
        });

        Route::prefix('purchase-order')
        ->controller(ProcurementPurchaseOrderController::class)
        ->group(function () {
            Route::get('all', 'all');
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('{id}', 'show');
            Route::get('{id}/generate-po-vendor/{$vendorId}', 'printPODocument');
        });

    });


    Route::prefix('supplier/{slug}')
    ->group(function () {

        Route::prefix('request-for-quotation')
        ->controller(SupplierRequestForQuotationController::class)
        ->group(function () {
            Route::get('all', 'all');
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('{id}', 'show');
            Route::post('{id}/send-offer', 'sendOffer');
        });

        Route::prefix('purchase-order')
        ->controller(SupplierPurchaseOrderController::class)
        ->group(function () {
            Route::get('all', 'all');
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('{id}', 'show');
            Route::post('{id}/set-approve', 'setApprove');
            Route::post('{id}/set-reject', 'setReject');
        });

    });


    // -----------------------------------------------------------
    //                          CONVERSATION
    // -----------------------------------------------------------

    Route::prefix('message')
    ->group(function () {
        Route::prefix('conversation')
        ->controller(ConversationController::class)
        ->group(function () {
            Route::get('all', 'all');
            Route::post('/', 'store');
            Route::delete('{id}', 'destroy');
        });

        Route::prefix('chat')
        ->controller(ChatController::class)
        ->group(function () {
            Route::get('all', 'all');
            Route::post('/', 'store');
        });
    });

// });