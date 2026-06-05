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
        Schema::create('ptw_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('job_title');
            $table->string('location');
            $table->enum('work_type', ['hot_work', 'cold_work', 'confined_space', 'excavation']);
            $table->enum('status', ['draft', 'pending_review', 'approved', 'rejected', 'closed'])->default('draft');
            $table->uuid('applicant_id');
            $table->uuid('assessor_id')->nullable();
            $table->uuid('manager_id')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('applicant_id')->references('id')->on('app_users')->onDelete('cascade');
            $table->foreign('assessor_id')->references('id')->on('app_users')->onDelete('set null');
            $table->foreign('manager_id')->references('id')->on('app_users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ptw_documents');
    }
};
