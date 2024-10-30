<?php

use App\Http\Controllers\CarServiceController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Response;
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

Route::middleware('api')
    ->group(function() {
        Route::prefix('v' . env('API_VERSION', '1'))
            ->group(function() {

                //need bearer token
                Route::middleware('auth:api')
                    ->middleware('throttle:60,1')->group(function () {

                        Route::prefix('car')->group(function () {
                                Route::post('/service', [CarServiceController::class,'createCarService']);
                                Route::put('/service/{id}', [CarServiceController::class,'updateCarService']);
                                Route::delete('/service/{id}', [CarServiceController::class,'deleteCarService']);
                        });

                        Route::prefix('service')->group(function () {
                            Route::post('/type', [ServiceTypeController::class,'createCarService']);
                            Route::put('/type/{id}', [ServiceTypeController::class,'updateCarService']);
                            Route::delete('/type/{id}', [ServiceTypeController::class,'deleteCarService']);
                        });

                    });

                //using throttle to protect against DoS attacks
                Route::middleware('throttle:60,1')->group(function () {

                    Route::get('/car/service/{id?}', [CarServiceController::class, 'getCarService']);

                    Route::get('/service/type/{id?}', [ServiceTypeController::class, 'getServiceType']);

                    Route::post('auth', [UserController::class,'login']);

                });

                Route::any('/{any}', function() {
                    return response()->json([
                        'message' => 'Car system booking API endpoint not found - if this problem persists, please contact web developer.'
                    ], Response::HTTP_NOT_FOUND);
                })->where('any', '.*');
            });
    });

