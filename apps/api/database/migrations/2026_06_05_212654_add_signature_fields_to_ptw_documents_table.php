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
        Schema::table('ptw_documents', function (Blueprint $table) {
            $table->string('manager_signature')->nullable();
            $table->timestamp('approved_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ptw_documents', function (Blueprint $table) {
            $table->dropColumn(['manager_signature', 'approved_at']);
        });
    }
};
