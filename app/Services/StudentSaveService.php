<?php

namespace App\Services;

use App\Events\AuditTableEntryEvent;
use App\Models\Numbering\AdmissionNumbering;
use App\Models\Student\Student;
use App\Models\Student\StudentAcademicStructure;
use App\Models\Student\StudentDocument;
use App\Models\Student\StudentGuardian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentSaveService
{
    public static function registerStudent($data, $studentId, $documents, $photo)
    {
        DB::beginTransaction();
        try {
            $result = self::saveStudent($data, $studentId, $documents, $photo);
            DB::commit();
            return $result;
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception->getMessage());
        }
    }

    public static function saveStudent($data, $studentId, $documents, $photo)
    {
//        dd($photo);
        $studentData = $data['studentData'];
        $structureForm = $data['structureForm'];
        $guardianForm = $data['guardianForm'];

        if ($studentId) {
            $studentData['updated_by'] = Auth::user()->id;
            $structureForm['updated_by'] = Auth::user()->id;
        } else {
            $studentData['created_by'] = Auth::user()->id;
            $structureForm['created_by'] = Auth::user()->id;
        }
        if ($photo) {
            $studentData['photo'] = $photo->store('students', 'public');
        }
        $studentNumbering = getAdmissionNumbering($studentData['admission_numbering_id']);
        $studentData['admission_no'] = $studentNumbering['admission_no'];
        $studentData['admission_numbering_index'] = $studentNumbering['admission_numbering_index'];
        $studentData['admission_numbering_id'] = $studentNumbering['admission_numbering_id'];

        $is_saved = Student::updateOrCreate(['id' => $studentId], $studentData);

        AuditTableEntryEvent::dispatch('students', $is_saved, $studentId ? 'edit' : 'create');

        if (!$studentId) {
            self::updateCurrentStudentNumbering($studentData['admission_numbering_id']);
        }

        if ($is_saved) {
            self::saveStructure($structureForm, $studentId, $is_saved->id);
            self::saveGuardian($guardianForm, $studentId, $is_saved->id);
            self::saveDocument($documents, $studentId, $is_saved->id);
            return true;
        }
        return false;
    }

    public static function updateCurrentStudentNumbering($numberingId)
    {
        $admissionNumbering = $numberingId ? AdmissionNumbering::find($numberingId) : AdmissionNumbering::first();

        $oldCurrentNUmber = $admissionNumbering['current'];
        $newCurrentNumber = $oldCurrentNUmber + 1;

        $is_updated = $admissionNumbering->update(['current' => $newCurrentNumber]);

        if($is_updated) {
            return true;
        }
        return false;
    }

    public static function saveStructure($data, $studentId, $save_id)
    {
        $data['student_id'] = $save_id;
        $is_saved = StudentAcademicStructure::updateOrCreate(['id' => $studentId], $data);
        AuditTableEntryEvent::dispatch('student_academic_structures', $is_saved, $studentId ? 'edit' : 'create');

        if ($is_saved) {
            return true;
        }

        return false;
    }

    public static function saveGuardian($data, $studentId, $saved_id)
    {
        foreach ($data as $guardian) {
            $guardian['student_id'] = $saved_id;

            if ($studentId) {
                $guardian['updated_by'] = Auth::user()->id;
            } else {
                $guardian['created_by'] = Auth::user()->id;
            }

            $is_saved = StudentGuardian::updateOrCreate(['id' => $studentId], $guardian);
            AuditTableEntryEvent::dispatch('student_guardians', $is_saved, $studentId ? 'edit' : 'create');
        }

        return true;
    }

    public static function saveDocument($documents, $studentId, $saved_id)
    {
        foreach ($documents as $document) {
            if ($document['file_path']) {
                if ($studentId) {
                    $document['updated_by'] = Auth::user()->id;
                } else {
                    $document['created_by'] = Auth::user()->id;
                }
                unset($document['preview']);
                unset($document['old_file']);

                $document['file_path'] = $document['file_path']->store('documents', 'public');
                $document['student_id'] = $saved_id;

                $is_saved = StudentDocument::create($document);
                AuditTableEntryEvent::dispatch('student_documents', $is_saved, $studentId ? 'edit' : 'create');

            }
        }

        return true;
    }
}
