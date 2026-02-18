<?php

use App\Models\User;

if (!function_exists('extraField')) {
    function extraField($table, $is_audit)
    {
        $table->foreignIdFor(User::class, 'created_by')->nullable();
        $table->foreignIdFor(User::class, 'update_by')->nullable();
        $table->foreignIdFor(User::class, 'deleted_by')->nullable();

        if (!$is_audit) {
            $table->foreign('created_by')->references('id')->on('users')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('update_by')->references('id')->on('users')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('deleted_by')->references('id')->on('users')->restrictOnDelete()->restrictOnUpdate();
        }
    }
}

if (!function_exists('auditField')) {
    function auditField($table)
    {
        $table->unsignedBigInteger('record_id')->comment('ID of the row in permissions table');

        // Action performed
        $table->string('action')->comment('CREATE / UPDATE / DELETE');

        // Who did the action
        $table->unsignedBigInteger('performed_by')->nullable()->comment('User ID who performed the action');

        // IP address of the actor
        $table->string('ip_address', 45)->nullable()->comment('IP address from where action was performed');

        // Old and new data
        $table->json('old_values')->nullable()->comment('Data before change');
        $table->json('new_values')->nullable()->comment('Data after change');
        // Optional FK for performed_by
        $table->foreign('performed_by')->references('id')->on('users')->nullOnDelete();
    }
}