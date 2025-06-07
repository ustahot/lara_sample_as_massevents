<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    private function getTableName()
    {
        return app(\App\Models\Massevent\TicketSet::class)->getTable();
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table($this->getTableName(), function (Blueprint $table) {
            $table->unsignedInteger('ticket_plan')->comment('Забронировано билетов');
            $table->unsignedBigInteger('member_id')->comment('id участника мероприятия. не является внешним ключом. чисто для истории');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table($this->getTableName(), function (Blueprint $table) {
            $table->dropColumn('ticket_plan');
            $table->dropColumn('member_id');
        });
    }
};
