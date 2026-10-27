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
        $this->createMigrationTable('admission_numberings');
        $this->createMigrationTable('audit_admission_numberings', true);
    }

    public function createMigrationTable($schema_name, $is_audit = false)
    {
        if (!Schema::hasTable($schema_name)) {
            Schema::create($schema_name, function (Blueprint $table) use ($is_audit) {
                    $table->id();
                    $table->string('academic_level');
                    $table->string('prefix');
                    $table->string('suffix');
                    $table->integer('start');
                    $table->integer('current');
                    $table->integer('body_length');
                    $table->integer('total_length');
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
        Schema::dropIfExists('admission_numberings');
        Schema::dropIfExists('audit_admission_numberings');
    }
};
