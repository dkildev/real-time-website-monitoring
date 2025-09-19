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
        Schema::create('incidents', function (Blueprint $t) {
            $t->id();
            $t->foreignId('monitor_id')->constrained()->cascadeOnDelete();
            $t->enum('state', ['open', 'resolved']);
            $t->timestamp('down_at');
            $t->timestamp('up_at')->nullable();
            $t->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
