
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
        Schema::create('scheduled_posts', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->foreignId('content_item_id')->constrained('content_items')->onDelete('cascade');
        $table->foreignId('workspace_id')->constrained('workspaces')->onDelete('cascade');
        $table->string('platform'); // youtube, tiktok, instagram, facebook, twitter, etc.
        $table->string('platform_post_id')->nullable(); // ID from the social media platform after publishing
        $table->enum('status', ['pending', 'scheduled', 'published', 'failed', 'cancelled'])->default('pending');
        $table->timestamp('scheduled_for');
        $table->timestamp('published_at')->nullable();
        $table->text('error_message')->nullable();
        $table->json('platform_specific_data')->nullable(); // For storing platform-specific metadata
        $table->timestamps();
        $table->softDeletes();
            
        $table->index(['workspace_id', 'status', 'scheduled_for']);
        $table->index('content_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_posts');
    }
};

