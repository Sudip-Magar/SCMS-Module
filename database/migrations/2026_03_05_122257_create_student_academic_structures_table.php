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
        $this->createMigrationTable('student_academic_structures');
        $this->createMigrationTable('audit_student_academic_structures', true);
    }

    public function createMigrationTable($schema_name, $is_audit = false)
    {
        if (!Schema::hasTable($schema_name)) {
            Schema::create($schema_name, function (Blueprint $table) use ($is_audit) {
                $table->id();
                $table->unsignedBigInteger('student_id');
                $table->unsignedBigInteger('academic_structure_id')->nullable();
                $table->integer('roll_no')->nullable();
                $table->string('symbol_no')->nullable();
                $table->string('registration_no')->nullable;
                extraField($table, $is_audit);
                if ($is_audit) {
                    auditField($table);
                }
                if (!$is_audit) {
                    $table->foreign('student_id')->references('id')->on('students')->restrictOnDelete()->cascadeOnUpdate();
                    $table->foreign('academic_structure_id')->references('id')->on('academic_structures')->restrictOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('student_academic_structures');
        Schema::dropIfExists('audit_student_academic_structures');
    }
};
