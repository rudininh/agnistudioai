
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
        Schema::create('content_ideas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->string('title');
            $table->text('description');
            $table->string('content_type')->comment('youtube_video, blog_post, tweet_thread, etc.');
            $table->enum('status', ['new', 'reviewed', 'in_progress', 'approved', 'rejected'])->default('new');
            $table->smallInteger('priority')->default(3)->comment('1-5, where 1 is highest priority');
            $table->jsonb('tags')->nullable();
            $table->integer('opportunity_score')->nullable();
            $table->enum('opportunity_confidence', ['high', 'medium', 'low'])->nullable();
            $table->jsonb('metadata')->nullable()->comment('estimatedEffort, targetAudience, keywords, sources');
            $table->timestamps();
            $table->timestamp('scheduled_for')->nullable();

            $table->index('project_id');
            $table->index('status');
            $table->index('priority');
            $table->index('opportunity_score');
            $table->index('content_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_ideas');
    }
};
