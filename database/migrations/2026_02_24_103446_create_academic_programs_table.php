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
        $this->createMigrationTsble('academic_programs');
        $this->createMigrationTsble('audit_academic_programs', true);
    }

    public function createMigrationTsble($schema_name, $is_audit = false)
    {
        if (!Schema::hasTable($schema_name)) {
            Schema::create($schema_name, function (Blueprint $table) use ($is_audit) {
                $table->id();
                $table->string('name');
                $table->string('short_name');
                $table->string('status');
                extraField($table, $is_audit);
                if ($is_audit) {
                    auditField($table);
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
        Schema::dropIfExists('academic_programs');
        Schema::dropIfExists('audit_academic_programs');
    }
};
