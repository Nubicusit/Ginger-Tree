<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('site_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');

            // Site Visit Details
            $table->dateTime('visit_datetime')->nullable();
            $table->string('assigned_staff')->nullable();

            // Site Documentation
            $table->text('site_condition_notes')->nullable();
            $table->string('measurement_files')->nullable();

            // Client Requirements
            $table->text('space_details')->nullable();
            $table->text('materials_finishes')->nullable();
            $table->text('style_preferences')->nullable();
            $table->text('appliances_accessories')->nullable();

            // Client Preferences
            $table->string('brand_preferences')->nullable();
            $table->string('finish_preferences')->nullable();
            $table->enum('budget_sensitivity', ['High', 'Medium', 'Low'])->nullable();

            // Commercial Indicators
            $table->string('initial_cost_estimate')->nullable();
            $table->enum('approval_status', ['Yes', 'Hold'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_visits');
    }

};
