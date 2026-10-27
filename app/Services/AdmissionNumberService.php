<?php

namespace App\Services;

use App\Models\Numbering\AdmissionNumbering;
use Illuminate\Support\Facades\DB;
use Exception;

class AdmissionNumberService
{
    public function generate($numberingId)
    {
        return DB::transaction(function () use ($numberingId) {

            $numbering = AdmissionNumbering::lockForUpdate()
                ->find($numberingId);

            if (!$numbering) {
                throw new Exception("Admission numbering not found");
            }

            $index = $numbering->current + 1;

            $body = str_pad(
                $index,
                $numbering->body_length,
                '0',
                STR_PAD_LEFT
            );

            $admissionNo = $numbering->prefix . $body . $numbering->suffix;

            $numbering->update([
                'current' => $index
            ]);

            return [
                'admission_no' => $admissionNo,
                'admission_numbering_index' => $index,
                'admission_numbering_id' => $numbering->id
            ];
        });
    }
}