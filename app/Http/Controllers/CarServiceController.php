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

                if($validatedData->fails()){
                    return $this->responseFormat($data, $validatedData->errors(),
                        Response::HTTP_BAD_REQUEST);
                }

                //get car service by id where deleted_at is not null
                $data = CarServiceModel::find($id);

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

    /**
     * Update car service
     *
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateCarService($id, Request $request): JsonResponse
    {
        try {

            $data['id_car_service'] = $id;
            $validatedData = Validator::make(
                array_merge(
                    $data,
                    $request->all()
                ),
                array_merge(CarServiceModel::isCarServiceIdValid(),
                            CarServiceModel::isCarServiceNameValid()
                )
            );

            if($validatedData->fails()){
                return $this->responseFormat($data, $validatedData->errors(),
                    Response::HTTP_BAD_REQUEST);
            }

            $carService = CarServiceModel::find($id);

            $data['name']['old'] = $carService->name;
            $data['name']['new'] = $request->get('name');

            $carService->name = $request->get('name');
            $carService->save();

            return $this->responseFormat($data, config('api.service.car.put.success'),
                Response::HTTP_OK);

        } catch ( Exception $e) {

            return $this->responseFormatUnknown($e);

        }

    }

    /**
     * Soft delete car service
     *
     * @param $id
     * @return JsonResponse
     */
    public function deleteCarService($id): JsonResponse
    {
        try {

            $data['id_car_service'] = $id;

            $validatedData = Validator::make(
                $data,
                CarServiceModel::isCarServiceIdValid()
            );

            if($validatedData->fails()){
                return $this->responseFormat($data, $validatedData->errors(),
                    Response::HTTP_BAD_REQUEST);
            }

            //soft deleted
            CarServiceModel::find($id)->delete();

            return $this->responseFormat($data, config('api.service.car.delete.success'),
                Response::HTTP_OK);

        } catch ( Exception $e) {

            return $this->responseFormatUnknown($e);

        }

    }

}
