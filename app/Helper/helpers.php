<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        // Action performed
        $table->string('action')->comment('CREATE / UPDATE / DELETE');

        // Who did the action
        $table->unsignedBigInteger('performed_by')->nullable()->comment('User ID who performed the action');

        // IP address of the actor
        $table->string('ip_address', 45)->nullable()->comment('IP address from where action was performed');

        // Optional FK for performed_by
        $table->foreign('performed_by')->references('id')->on('users')->nullOnDelete();
    }
}

if (!function_exists('validateDate')) {
    function validateDate(array $data, array $rules)
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()]);
        }

        return true;
        dd($validator);
    }
}

if (!function_exists('auditTableEntry')) {
    function auditTableEntry(string $auditTableClass, array $data, string $action)
    {
        $data['action'] = $action;
        $data['performed_by'] = Auth::id();
        $data['ip_address'] = request()->ip();

        $table = new $auditTableClass(); // instantiate audit model
        return $table->create($data);    // save audit record
    }
}

if (!function_exists('auditTableEntry')) {
    function auditTableEntry(string $auditTableClass, array $data, string $action)
    {
        $data['action'] = $action;
        $data['performed_by'] = Auth::id();
        $data['ip_address'] = request()->ip();

        $table = new $auditTableClass(); // instantiate audit model
        return $table->create($data);    // save audit record
    }
}

if (!function_exists('auditTableEntry')) {
    function auditTableEntry(string $auditTableClass, array $data, string $action)
    {
        $data['action'] = $action;
        $data['performed_by'] = Auth::id();
        $data['ip_address'] = request()->ip();

        $table = new $auditTableClass(); // instantiate audit model
        return $table->create($data);    // save audit record
    }
}

if (!function_exists('auditTableEntry')) {
    function auditTableEntry(string $auditTableClass, array $data, string $action)
    {
        $data['action'] = $action;
        $data['performed_by'] = Auth::id();
        $data['ip_address'] = request()->ip();

        $table = new $auditTableClass(); // instantiate audit model
        return $table->create($data);    // save audit record
    }
}