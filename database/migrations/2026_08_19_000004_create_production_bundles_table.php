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
        Schema::create('production_bundles', function (Blueprint $table) {
            $table->id();
            $table->string('bundle_no')->unique();
            $table->foreignId('buyer_id')->index()->constrained()->restrictOnDelete();
            $table->foreignId('style_id')->index()->constrained()->restrictOnDelete();
            $table->string('color', 100)->index();
            $table->string('size', 50);
            $table->foreignId('line_id')->index()->constrained('sewing_lines')->restrictOnDelete();
            $table->unsignedInteger('quantity')->default(0)->index();
            $table->unsignedInteger('completed_qty')->default(0);
            $table->unsignedInteger('rejected_qty')->default(0);
            $table->string('operator_name', 150)->nullable()->index();
            $table->date('production_date')->index();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_bundles');
    }
};
