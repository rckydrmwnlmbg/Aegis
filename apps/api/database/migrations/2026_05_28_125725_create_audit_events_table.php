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
        Schema::create('audit_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->nullable(); // nullable for platform level audits
            $table->string('domain')->default('core');
            $table->string('entity_type')->nullable();
            $table->uuid('entity_id')->nullable();
            $table->string('action_type');
            $table->uuid('actor_id')->nullable();
            $table->string('actor_type')->nullable();
            $table->timestamp('occurred_at');
            $table->uuid('correlation_id')->nullable();
            $table->json('metadata_json')->nullable();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_events');
    }
};
