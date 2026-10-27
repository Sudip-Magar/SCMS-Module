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
        $this->createMigrationTable('student_attendances');
        $this->createMigrationTable('audit_student_attendances', true);
    }

    public function createMigrationTable($schema_name, $is_audit = false)
    {
        if (!Schema::hasTable($schema_name)) {
            Schema::create($schema_name, function (Blueprint $table) use ($is_audit) {
                $table->id();
                $table->unsignedBigInteger('student_id');
                $table->unsignedBigInteger('timetable_detail_id');
                $table->date('attendance_date');
                $table->boolean('is_present');
                $table->string('remark')->nullable()->comment('late reason');
                extraField($table, $is_audit);
                if ($is_audit) {
                    auditField($table);
                }
                if (!$is_audit) {
                    $table->foreign('student_id')->references('id')->on('students')->restrictOnDelete()->cascadeOnUpdate();
                    $table->foreign('timetable_detail_id')->references('id')->on('academic_timetable_details')->restrictOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('student_attendances');
        Schema::dropIfExists('audit_student_attendances');
    }
};
