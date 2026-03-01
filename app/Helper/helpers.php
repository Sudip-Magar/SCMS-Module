<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

if (!function_exists('extraField')) {
    function extraField($table, $is_audit)
    {
        $table->foreignIdFor(User::class, 'created_by')->nullable();
        $table->foreignIdFor(User::class, 'updated_by')->nullable();
        $table->foreignIdFor(User::class, 'deleted_by')->nullable();

        if (!$is_audit) {
            $table->foreign('created_by')->references('id')->on('users')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('updated_by')->references('id')->on('users')->restrictOnDelete()->restrictOnUpdate();
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

if (!function_exists('validateField')) {
    function validateField(array $data, array $rules)
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()]);
        }

        return [];
    }
}


if (!function_exists('EngToNpNumberConverter')) {
    function EngToNpNumberConverter($number)
    {
        $enNumber = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $npNumber = ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'];
        if (session('locale') == 'np') {
            // dd(session('locale'));
            // dd(str_replace($enNumber, $npNumber, $number));
            return str_replace($enNumber, $npNumber, $number);
        }
        // return str_replace($enNumber, $enNumber, $number);
    }
}