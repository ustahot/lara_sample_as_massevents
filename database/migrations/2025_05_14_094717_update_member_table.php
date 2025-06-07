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
            $table->foreignIdFor(\App\Models\ETL\ImportSession::class,'etl_import_session_id')
                ->after('id')
                ->nullable()
                ->comment('id сессии импорта')
            ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table($this->getTableName(), function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\ETL\ImportSession::class, 'etl_import_session_id');
            $table->dropColumn('etl_import_session_id');
        });
    }

};
