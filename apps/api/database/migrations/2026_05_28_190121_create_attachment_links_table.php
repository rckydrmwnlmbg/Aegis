<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('attachment_links', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->uuid('attachment_id')->index();
            $table->string('domain');
            $table->string('entity_type');
            $table->uuid('entity_id')->index();
            $table->string('linkage_type')->nullable();
            $table->timestamp('linked_at')->useCurrent();
            $table->uuid('linked_by')->nullable();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('attachment_id')->references('id')->on('attachments')->onDelete('cascade');
            $table->foreign('linked_by')->references('id')->on('app_users')->onDelete('set null');
            $table->unique(['attachment_id', 'entity_type', 'entity_id', 'linkage_type'], 'attachment_link_unique');
        });
    }
    public function down(): void {
        Schema::dropIfExists('attachment_links');
    }
};
