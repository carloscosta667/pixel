<?php

use App\Http\Controllers\BookingDateController;
use App\Http\Controllers\CarServiceController;
use App\Http\Controllers\MechanicController;
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

                //routes admin access
                Route::middleware('auth:api')
                    ->middleware('throttle:60,1')->group(function () {

                        Route::prefix('car/service')->group(function () {
                                Route::post('/', [CarServiceController::class,'createCarService']);
                                Route::put('/{id}', [CarServiceController::class,'updateCarService']);
                                Route::delete('/{id}', [CarServiceController::class,'deleteCarService']);

                                Route::post('/link-service-type', [CarServiceController::class,'linkServiceTypeToCarService']);
                                Route::delete('/unlink-service-type/{id}', [CarServiceController::class,'unlinkServiceTypeFromCarService']);
                        });

                        Route::prefix('service/type')->group(function () {
                            Route::post('/', [ServiceTypeController::class,'createServiceType']);
                            Route::put('/{id}', [ServiceTypeController::class,'updateServiceType']);
                            Route::delete('/{id}', [ServiceTypeController::class,'deleteServiceType']);
                        });

                        Route::prefix('mechanic')->group(function () {
                            Route::post('/', [MechanicController::class,'createMechanic']);
                            Route::put('/{id}', [MechanicController::class,'updateMechanic']);
                            Route::delete('/{id}', [MechanicController::class,'deleteMechanic']);
                        });

                        Route::prefix('booking-date')->group(function () {
                            Route::post('/', [BookingDateController::class,'createBookingDate']);
                            Route::put('/{id}', [BookingDateController::class,'updateBookingDate']);
                            Route::delete('/{id}', [BookingDateController::class,'deleteBookingDate']);
                        });
                    });


                //routes customer access
                //using throttle to protect against DoS attacks
                Route::middleware('throttle:60,1')->group(function () {

                    Route::get('/car/service/{id?}', [CarServiceController::class, 'getCarService']);

                    Route::get('/service/type/{id?}', [ServiceTypeController::class, 'getServiceType']);

                    Route::get('/mechanic/{id?}', [MechanicController::class,'getMechanic']);

                    Route::get('/booking-date/{id?}', [BookingDateController::class,'getBookingDate']);

                    Route::post('auth', [UserController::class,'login']);

                });

                Route::any('/{any}', function() {
                    return response()->json([
                        'message' => 'Car system booking API endpoint not found - if this problem persists, please contact web developer.'
                    ], Response::HTTP_NOT_FOUND);
                })->where('any', '.*');
            });
    });

