<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\ConfigApprovalController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\MaterialCategoryController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\PurchaseRequestController;
use App\Http\Controllers\Api\PurchaseRequestItemController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VendorController;
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
        Route::get('refresh', 'refresh')->middleware('auth:api');
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
        Route::get('all', 'all');
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::put('{id}', 'update');
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
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
    });

    Route::prefix('purchase-request')
    ->controller(PurchaseRequestController::class)
    ->middleware('auth:api')
    ->group(function () {
        Route::get('all', 'all');
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
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

// });