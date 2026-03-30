<?php
// database/migrations/xxxx_create_two_d_designs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('two_d_designs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();

            // Drawing scope
            $table->enum('drawing_type', [
                'Working Drawing',
                'Interior Drawing',
                'Detail Drawing',
            ])->default('Working Drawing');

            // Workflow
            $table->enum('drawing_status', [
                'In Progress',
                'Submitted',
                'Approved',
            ])->default('In Progress');

            // People
            $table->string('assigned_designer', 150)->nullable();
            $table->string('project_manager', 150)->nullable();

            // Dates
            $table->date('drawing_start_date')->nullable();
            $table->date('submission_date')->nullable();
            $table->date('approval_date')->nullable();

            // Review
            $table->boolean('pm_internal_review')->default(false);
            $table->date('pm_review_date')->nullable();
            $table->text('pm_review_notes')->nullable();

            // Client approval
            $table->boolean('client_approval')->default(false);
            $table->text('client_feedback')->nullable();

            // Revisions
            $table->unsignedSmallInteger('revision_count')->default(0);
            $table->text('revision_notes')->nullable();

            // Finalization
            $table->boolean('internal_approval')->default(false);
            $table->boolean('design_freeze')->default(false);
            $table->date('freeze_date')->nullable();

            // Production forwarding
            $table->boolean('forwarded_to_production')->default(false);
            $table->date('forwarded_date')->nullable();

            // Post-freeze
            $table->boolean('additional_cost_flag')->default(false);
            $table->text('change_after_freeze_note')->nullable();

            // Files
            $table->json('drawing_files')->nullable();

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('two_d_designs');
    }
};

