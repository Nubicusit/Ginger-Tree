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
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->string('quotation_no');
            $table->integer('version')->default(1);
            $table->decimal('amount', 12, 2);
            $table->decimal('discount', 12, 2)->nullable();
            $table->decimal('final_amount', 12, 2);
            $table->enum('status', ['Submitted', 'Negotiation', 'Approved', 'Rejected'])
                ->default('Submitted');
            $table->timestamp('submitted_at')->nullable();
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
