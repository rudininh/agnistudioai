
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
        Schema::create('analytics_events', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->foreignId('content_item_id')->constrained('content_items')->onDelete('cascade');
        $table->enum('platform', ['youtube', 'tiktok', 'instagram', 'facebook', 'twitter']);
        $table->enum('event_type', ['view', 'like', 'comment', 'share', 'save', 'click', 'impression']);
        $table->integer('event_value')->default(1); // count of events
        $table->timestamp('event_timestamp');
        $table->json('metadata')->nullable()->comment('device, geography, traffic_source, etc.');
        $table->timestamp('recorded_at')->useCurrent();
            
        $table->index('content_item_id');
        $table->index('platform');
        $table->index('event_type');
        $table->index('event_timestamp');
        $table->index('recorded_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
    }
};

