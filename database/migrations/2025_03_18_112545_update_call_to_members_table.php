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
        Schema::table(self::getTableName(), function (Blueprint $table) {
            $table->string('phone_from', 255)->after('member_id')->comment('Номер телефона, с которого делается звонок');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(self::getTableName(), function (Blueprint $table) {
            $table->dropColumn('phone_from');
        });
    }
};
