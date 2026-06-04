<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('attachments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->string('storage_provider');
            $table->string('storage_key');
            $table->string('media_type');
            $table->unsignedBigInteger('size_bytes');
            $table->string('checksum')->nullable();
            $table->uuid('created_by')->nullable();
            $table->string('status')->default('uploaded');
            $table->timestamps();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('app_users')->onDelete('set null');
        });
    }
    public function down(): void {
        Schema::dropIfExists('attachments');
    }
};
