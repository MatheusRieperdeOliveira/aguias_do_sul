<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('points', function (Blueprint $table) {
            $table->foreignId('recorded_by_user_id')
                ->nullable()
                ->after('pathfinder_id')
                ->constrained('users')
                ->nullOnDelete();
            $table->string('recorded_from_ip', 45)->nullable()->after('recorded_by_user_id');
        });
    }

    public function down(): void
    {
        Schema::table('points', function (Blueprint $table) {
            $table->dropForeign(['recorded_by_user_id']);
            $table->dropColumn(['recorded_by_user_id', 'recorded_from_ip']);
        });
    }
};
