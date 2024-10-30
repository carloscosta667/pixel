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
    public string $tableName = 'car_services_has_service_types';

    /**
     * Run the migrations.
     * @table car_services_has_service_types
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable($this->tableName)) return;
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id_car_services_has_service_types');
            $table->unsignedInteger('car_services_id_car');
            $table->foreign('car_services_id_car', 'fk_car_services_has_service_types_car_services1_idx')
                ->references('id_car_service')->on('car_services')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->unsignedInteger('service_types_id_service_type');
            $table->foreign('service_types_id_service_type', 'fk_car_services_has_service_types_service_types1_idx')
                ->references('id_service_type')->on('service_types')
                ->onDelete('no action')
                ->onUpdate('no action');
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
