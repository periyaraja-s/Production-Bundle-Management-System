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
        Schema::table('production_bundles', function (Blueprint $table) {
            $table->index(['deleted_at', 'production_date'], 'production_bundles_deleted_at_production_date_index');
        });

        Schema::table('buyers', function (Blueprint $table) {
            $table->index('buyer_name');
        });

        Schema::table('styles', function (Blueprint $table) {
            $table->index('style_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_bundles', function (Blueprint $table) {
            $table->dropIndex('production_bundles_deleted_at_production_date_index');
        });

        Schema::table('buyers', function (Blueprint $table) {
            $table->dropIndex(['buyer_name']);
        });

        Schema::table('styles', function (Blueprint $table) {
            $table->dropIndex(['style_no']);
        });
    }
};
