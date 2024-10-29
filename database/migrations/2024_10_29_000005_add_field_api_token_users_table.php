<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public string $tableName = 'users';

    /**
     * @var string
     */
    public string $column = 'api_token';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(Schema::hasColumn($this->tableName, $this->column)) return;
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->string($this->column, 255)->nullable()->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(!Schema::hasColumn($this->tableName, $this->column)) return;
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->dropColumn($this->column);
        });
    }
};
