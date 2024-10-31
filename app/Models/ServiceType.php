<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class ServiceType extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'service_types';

    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    protected $primaryKey = 'id_service_type';

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
     * The car service that belong to type service.
     */
    public function carService(): BelongsToMany
    {
        return $this->belongsToMany(CarService::class,
            'car_services_has_service_types',
            'service_types_id_service_type',
            'car_services_id_car'
        )->withPivot('id_car_services_has_service_types');
    }

    /**
     * Check if service type id is valid.
     *
     * @return  array
     */
    static function isServiceTypeIdValid(): array
    {
        return [
            'id_service_type' => [
                'required',
                'integer',
                Rule::exists('service_types')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })
            ]
        ];
    }

    /**
     * Rules to create or update service type.
     *
     * @return  array
     */
    static function isServiceTypeNameValid(): array
    {
        return [
            'name' => 'required|string|min:2|max:45|unique:service_types'
        ];
    }
}
