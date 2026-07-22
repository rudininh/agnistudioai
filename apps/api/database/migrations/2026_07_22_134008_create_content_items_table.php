
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
        Schema::create('content_items', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->foreignId('idea_id')->nullable()->constrained('content_ideas')->onDelete('set null');
        $table->foreignId('workspace_id')->constrained('workspaces')->onDelete('cascade');
        $table->string('title');
        $table->text('content');
        $table->enum('format', ['article', 'video', 'short_video', 'podcast', 'post', 'story', 'thread', 'email', 'newsletter']);
        $table->enum('status', ['draft', 'scheduled', 'published', 'failed', 'archived'])->default('draft');
        $table->timestamp('published_at')->nullable();
        $table->jsonb('metadata')->nullable()->comment('wordCount, readingTime, tags, platforms, seo (title, description, keywords)');
        $table->timestamps();
            
        $table->index('idea_id');
        $table->index('workspace_id');
        $table->index('status');
        $table->index('format');
        $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_items');
    }
};

