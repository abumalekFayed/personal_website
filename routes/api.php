<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BondController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AccBondController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\BrkInvoiceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SlsInvoiceController;
use App\Http\Controllers\StrProductController;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\DestructionController;
use App\Http\Controllers\SlsDeliveryController;
use App\Http\Controllers\StrMaterialController;
use App\Http\Controllers\PurchaseItemController;
use App\Http\Controllers\RepaireOrderController;
use App\Http\Controllers\AccCostCenterController;
use App\Http\Controllers\AccTransactionController;
use App\Http\Controllers\BrkInvoiceItemController;

use App\Http\Controllers\SlsProccessingController;

use App\Http\Controllers\Employee\ShiftsController;
use App\Http\Controllers\SalesReservationController;
use App\Http\Controllers\Employee\VacationController;
use App\Http\Controllers\BrkProductionOrderController;
use App\Http\Controllers\CompetitorController;
use App\Http\Controllers\Employee\EmpLevelsController;
use App\Http\Controllers\SlsProductionOrderController;
use App\Http\Controllers\SalesReservationOrderController;
use App\Http\Controllers\Employee\LeavingPolicyController;
use App\Http\Controllers\Employee\EmpDesignationController;
use App\Http\Controllers\Employee\DepartmentsController as EmpDepartmentController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DepartmentItemController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//public routes
Route::apiResource('department', DepartmentController::class);
Route::apiResource('department-item', DepartmentItemController::class);


Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('refresh', [AuthController::class, 'refresh']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user', [AuthController::class, 'user']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});


Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('department-master', DepartmentMasterController::class);
    Route::get('dashboard', DashboardController::class);
    Route::apiResource('setting', SettingController::class);
    Route::apiResource('user', UserController::class);
});
