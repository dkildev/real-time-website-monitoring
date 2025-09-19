<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('monitors', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('url');
            $t->unsignedInteger('interval_seconds')->default(60);
            $t->unsignedSmallInteger('timeout_seconds')->default(8);
            $t->string('expect')->nullable();
            $t->boolean('enabled')->default(true);
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitors');
    }
};
