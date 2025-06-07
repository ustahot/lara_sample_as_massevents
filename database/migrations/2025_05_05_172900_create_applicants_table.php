<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function getTableName()
    {
        return app(\App\Models\HR\Applicant::class)->getTable();
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(self::getTableName(), function (Blueprint $table) {
            $table->id();
            $table->string('code', 255)->unique();
            $table->string('full_name', 255)->nullable();
            $table->string('email', 255)->nullable()->unique();
            $table->string('phone', 255)->nullable()->unique();
            $table->string('create_case', 255)->nullable()->comment('Кейс, которым был создан соискатель');
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
