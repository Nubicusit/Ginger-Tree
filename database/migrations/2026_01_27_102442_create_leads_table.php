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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            // Lead Details
            $table->string('client_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('location')->nullable();
            $table->text('project_address')->nullable();

            $table->enum('lead_source', [
                'Website', 'Instagram', 'WhatsApp', 'Walk-in',
                'BNI', 'Referral', 'Ads'
            ]);

            $table->enum('project_type', [
                'Residential', 'Commercial', 'Flat',
                'Industry', 'Outsourcing', 'Piece Rate'
            ]);

            // Project Information
            $table->string('budget_range')->nullable();
            $table->date('expected_start_date')->nullable();

            // Assignment
            $table->foreignId('sales_executive_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('designer_id')->nullable()->constrained('users')->nullOnDelete();

            // Lead Pipeline
            $table->enum('status', [
                'New', 'Contacted', 'Site Visit',
                'Proposal', 'Negotiation', 'Won', 'Lost'
            ])->default('New');

            // Lost Lead Analysis
            $table->enum('lost_reason', [
                'Price', 'Delay', 'No Response',
                'Design Mismatch', 'Others'
            ])->nullable();

            // Follow-up
            $table->date('next_followup_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
