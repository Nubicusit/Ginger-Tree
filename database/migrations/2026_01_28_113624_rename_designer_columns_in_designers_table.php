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
        Schema::table('designers', function (Blueprint $table) {
            $table->renameColumn('name', 'designer_name');
            $table->renameColumn('email', 'designer_email');
            $table->renameColumn('contact_no', 'designer_no');
            $table->renameColumn('address', 'designer_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('designers', function (Blueprint $table) {
            $table->renameColumn('designer_name', 'name');
            $table->renameColumn('designer_email', 'email');
            $table->renameColumn('designer_no', 'contact_no');
        });
    }
};
