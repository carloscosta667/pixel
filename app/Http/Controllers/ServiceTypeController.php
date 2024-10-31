<?php

namespace App\Http\Controllers;

use App\Models\ServiceType as ServiceTypeModel;
use App\Traits\Helper;
use http\Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ServiceTypeController extends Controller
{
    use Helper;

    /**
     * Get service type
     *
     * $id = null, get all service types
     * $id > 0, get the service type by $id
     * @param null $id
     * @return JsonResponse
     */
    public function getServiceType($id = null): JsonResponse
    {
        try {

            $data = [];

            //if the id > 0 then try to get the service type by id
            if($id){

                $validatedData = Validator::make(
                    ['id_service_type' => $id],
                    ServiceTypeModel::isServiceTypeIdValid()
                );

                if($validatedData->fails()){
                    return $this->responseFormat($data, $validatedData->errors(),
                        Response::HTTP_BAD_REQUEST);
                }

                //get service type by id where deleted_at is not null
                $data['id_service_type'] = $id;

                $data = ServiceTypeModel::with('carService')->find($id);

            }else{

                //get all service type where deleted_at is not null
                $data = ServiceTypeModel::with('carService')->get();
            }

            return $this->responseFormat($data, config('api.service.type.get.success'),
                    Response::HTTP_OK);

        } catch ( Exception $e) {

            return $this->responseFormatUnknown($e);

        }

    }

    /**
     * Create service type
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createServiceType(Request $request): JsonResponse
    {
        try {

            $data = [];
            $validatedData = Validator::make(
                $request->all(),
                ServiceTypeModel::isServiceTypeNameValid()
            );

            if($validatedData->fails()){
                return $this->responseFormat($data, $validatedData->errors(),
                    Response::HTTP_BAD_REQUEST);
            }

            $serviceType = new ServiceTypeModel();

            $serviceType->name = $request->get('name');

            $serviceType->save();

            return $this->responseFormat(
                ['id_service_type' => $serviceType->id_service_type],
                config('api.service.type.post.success'),
                Response::HTTP_OK);

        } catch ( Exception $e) {

            return $this->responseFormatUnknown($e);

        }

    }

    /**
     * Update service type
     *
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateServiceType($id, Request $request): JsonResponse
    {
        try {

            $data['id_service_type'] = $id;
            $validatedData = Validator::make(
                array_merge(
                    $data,
                    $request->all()
                ),
                array_merge(ServiceTypeModel::isServiceTypeIdValid(),
                            ServiceTypeModel::isServiceTypeNameValid()
                )
            );

            if($validatedData->fails()){
                return $this->responseFormat($data, $validatedData->errors(),
                    Response::HTTP_BAD_REQUEST);
            }

            $serviceType = ServiceTypeModel::find($id);

            $data['name']['old'] = $serviceType->name;
            $data['name']['new'] = $request->get('name');

            $serviceType->name = $request->get('name');
            $serviceType->save();

            return $this->responseFormat($data, config('api.service.type.put.success'),
                Response::HTTP_OK);

        } catch ( Exception $e) {

            return $this->responseFormatUnknown($e);

        }

    }

    /**
     * Soft delete service type
     *
     * @param $id
     * @return JsonResponse
     */
    public function deleteServiceType($id): JsonResponse
    {
        try {

            $data['id_service_type'] = $id;

            $validatedData = Validator::make(
                $data,
                ServiceTypeModel::isServiceTypeIdValid()
            );

            if($validatedData->fails()){
                return $this->responseFormat($data, $validatedData->errors(),
                    Response::HTTP_BAD_REQUEST);
            }

            //soft deleted
            ServiceTypeModel::find($id)->delete();

            return $this->responseFormat($data, config('api.service.type.delete.success'),
                Response::HTTP_OK);

        } catch ( Exception $e) {

            return $this->responseFormatUnknown($e);

        }

    }

}
