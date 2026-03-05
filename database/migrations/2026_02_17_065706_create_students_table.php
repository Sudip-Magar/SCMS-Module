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
        $this->createMigrationTable('students');
        $this->createMigrationTable('audit_students', true);
    }

    public function createMigrationTable($schema_name, $is_audit = false)
    {
        if (!Schema::hasTable($schema_name)) {
            Schema::create($schema_name, function (Blueprint $table) use ($is_audit) {
                $table->id();
                $table->string('admission_no');
                $table->string('first_name');
                $table->string('middle_name')->nullable();
                $table->string('last_name');
                $table->string('photo')->nullable();
                $table->string('gender');
                $table->date('date_of_birth_en');
                $table->string('date_of_birth_np');
                $table->string('email');
                $table->bigInteger('phone');
                $table->unsignedBigInteger('province_id');
                $table->unsignedBigInteger('district_id');
                $table->string('city');
                $table->integer('ward_no');
                $table->date('admission_date_en');
                $table->string('admission_date_np');
                $table->string('status');
                extraField($table, $is_audit);
                if ($is_audit) {
                    auditField($table);
                }

                if (!$is_audit) {
                    $table->foreign('province_id')->references('id')->on('provinces')->restrictOnDelete()->cascadeOnUpdate();
                    $table->foreign('district_id')->references('id')->on('districts')->restrictOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('students');
        Schema::dropIfExists('audit_students');
    }
};
