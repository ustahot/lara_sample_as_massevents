<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    private function getTableName()
    {
        return app(\App\Models\Massevent\MemberSmsSimpleMessage::class)->getTable();
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(self::getTableName(), function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Massevent\Member::class, 'member_id');
            $table->string('real_used_phone_at_sending',255)->comment('Телефон, который реально использовался в момент отправки');
            $table->text('text')->comment('Текст сообщения');
            $table->string('status', 255)->default('draft')->comment('Статус сообщения');
            $table->timestamp('sent_at')->nullable()->comment('Время отправки сообщения');
            $table->timestamp('last_status_at')->nullable()->comment('Время последнего запроса статуса');
            $table->unsignedInteger('status_request_try_quantity')->default(0)->comment('Количество сделанных запросов статуса сообщения');
            $table->text('service_class')->nullable()->comment('Имя класса сервиса, который использовался при отправке');
            $table->string('outer_id', 1023)->nullable()->comment('Идентификатор сообщения, в контексте внешнего сервиса');
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
