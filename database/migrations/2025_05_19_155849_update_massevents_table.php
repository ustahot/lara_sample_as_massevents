<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function getTableName()
    {
        return app(\App\Models\Massevent\Massevent::class)->getTable();
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table($this->getTableName(), function (Blueprint $table) {
            $table->text('booking_notify_message')->nullable();
            $table->text('reminder_tomorrow_message')->nullable();
            $table->text('reminder_today_message')->nullable();
            $table->text('feedback_request_message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table($this->getTableName(), function (Blueprint $table) {
            $table->dropColumn('booking_notify_message');
            $table->dropColumn('reminder_tomorrow_message');
            $table->dropColumn('reminder_today_message');
            $table->dropColumn('feedback_request_message');
        });
    }
};
