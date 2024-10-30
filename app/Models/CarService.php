<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarService extends Model
{
    use HasFactory;
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
     * Check if car service id is valid.
     *
     * @return  array
     */
    static function isCarServiceIdValid(): array
    {
        return [
            'id_car_service' => 'required|exists:car_services',
        ];
    }

    /**
     * Rules to create a car service.
     *
     * @return  array
     */
    static function isCarServiceNameValid(): array
    {
        return [
            'name' => 'required|string|min:2|max:45|unique:car_services'
        ];
    }
}
