<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class CarService extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'car_services';

    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    protected $primaryKey = 'id_car_service';

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
     * The type service that belong to car service.
     */
    public function serviceType(): BelongsToMany
    {
        return $this->belongsToMany(ServiceType::class,
            'car_services_has_service_types',
            'car_services_id_car',
            'service_types_id_service_type');
    }

    /**
     * Check if car service id is valid.
     *
     * @return  array
     */
    static function isCarServiceIdValid(): array
    {
        return [
            'id_car_service' => [
                                    'required',
                                    Rule::exists('car_services')->where(function ($query) {
                                        return $query->whereNull('deleted_at');
                                    })
                                ]
        ];
    }

    /**
     * Rules to create or update a car service.
     *
     * @return  array
     */
    static function isCarServiceNameValid(): array
    {
        return [
            'name' => 'required|string|min:2|max:45|unique:car_services'
        ];
    }

    /**
     * Rules add new service type to the car service.
     *
     * @param $id_service_type
     * @return  array
     */
    static function isServiceTypeIdUnique($id_service_type): array
    {
        return [
            'car_services_id_car' => [
                Rule::unique('car_services_has_service_types')->where(function ($query) use ($id_service_type) {
                    return $query->where('service_types_id_service_type',$id_service_type);
                })
            ]
        ];
    }

    /**
     * Rules messages about the service type already linked
     *
     * @return array[]
     */
    static function messageIsServiceTypeIdUnique(): array
    {
        return [
            'car_services_id_car.unique' => config('api.service.car.type.already_linked')
        ];
    }

    /**
     * Rules to check if the id provided exist on car_services_has_service_types table.
     *
     * @return  array
     */
    static function isServiceTypeLinkedCarServiceValid(): array
    {
        return [
            'id_car_services_has_service_types' => 'required|exist:car_services_has_service_types'
        ];
    }
}
