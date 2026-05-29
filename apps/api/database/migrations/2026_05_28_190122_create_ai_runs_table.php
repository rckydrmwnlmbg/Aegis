<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('ai_runs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->uuid('actor_id')->nullable();
            $table->string('use_case');
            $table->string('provider_reference')->nullable();
            $table->string('workflow_domain')->nullable();
            $table->uuid('workflow_entity_id')->nullable()->index();
            $table->json('payload')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('occurred_at')->useCurrent();
            $table->uuid('correlation_id')->nullable()->index();
            $table->timestamps();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('actor_id')->references('id')->on('app_users')->onDelete('set null');

            // Required for Idempotency logic scope to uniquely identify across workflows per tenant
            $table->unique(['tenant_id', 'workflow_entity_id', 'use_case'], 'ai_runs_idempotency_unique');
        });
    }
    public function down(): void {
        Schema::dropIfExists('ai_runs');
    }
};
