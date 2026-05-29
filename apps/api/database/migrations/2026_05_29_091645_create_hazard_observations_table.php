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
        Schema::create('hazard_observations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('status')->default('draft');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('category_name')->nullable();
            $table->string('risk_score')->nullable();
            $table->integer('ai_confidence_score')->nullable();
            $table->json('metadata')->nullable();

            $table->string('hazard_number')->nullable();
            $table->uuid('category_id')->nullable();
            $table->string('severity_level')->nullable();
            $table->timestamp('observed_at')->nullable();
            $table->uuid('observed_by')->nullable();
            $table->uuid('location_reference')->nullable();
            $table->uuid('contractor_reference_id')->nullable();
            $table->uuid('owner_id')->nullable();
            $table->integer('version')->default(1);

            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hazard_observations');
    }
};
