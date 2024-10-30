<?php

namespace Database\Migrations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public string $tableName = 'booking_dates';

    /**
     * Run the migrations.
     * @table booking_dates
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable($this->tableName)) return;
        Schema::create($this->tableName, function (Blueprint $table) {

            $table->increments('id_booking_date');

            $table->unsignedInteger('mechanics_id_mechanic');
            $table->foreign('mechanics_id_mechanic', 'fk_booking_dates_mechanics_idx')
                ->references('id_mechanic')->on('mechanics')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->unsignedInteger('id_car_services_has_service_types');
            $table->foreign('id_car_services_has_service_types', 'fk_services_has_service_types_car_services1_idx')
                ->references('id_car_services_has_service_types')->on('car_services_has_service_types')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->dateTime('start_date_service');
            $table->dateTime('end_date_service');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
