<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Employer
Route::post('/employer', [App\Http\Controllers\EmployerController::class, 'store']);
Route::post('/employer/otp', [App\Http\Controllers\EmployerController::class, 'check_otp']);
Route::post('/employer/login', [App\Http\Controllers\EmployerController::class, 'authenticate']);


// Employee
Route::post('/employee', [App\Http\Controllers\EmployeeController::class, 'store']);
Route::post('/employee/otp', [App\Http\Controllers\EmployeeController::class, 'check_otp']);
Route::post('/employee/login', [App\Http\Controllers\EmployeeController::class, 'authenticate']);

Route::get('/employer', [App\Http\Controllers\EmployerController::class, 'allEmployer']);
Route::get('/employer/{account_number}', [App\Http\Controllers\EmployerController::class, 'getEmployer']);
Route::get('/employee', [App\Http\Controllers\EmployeeController::class, 'allEmployee']);
Route::get('/employee/{account_number}', [App\Http\Controllers\EmployeeController::class, 'getEmployee']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/employer/transaction', [App\Http\Controllers\EmployerController::class, 'makeTransaction']);

    

    
    Route::post('/employee/employer-details', [App\Http\Controllers\EmployerDetailsController::class, 'update']);

    Route::post('/employee/next-of-kin', [App\Http\Controllers\NextofKinController::class, 'update']);
});