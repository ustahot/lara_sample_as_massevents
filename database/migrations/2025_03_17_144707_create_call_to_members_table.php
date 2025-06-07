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
        Schema::create(self::getTableName(), function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Massevent\Member::class, 'member_id');
            $table->string('real_used_phone_at_calling',255)->comment('Телефон, который реально использовался в момент звонка');
            $table->string('status', 255)->default('draft')->comment('Статус звонка');
            $table->text('service_class')->nullable()->comment('Имя класса сервиса, который использовался при звонке');
            $table->text('record_link')->nullable()->comment('Ссылка на запись звонка');
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
