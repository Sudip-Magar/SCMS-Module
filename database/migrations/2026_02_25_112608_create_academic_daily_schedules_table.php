<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->createMigrationTable('academic_daily_schedules');
        $this->createMigrationTable('audit_academic_daily_schedules', true);
    }

    public function createMigrationTable($schema_name, $is_audit = false)
    {
        if (!Schema::hasTable($schema_name)) {
            Schema::create($schema_name, function (Blueprint $table) use ($is_audit) {
                $table->id();
                $table->string('academic_level');
                $table->string(column: 'day');
                $table->integer('total_period');
                $table->string('shift');
                $table->string('status');
                extraField($table, $is_audit);
                if ($is_audit) {
                    auditField($table);
                }
                if (!$is_audit) {
                    $table->unique(['academic_level', 'day', 'total_period']);
                }
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_daily_schedules');
        Schema::dropIfExists('audit_academic_daily_schedules');
    }
};
