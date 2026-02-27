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
        $this->createMigrationTable('academic_structures');
        $this->createMigrationTable('audit_academic_structures', true);
    }

    public function createMigrationTable($schema_name, $is_audit = false)
    {
        if (!Schema::hasTable($schema_name)) {
            Schema::create($schema_name, function (Blueprint $table) use ($is_audit) {
                $table->id();
                $table->string('name');
                $table->string('education_level');
                $table->unsignedBigInteger(column: 'academic_year_id');
                $table->unsignedBigInteger(column: 'program_id')->nullable();
                $table->unsignedBigInteger(column: 'faculty_id')->nullable();
                $table->unsignedBigInteger(column: 'level_id');
                $table->unsignedBigInteger(column: 'room_id');
                $table->unsignedBigInteger(column: 'section_id')->nullable();
                $table->string(column: 'academic_system')->comment('Semester or Year');
                $table->string('status');
                extraField($table, $is_audit);
                if ($is_audit) {
                    auditField($table);
                }

                if (!$is_audit) {
                    $table->foreign('academic_year_id')->references('id')->on('academic_years')->restrictOnDelete()->cascadeOnUpdate();
                    $table->foreign('program_id')->references('id')->on('academic_programs')->restrictOnDelete()->cascadeOnUpdate();
                    $table->foreign('faculty_id')->references('id')->on('academic_faculties')->restrictOnDelete()->cascadeOnUpdate();
                    $table->foreign('level_id')->references('id')->on('academic_levels')->restrictOnDelete()->cascadeOnUpdate();
                    $table->foreign('room_id')->references('id')->on('academic_rooms')->restrictOnDelete()->cascadeOnUpdate();
                    $table->foreign('section_id')->references('id')->on('academic_sections')->restrictOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('academic_structure');
        Schema::dropIfExists('audit_academic_structure');
    }
};
