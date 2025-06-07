<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function getTableName()
    {
        return app(\App\Models\ETL\StageExtractMember::class)->getTable();
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(self::getTableName(), function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\ETL\ImportSession::class, 'session_id')
                ->references('id')->on(app(\App\Models\ETL\ImportSession::class)->getTable());
            $table->foreignIdFor(\App\Models\Massevent\Massevent::class, 'massevent_id')
                ->references('id')->on(app(\App\Models\Massevent\Massevent::class)->getTable());
            $table->string('status', 255)->default('new');
            $table->string('category_code', 255)->nullable();
            $table->string('outer_id', 255)->nullable();
            $table->string('name', 1023)->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('manager_name', 255)->nullable();
            $table->string('member_guid', 255)->nullable();
            $table->string('manager_guid', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(self::getTableName());
    }
};
