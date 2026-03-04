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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->string('quotation_no')->unique();

            // ✅ JSON array for storing multiple items
            $table->json('items');

            $table->enum('status', ['Submitted', 'Negotiation','Approved', 'Rejected']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
