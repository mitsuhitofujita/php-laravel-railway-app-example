<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('railway_sequences', function (Blueprint $table) {
            $table->id();
            $table->datetime('created_at', 6)->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('railway_sequences');
    }
};
