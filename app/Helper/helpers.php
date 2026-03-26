<?php

use App\Models\Numbering\AdmissionNumbering;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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

if (!function_exists('backedEnumAsArray')) {
    function backedEnumAsArray($enum)
    {
        return collect($enum)
            ->map(function ($item) {
                return [
                    'value' => $item->name,
                    'label' => $item->value,
                ];
            })
            ->values()
            ->all();
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

if (!function_exists('getAdmissionNumbering')) {
    function getAdmissionNumbering($id = null)
    {
        // Query either all or specific
        $query = AdmissionNumbering::query()->active();

        if ($id) {
            $query->where('id', $id);
        }

        $admissionNumbering = $query->get()
            ->map(function ($row) {
                $prefix = $row->prefix ?? '';
                $suffix = $row->suffix ?? '';

                // determine number index
                $numberIndex = $row->current > $row->start ? $row->current : $row->start;

                // pad number for admission_no
                $body = str_pad($numberIndex, $row->body_length, '0', STR_PAD_LEFT);

                $admissionNo = $prefix . $body . $suffix;

                return [
                    'admission_no' => $admissionNo,               // full admission number
                    'admission_numbering_index' => $numberIndex, // numeric index
                    'admission_numbering_id' => $row->id,                   // for select options
                ];
            });

        // if ID given, return single object
        if ($id) {
            return $admissionNumbering->first();
        }

        return $admissionNumbering;
    }
}

if (!function_exists('authorizeUserCheck')) {
    function authorizeUserCheck($permission)
    {
        $user = Auth::user();

        if ($user->username == 'admin@gmail.com') {
            return true;
        }

        if (is_string($permission)) {
            return $user->can($permission);
        }

        if (is_array($permission)) {
            foreach ($permission as $per) {
                if ($user->can($per)) {
                    return true;
                }
            }
        }

        return false;
    }
}

if (!function_exists('authorizeUserModal')) {
    function authorizeUserModal($permission)
    {
        $response = Gate::allowIf(function ($user) use ($permission) {
//            dd($user);
            if ($user->username == 'admin@gmail.com') {
                return true;
            }
            // Single permission
            if (is_string($permission)) {
                return $user->can($permission);
            }

            // Multiple permissions
            if (is_array($permission)) {
                foreach ($permission as $per) {
                    if ($user->can($per)) {
                        return true;
                    }
                }
            }

            return false; // fallback
        });

        return $response->allowed();
    }
}
