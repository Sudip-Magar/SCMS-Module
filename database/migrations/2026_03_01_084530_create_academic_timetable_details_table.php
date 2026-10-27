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
        $this->createMigrationTable('academic_timetable_details');
        $this->createMigrationTable('audit_academic_timetable_details', true);
    }

     public function createMigrationTable($schema_name, $is_audit = false){
          if (!Schema::hasTable($schema_name)) {
            Schema::create($schema_name, function (Blueprint $table) use ($is_audit) {
                $table->id();
                $table->unsignedBigInteger('timetable_id');
                $table->unsignedBigInteger('daily_schedule_id')->nullable();
                $table->integer('period_no');
                $table->unsignedBigInteger('subject_id')->nullable();
                $table->unsignedBigInteger('teacher_id')->nullable();
                $table->string('type')->comment('break or class');
//                $table->string('status');
                extraField($table, $is_audit);
                if ($is_audit) {
                    auditField($table);
                }
                if (!$is_audit) {
                    $table->foreign('daily_schedule_id')->references('id')->on('academic_daily_schedules')->restrictOnDelete()->cascadeOnUpdate();
                    $table->foreign('timetable_id')->references('id')->on('academic_timetables')->restrictOnDelete()->cascadeOnUpdate();
                    $table->foreign('subject_id')->references('id')->on('academic_subjects')->restrictOnDelete()->cascadeOnUpdate();
                    $table->foreign('teacher_id')->references('id')->on('academic_teachers')->restrictOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('academic_timetable_details');
        Schema::dropIfExists('audit_academic_timetable_details');
    }
};
