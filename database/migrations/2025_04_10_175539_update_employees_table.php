<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function getTableName()
    {
        return app(\App\Models\HR\Employee::class)->getTable();
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table($this->getTableName(), function (Blueprint $table) {
            $table->string('corp_family_status', 255)
                ->after('code')
                ->nullable()
            ;
            $table->string('telegram_user_id', 255)
                ->unique()
                ->after('code')
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
            $table->dropColumn('telegram_user_id');
            $table->dropColumn('corp_family_status');
        });
    }
};
