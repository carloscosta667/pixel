<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class Mechanic extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mechanics';

    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    protected $primaryKey = 'id_mechanic';

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
     * Mechanics has many booking dates.
     *
     * @return HasMany
     */
    public function bookingDate(): HasMany
    {
        return $this->hasMany(BookingDate::class,
            'mechanics_id_mechanic',
            'id_mechanic'
        );
    }

    /**
     * Check if mechanic id is valid.
     *
     * @return  array
     */
    static function isMechanicIdValid(): array
    {
        return [
            'id_mechanic' => [
                'integer',
                'nullable',
                Rule::exists('mechanics')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })
            ]
        ];
    }

    /**
     * Rules to create or update a mechanic.
     * Check if we have the first name and last name already registered
     *
     * @param $last_name
     * @return  array
     */
    static function isMechanicNameValid($last_name): array
    {
        return [
            'first_name' => [
                'required',
                'string',
                'min:2',
                'max:45',
                Rule::unique('mechanics')->where(function ($query) use ($last_name) {
                    return $query->where('last_name', $last_name);
                })
            ],
            'last_name' => 'required|string|min:2|max:45'
        ];
    }

    /**
     * Rules messages about the mechanic with the same first name and the same last name
     *
     * @return array[]
     */
    static function messageIsServiceTypeIdUnique(): array
    {
        return [
            'first_name.unique' => config('api.mechanic.unique_name')
        ];
    }
}
