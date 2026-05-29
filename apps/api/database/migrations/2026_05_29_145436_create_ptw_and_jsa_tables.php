<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Permit Types
        Schema::create('permit_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('code');
            $table->string('name');
            $table->string('risk_class');
            $table->json('configuration_json')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        // 2. JSAs
        Schema::create('jsas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('jsa_number')->nullable();
            $table->string('title');
            $table->string('status')->default('draft');
            $table->uuid('prepared_by')->nullable();
            $table->uuid('project_reference')->nullable();
            $table->uuid('linked_permit_id')->nullable();
            $table->integer('version')->default(1);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('prepared_by')->references('id')->on('app_users')->onDelete('set null');
        });

        // 3. JSA Tasks
        Schema::create('jsa_tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('jsa_id');
            $table->integer('task_order');
            $table->text('description');
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('jsa_id')->references('id')->on('jsas')->onDelete('cascade');
        });

        // 4. JSA Hazards
        Schema::create('jsa_hazards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('jsa_task_id');
            $table->text('description');
            $table->integer('likelihood_score')->nullable();
            $table->integer('severity_score')->nullable();
            $table->integer('residual_score')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('jsa_task_id')->references('id')->on('jsa_tasks')->onDelete('cascade');
        });

        // 5. JSA Controls
        Schema::create('jsa_controls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('hazard_id');
            $table->string('control_type');
            $table->text('description');
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('hazard_id')->references('id')->on('jsa_hazards')->onDelete('cascade');
        });

        // 6. Permit To Works
        Schema::create('permit_to_works', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('permit_number')->nullable();
            $table->uuid('permit_type_id')->nullable();
            $table->uuid('jsa_id')->nullable();
            $table->string('status')->default('draft');
            $table->string('title');
            $table->text('work_scope')->nullable();
            $table->uuid('location_reference')->nullable();
            $table->string('contractor_reference_id')->nullable();
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->uuid('requested_by')->nullable();
            $table->uuid('current_owner_id')->nullable();
            $table->integer('version')->default(1);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('permit_type_id')->references('id')->on('permit_types')->onDelete('set null');
            $table->foreign('jsa_id')->references('id')->on('jsas')->onDelete('set null');
            $table->foreign('requested_by')->references('id')->on('app_users')->onDelete('set null');
            $table->foreign('current_owner_id')->references('id')->on('app_users')->onDelete('set null');
        });

        // Update jsas linked_permit_id
        Schema::table('jsas', function (Blueprint $table) {
            $table->foreign('linked_permit_id')->references('id')->on('permit_to_works')->onDelete('set null');
        });

        // 7. Permit Approvals
        Schema::create('permit_approvals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('permit_id');
            $table->integer('approval_stage')->default(1);
            $table->uuid('approver_id');
            $table->string('role_saat_menyetujui')->nullable();
            $table->string('decision'); // approved, rejected
            $table->text('decision_notes')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('permit_id')->references('id')->on('permit_to_works')->onDelete('cascade');
            $table->foreign('approver_id')->references('id')->on('app_users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('jsas', function (Blueprint $table) {
            $table->dropForeign(['linked_permit_id']);
        });

        Schema::dropIfExists('permit_approvals');
        Schema::dropIfExists('permit_to_works');
        Schema::dropIfExists('jsa_controls');
        Schema::dropIfExists('jsa_hazards');
        Schema::dropIfExists('jsa_tasks');
        Schema::dropIfExists('jsas');
        Schema::dropIfExists('permit_types');
    }
};
