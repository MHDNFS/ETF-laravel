<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->index(['status', 'id'], 'idx_employees_status_id');
            $table->index(['branch', 'id'], 'idx_employees_branch_id');
            $table->index(['company', 'id'], 'idx_employees_company_id');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex('idx_employees_status_id');
            $table->dropIndex('idx_employees_branch_id');
            $table->dropIndex('idx_employees_company_id');
        });
    }
};
