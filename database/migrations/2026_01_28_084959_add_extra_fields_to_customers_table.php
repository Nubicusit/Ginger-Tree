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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('email')->nullable()->after('contact_no');
            $table->text('address')->nullable()->after('email');
            $table->string('customer_type')->nullable()->after('address');
            $table->string('industry')->nullable()->after('customer_type');
            $table->string('website')->nullable()->after('industry');
            $table->text('notes')->nullable()->after('website');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'address',
                'customer_type',
                'industry',
                'website',
                'notes',
            ]);
        });
    }
};
