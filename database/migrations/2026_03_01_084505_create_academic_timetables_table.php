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
        $this->createMigrationTable('academic_timetables');
        $this->createMigrationTable('audit_academic_timetables', true);
    }

    public function createMigrationTable($schema_name, $is_audit = false){
          if (!Schema::hasTable($schema_name)) {
            Schema::create($schema_name, function (Blueprint $table) use ($is_audit) {
                $table->id();
                $table->string('name');
                $table->unsignedBigInteger('structure_id');
                $table->string('status');
                extraField($table, $is_audit);
                if ($is_audit) {
                    auditField($table);
                }
                if (!$is_audit) {
                    $table->foreign('structure_id')->references('id')->on('academic_structures')->restrictOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('academic_timetables');
        Schema::dropIfExists('audit_academic_timetables');
    }
};
