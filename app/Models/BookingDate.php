<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class BookingDate extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'booking_dates';

    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    protected $primaryKey = 'id_booking_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'service_types_id_service_type',
        'mechanics_id_mechanic',
        'deleted_at',
        'updated_at',
        'created_at'
    ];

    /**
     * Booking date has one mechanic.
     */
    public function mechanic(): HasOne
    {
        return $this->hasOne(Mechanic::class,
            'id_mechanic',
            'mechanics_id_mechanic'
        );
    }

    /**
     * Booking date has one service type.
     */
    public function serviceType(): HasOne
    {
        return $this->hasOne(ServiceType::class,
            'id_service_type',
            'service_types_id_service_type'
        );
    }

    /**
     * Check if booking date id is valid.
     *
     * @return  array
     */
    static function isBookingDateIdValid(): array
    {
        return [
            'id_booking_date' => [
                'required',
                Rule::exists('booking_dates')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })
            ]
        ];
    }

    /**
     * Check if mechanic is available between start date and end date.
     *
     * @param $start_date_service
     * @param $end_date_service
     * @return  array
     */
    static function isBookingDateMechanicAvailable($start_date_service, $end_date_service): array
    {
        return [
            'mechanics_id_mechanic' => [
                'required',
                Rule::unique('booking_dates')->where(function ($query) use ($start_date_service, $end_date_service) {

                    return $query->where(function ($query) use ($start_date_service, $end_date_service) {
                        $query->whereBetween('start_date_service', [$start_date_service, $end_date_service])
                            ->orwhereBetween('end_date_service', [$start_date_service, $end_date_service]);
                    })->whereNull('deleted_at');

                })
            ]
        ];
    }

    /**
     * Check if start date and end date is valid.
     *
     * @return  array
     */
    static function isBookingDatesValid(): array
    {
        return [
            'start_date_service'      => 'required|date|after:now|before:end_date_service',
            'end_date_service'        => 'date|after:start_date_service',
        ];
    }

    /**
     * Rules messages about the mechanic with the same first name and the same last name
     *
     * @return array[]
     */
    static function messageIsBookingDateMechanicAvailable(): array
    {
        return [
            'mechanics_id_mechanic.unique' => config('api.booking_date.available')
        ];
    }
}
