<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('store_railway_provider_requests', function (Blueprint $table) {
            $table->id();
            $table->text('token')->unique('store_railway_provider_request_unique_1');
            $table->foreignId('railway_provider_event_stream_id')->index('store_railway_provider_request_index_1');
            $table->timestamp('valid_from', 6);
            $table->text('name');
            $table->timestamp('created_at', 6)->useCurrent();

            $table->foreign('railway_provider_event_stream_id')->references('id')->on('railway_provider_event_streams');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_railway_provider_requests');
    }
};
