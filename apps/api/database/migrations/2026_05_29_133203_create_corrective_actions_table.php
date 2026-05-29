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
        Schema::create('corrective_actions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('capa_number')->nullable();
            $table->string('action_type')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();

            // Polymorphic source relation (e.g. Incident, AuditFinding, HazardObservation)
            $table->uuid('source_id')->nullable();
            $table->string('source_type')->nullable();
            $table->index(['source_type', 'source_id']);

            // Assignment and references
            $table->uuid('owner_id')->nullable();
            $table->uuid('site_id')->nullable();
            $table->uuid('project_id')->nullable();

            $table->timestamp('due_date')->nullable();
            $table->string('status')->default('open');
            $table->integer('version')->default(1);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('owner_id')->references('id')->on('app_users')->onDelete('set null');
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('set null');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corrective_actions');
    }
};
