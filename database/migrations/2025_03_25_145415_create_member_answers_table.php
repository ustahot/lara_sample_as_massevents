<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function getTableName()
    {
        return app(\App\Models\Massevent\MemberAnswer::class)->getTable();
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(self::getTableName(), function (Blueprint $table) {
            $table->id();
            $table->string('member_hash_id', 255);
            $table->foreign('member_hash_id')
                ->references('hash_id')
                ->on(app(\App\Models\Massevent\Member::class)->getTable()
            );
            $table->string('status', 255)->nullable();
            $table->text('request')->nullable();
            $table->string('type', 255)->nullable();
            $table->text('answer')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_answers');
    }
};
