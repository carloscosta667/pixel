<?php

namespace App\Http\Controllers;

use App\Models\Mechanic as MechanicModel;
use App\Traits\Helper;
use http\Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class MechanicController extends Controller
{
    use Helper;

    /**
     * Get mechanic available
     *
     * $id = null, get all mechanics
     * $id > 0, get the mechanic by $id
     * @param null $id
     * @return JsonResponse
     */
    public function getMechanic($id = null): JsonResponse
    {
        try {

            $data = [];

            //if the id > 0 then try to get the mechanic by id
            if($id){

                $validatedData = Validator::make(
                    ['id_mechanic' => $id],
                    MechanicModel::isMechanicIdValid()
                );

                if($validatedData->fails()){
                    return $this->responseFormat($data, $validatedData->errors(),
                        Response::HTTP_BAD_REQUEST);
                }

                //get mechanic by id where deleted_at is not null
                $data = MechanicModel::find($id);

            }else{

                //get all mechanic where deleted_at is not null
                $data = MechanicModel::all();
            }

            return $this->responseFormat($data, config('api.mechanic.get.success'),
                    Response::HTTP_OK);

        } catch ( Exception $e) {

            return $this->responseFormatUnknown($e);

        }

    }

    /**
     * Create mechanic
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createMechanic(Request $request): JsonResponse
    {
        try {

            $data['first_name'] = $request->get('first_name');
            $data['last_name'] = $request->get('last_name');

            $validatedData = Validator::make(
                $data,
                MechanicModel::isMechanicNameValid($data['last_name']),
                MechanicModel::messageIsServiceTypeIdUnique()
            );

            if($validatedData->fails()){
                return $this->responseFormat($data, $validatedData->errors(),
                    Response::HTTP_BAD_REQUEST);
            }

            $mechanic = new MechanicModel();

            $mechanic->first_name = $request->get('first_name');
            $mechanic->last_name = $request->get('last_name');

            $mechanic->save();

            $data['id_mechanic'] = $mechanic->id_mechanic;

            return $this->responseFormat(
                $data,
                config('api.mechanic.post.success'),
                Response::HTTP_OK);

        } catch ( Exception $e) {

            return $this->responseFormatUnknown($e);

        }

    }

    /**
     * Update mechanic
     *
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateMechanic($id, Request $request): JsonResponse
    {
        try {

            $data['id_mechanic'] = $id;

            $validatedData = Validator::make(
                array_merge(
                    $data,
                    $request->all()
                ),
                array_merge(MechanicModel::isMechanicIdValid(),
                            MechanicModel::isMechanicNameValid($request->get('last_name'))
                ),
                MechanicModel::messageIsServiceTypeIdUnique()
            );

            if($validatedData->fails()){
                return $this->responseFormat($data, $validatedData->errors(),
                    Response::HTTP_BAD_REQUEST);
            }

            $mechanic = MechanicModel::find($id);

            $data['first_name']['old'] = $mechanic->first_name;
            $data['first_name']['new'] = $request->get('first_name');

            $data['last_name']['old'] = $mechanic->last_name;
            $data['last_name']['new'] = $request->get('last_name');

            $mechanic->first_name = $request->get('first_name');
            $mechanic->last_name = $request->get('last_name');

            $mechanic->save();

            return $this->responseFormat($data, config('api.mechanic.put.success'),
                Response::HTTP_OK);

        } catch ( Exception $e) {

            return $this->responseFormatUnknown($e);

        }

    }

    /**
     * Soft delete mechanic
     *
     * @param $id
     * @return JsonResponse
     */
    public function deleteMechanic($id): JsonResponse
    {
        try {

            $data['id_mechanic'] = $id;

            $validatedData = Validator::make(
                $data,
                MechanicModel::isMechanicIdValid()
            );

            if($validatedData->fails()){
                return $this->responseFormat($data, $validatedData->errors(),
                    Response::HTTP_BAD_REQUEST);
            }

            //soft deleted
            MechanicModel::find($id)->delete();

            return $this->responseFormat($data, config('api.mechanic.delete.success'),
                Response::HTTP_OK);

        } catch ( Exception $e) {

            return $this->responseFormatUnknown($e);

        }

    }


}
