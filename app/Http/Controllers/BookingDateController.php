<?php

namespace App\Http\Controllers;

use App\Models\BookingDate as BookingDateModel;
use App\Models\Mechanic as MechanicModel;
use App\Models\ServiceType as ServiceTypeModel;
use App\Traits\Helper;
use http\Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class BookingDateController extends Controller
{
    use Helper;

    /**
     * Get booking dates available
     *
     * $id = null, get all booking dates
     * $id > 0, get the booking date by $id
     * @param null $id
     * @return JsonResponse
     */
    public function getBookingDate($id = null): JsonResponse
    {
        try {

            $data = [];

            //if the id > 0 then try to get the booking date by id
            if($id){

                $validatedData = Validator::make(
                    ['id_booking_date' => $id],
                    BookingDateModel::isBookingDateIdValid()
                );

                if($validatedData->fails()){
                    return $this->responseFormat($data, $validatedData->errors(),
                        Response::HTTP_BAD_REQUEST);
                }

                //get booking date by id where deleted_at is not null
                $data = BookingDateModel::with('mechanic')->find($id);

            }else{

                //get all booking dates where deleted_at is not null
                $data = BookingDateModel::with('mechanic')->get();
            }

            return $this->responseFormat($data, config('api.booking_date.get.success'),
                    Response::HTTP_OK);

        } catch ( Exception $e) {

            return $this->responseFormatUnknown($e);

        }

    }

    /**
     * Create booking date
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createBookingDate(Request $request): JsonResponse
    {
        try {

            $data['id_mechanic'] = $request->get('id_mechanic');
            $data['id_service_type'] = $request->get('id_service_type');
            $data['start_date_service'] = $request->get('start_date_service');
            $data['end_date_service'] = $request->get('end_date_service');

            $validatedData = Validator::make(
                array_merge(
                    ['mechanics_id_mechanic' => $request->get('id_mechanic')],
                    $data
                ),
                array_merge(
                    MechanicModel::isMechanicIdValid(),
                    ServiceTypeModel::isServiceTypeIdValid(),
                    BookingDateModel::isBookingDatesValid(),
                    BookingDateModel::isBookingDateMechanicAvailable($data['start_date_service'], $data['end_date_service'])
                ),
                BookingDateModel::messageIsBookingDateMechanicAvailable()
            );

            if($validatedData->fails()){
                return $this->responseFormat($data, $validatedData->errors(),
                    Response::HTTP_BAD_REQUEST);
            }

            $booking_date = new BookingDateModel();

            $booking_date->mechanics_id_mechanic = $request->get('id_mechanic');
            $booking_date->service_types_id_service_type = $request->get('id_service_type');

            $booking_date->start_date_service = $request->get('start_date_service');
            $booking_date->end_date_service = $request->get('end_date_service');

            $booking_date->save();

            $data['id_booking_date'] = $booking_date->id_booking_date . '';

            return $this->responseFormat(
                $data,
                config('api.booking_date.post.success'),
                Response::HTTP_OK);

        } catch ( Exception $e) {

            return $this->responseFormatUnknown($e);

        }

    }

    /**
     * Update booking date
     *
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateBookingDate($id, Request $request): JsonResponse
    {
        try {

            $data['id_booking_date'] = $id;

            $validatedData = Validator::make(
                array_merge(
                    $data,
                    ['mechanics_id_mechanic' => $request->get('id_mechanic')],
                    $request->all()
                ),
                array_merge(
                    MechanicModel::isMechanicIdValid(),
                    ServiceTypeModel::isServiceTypeIdValid(),
                    BookingDateModel::isBookingDateIdValid(),
                    BookingDateModel::isBookingDatesValid(),
                    BookingDateModel::isBookingDateMechanicAvailable(
                        $request->get('start_date_service'),
                        $request->get('end_date_service')
                    )
                ),
                BookingDateModel::messageIsBookingDateMechanicAvailable()
            );

            if($validatedData->fails()){
                return $this->responseFormat($data, $validatedData->errors(),
                    Response::HTTP_BAD_REQUEST);
            }

            $booking_date = BookingDateModel::find($id);

            $data['id_mechanic']['old'] = $booking_date->mechanics_id_mechanic;
            $data['id_mechanic']['new'] = $request->get('id_mechanic');

            $data['id_service_type']['old']  = $booking_date->service_types_id_service_type;
            $data['id_service_type']['new']  = $request->get('id_service_type');

            $data['start_date_service']['old'] = $booking_date->start_date_service;
            $data['start_date_service']['new'] = $request->get('start_date_service');

            $data['end_date_service']['old'] = $booking_date->end_date_service;
            $data['end_date_service']['new'] = $request->get('end_date_service');

            $booking_date->save();

            return $this->responseFormat($data, config('api.booking_date.put.success'),
                Response::HTTP_OK);

        } catch ( Exception $e) {

            return $this->responseFormatUnknown($e);

        }

    }

    /**
     * Soft delete booking date
     *
     * @param $id
     * @return JsonResponse
     */
    public function deleteBookingDate($id): JsonResponse
    {
        try {

            $data['id_booking_date'] = $id;

            $validatedData = Validator::make(
                $data,
                BookingDateModel::isBookingDateIdValid()
            );

            if($validatedData->fails()){
                return $this->responseFormat($data, $validatedData->errors(),
                    Response::HTTP_BAD_REQUEST);
            }

            //soft deleted
            BookingDateModel::find($id)->delete();

            return $this->responseFormat($data, config('api.booking_date.delete.success'),
                Response::HTTP_OK);

        } catch ( Exception $e) {

            return $this->responseFormatUnknown($e);

        }

    }

}
