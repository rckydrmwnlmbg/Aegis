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
        Schema::create('incidents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('status')->default('draft');
            $table->string('title')->nullable();
            $table->text('summary')->nullable();
            $table->json('metadata')->nullable(); // Store the full 5W1H extraction
            $table->integer('ai_confidence_score')->nullable();

            // Other fields from schema.md that are not strictly necessary for AI extraction test
            $table->string('incident_number')->nullable();
            $table->string('incident_type')->nullable();
            $table->uuid('classification_id')->nullable();
            $table->timestamp('occurred_at')->nullable();
            $table->timestamp('reported_at')->nullable();
            $table->uuid('reported_by')->nullable();
            $table->uuid('location_reference')->nullable();
            $table->uuid('project_reference')->nullable();
            $table->uuid('contractor_reference_id')->nullable();
            $table->string('severity_status')->nullable();
            $table->uuid('current_owner_id')->nullable();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
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
        Schema::dropIfExists('incidents');
    }
};
