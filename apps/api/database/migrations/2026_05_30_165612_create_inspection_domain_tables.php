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
        Schema::create('inspection_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('app_users')->onDelete('set null');
        });

        Schema::create('inspection_template_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->uuid('template_id')->index();
            $table->text('question_text');
            $table->enum('response_type', ['yes_no', 'text', 'numeric', 'photo']);
            $table->boolean('is_required')->default(true);
            $table->integer('order_index');
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('template_id')->references('id')->on('inspection_templates')->onDelete('cascade');
        });

        Schema::create('inspections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->uuid('template_id')->index();
            $table->uuid('site_id')->nullable()->index();
            $table->uuid('conducted_by')->nullable()->index();
            $table->enum('status', ['draft', 'in_progress', 'completed'])->default('draft');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('template_id')->references('id')->on('inspection_templates')->onDelete('cascade');
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('set null');
            $table->foreign('conducted_by')->references('id')->on('app_users')->onDelete('set null');
        });

        Schema::create('inspection_responses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->uuid('inspection_id')->index();
            $table->uuid('template_item_id')->index();
            $table->text('response_value')->nullable();
            $table->boolean('response_boolean')->nullable();
            $table->uuid('attachment_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('inspection_id')->references('id')->on('inspections')->onDelete('cascade');
            $table->foreign('template_item_id')->references('id')->on('inspection_template_items')->onDelete('cascade');
            $table->foreign('attachment_id')->references('id')->on('attachments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_responses');
        Schema::dropIfExists('inspections');
        Schema::dropIfExists('inspection_template_items');
        Schema::dropIfExists('inspection_templates');
    }
};
