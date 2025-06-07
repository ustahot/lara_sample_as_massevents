<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function getTableName()
    {
        return app(\App\Models\Massevent\CallToMember::class)->getTable();
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table($this->getTableName(), function (Blueprint $table) {
            $table->text('data_from_service')->after('status')->nullable()->comment('Данные, полученные от сервиса');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table($this->getTableName(), function (Blueprint $table) {
            $table->dropColumn('data_from_service');
        });
    }
};
