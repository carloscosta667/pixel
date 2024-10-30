<?php

namespace App\Http\Controllers;

use App\Models\CarService as CarServiceModel;
use App\Traits\Helper;
use http\Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CarServiceController extends Controller
{
    use Helper;

    /**
     * Get car service available
     *
     * $id = null, get all car services
     * $id > 0, get the car service with $id
     * @param null $id
     * @return JsonResponse
     */
    public function getCarService($id = null): JsonResponse
    {
        try {

            $data = [];

            //if the id > 0 then try to get the car service with id
            if($id){

                $validatedData = Validator::make(
                    ['id_car_service' => $id],
                    CarServiceModel::isCarServiceIdValid()
                );

                $data = CarServiceModel::find($id);
                
                if($validatedData->fails()){
                    return $this->responseFormat($data, $validatedData->errors(),
                        Response::HTTP_BAD_REQUEST);
                }

            }else{

                //get all car service where deleted_at is not null
                $data = CarServiceModel::all();
            }

            return $this->responseFormat($data, config('api.service.car.get.success'),
                    Response::HTTP_OK);

        } catch ( Exception $e) {

            return $this->responseFormatUnknown($e);

        }

    }

    /**
     * Create car service
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createCarService(Request $request): JsonResponse
    {
        try {

            $data = [];
            $validatedData = Validator::make(
                $request->all(),
                CarServiceModel::isCarServiceNameValid()
            );

            if($validatedData->fails()){
                return $this->responseFormat($data, $validatedData->errors(),
                    Response::HTTP_BAD_REQUEST);
            }

            $carService = new CarServiceModel();

            $carService->name = $request->get('name');

            $carService->save();

            return $this->responseFormat(
                ['id_car_service' => $carService->id_car_service],
                config('api.service.car.post.success'),
                Response::HTTP_OK);

        } catch ( Exception $e) {

            return $this->responseFormatUnknown($e);

        }

    }


}
