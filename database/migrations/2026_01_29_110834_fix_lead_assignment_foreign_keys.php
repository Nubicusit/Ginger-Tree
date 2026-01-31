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
        Schema::table('leads', function (Blueprint $table) {

        // drop wrong foreign keys
        $table->dropForeign(['sales_executive_id']);
        $table->dropForeign(['designer_id']);

        // add correct foreign keys
        $table->foreign('sales_executive_id')
              ->references('id')
              ->on('sales_executives')
              ->nullOnDelete();

        $table->foreign('designer_id')
              ->references('id')
              ->on('designers')
              ->nullOnDelete();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {

        $table->dropForeign(['sales_executive_id']);
        $table->dropForeign(['designer_id']);

        $table->foreign('sales_executive_id')
              ->references('id')
              ->on('users');

        $table->foreign('designer_id')
              ->references('id')
              ->on('users');
    });
    }
};
