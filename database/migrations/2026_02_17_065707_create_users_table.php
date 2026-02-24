<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $this->createMigrationTable('users');
        $this->createMigrationTable('audit_user', true);
    }

    public function createMigrationTable($schema_name, $is_audit = false)
    {
        if (!Schema::hasTable($schema_name)) {
            Schema::create($schema_name, function (Blueprint $table) use ($is_audit) {
                $table->id();
                $table->string('username')->unique();
                $table->string('user_type')->nullable();
                $table->string('short_name');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('role_id')->nullable();
                $table->string('password');
                $table->string('status');
                extraField($table, $is_audit);
                $table->timestamp('email_verified_at')->nullable();
                $table->rememberToken();
                if(!$is_audit) {
                    $table->foreign('role_id')->references('id')->on('roles')->restrictOnDelete()->restrictOnUpdate();
                }

                if($is_audit) {
                    auditField($table);
                }
                $table->timestamps();
            });
        }

        if (!$is_audit) {
            if (!Schema::hasTable('sessions')) {
                Schema::create('sessions', function (Blueprint $table) {
                    $table->string('id')->primary();
                    $table->foreignId('user_id')->nullable()->index();
                    $table->string('ip_address', 45)->nullable();
                    $table->text('user_agent')->nullable();
                    $table->longText('payload');
                    $table->integer('last_activity')->index();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('audit_users');
        Schema::dropIfExists('sessions');
    }
};
