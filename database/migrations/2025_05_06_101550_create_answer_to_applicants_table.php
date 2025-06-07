<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function getTableName()
    {
        return app(\App\Models\Hr\AnswerToApplicant::class)->getTable();
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(self::getTableName(), function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Hr\Applicant::class, 'applicant_id')->nullable()->constrained();
            $table->string('email_from', 255)->nullable();
            $table->string('email_to', 255);
            $table->text('subject')->nullable();
            $table->text('content')->nullable();
            $table->string('template_code', 255)->comment('код шаблона для формирования контента');
            $table->string('service_class', 1023)->nullable();
            $table->text('service_response')->nullable();
            $table->string('status', 255)->nullable();
            $table->string('author_code', 255)->nullable()->comment('Внешний идентификатор автора');
            $table->dateTime('sent_at')->nullable();
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
