
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pathfinders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('status');
            $table->string('ddd')->nullable();
            $table->string('phone')->nullable();
            $table->string('full_phone')->nullable();
            $table->date('birthday');
            $table->integer('age');
            $table->string('responsible_name')->nullable();
            $table->string('responsible_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('pathfinders');
    }
};
