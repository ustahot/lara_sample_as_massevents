<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function getTableName()
    {
        return app(\App\Models\Massevent\Member::class)->getTable();
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table($this->getTableName(), function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\HR\Employee::class, 'manager_id')
                ->after('manager_guid')
                ->nullable()
                ->constrained()
            ;
            $table->string('manager_status', 255)
                ->after('manager_guid')
                ->nullable()
            ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table($this->getTableName(), function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\HR\Employee::class, 'manager_id');
            $table->dropColumn('manager_id');
            $table->dropColumn('manager_guid');
        });
    }
};
