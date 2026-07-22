
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
        Schema::create('projects', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->foreignId('workspace_id')->constrained('workspaces')->onDelete('cascade');
        $table->string('name');
        $table->string('slug');
        $table->text('description')->nullable();
        $table->enum('status', ['active', 'archived', 'completed'])->default('active');
        $table->json('metadata')->nullable()->comment('color, icon, createdBy');
        $table->timestamps();
        $table->softDeletes();
            
        $table->unique(['workspace_id', 'slug']);
        $table->index('workspace_id');
        $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

