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
        Schema::create('monitor_checks', function (Blueprint $t) {
            $t->id();
            $t->foreignId('monitor_id')->constrained()->cascadeOnDelete();
            $t->boolean('ok');
            $t->unsignedSmallInteger('status_code')->nullable();
            $t->float('latency_ms')->nullable();
            $t->text('error')->nullable();
            $t->timestamp('checked_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitor_checks');
    }
};
