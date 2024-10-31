<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
        'deleted_at',
        'updated_at',
        'created_at'
    ];

    /**
     * Check if mechanic id is valid.
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

}
