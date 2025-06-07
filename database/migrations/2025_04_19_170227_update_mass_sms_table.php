<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function getTableName()
    {
        return app(\App\Models\Massevent\MassSms::class)->getTable();
    }
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table($this->getTableName(), function (Blueprint $table) {
            $table->text('member_id_collection', 255)
                ->after('real_used_phones_at_sending')
                ->nullable()
                ->comment('коллекция id участников мероприятия, для которых формируется эта рассылка')
            ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table($this->getTableName(), function (Blueprint $table) {
            $table->dropColumn('real_used_phones_at_sending');
        });
    }
};
