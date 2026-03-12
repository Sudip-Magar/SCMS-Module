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
        $studentData = $data['studentData'];
        $structureForm = $data['structureForm'];
        $guardianForm = $data['guardianForm'];

        if ($studentId) {
            $studentData['updated_by'] = Auth::user()->id;
            $structureForm['updated_by'] = Auth::user()->id;
        } else {
            $studentData['created_by'] = Auth::user()->id;
            $structureForm['created_by'] = Auth::user()->id;

            $studentNumbering = getAdmissionNumbering($studentData['admission_numbering_id']);
            $studentData['admission_no'] = $studentNumbering['admission_no'];
            $studentData['admission_numbering_index'] = $studentNumbering['admission_numbering_index'];
            $studentData['admission_numbering_id'] = $studentNumbering['admission_numbering_id'];
        }
        if ($photo) {
            $studentData['photo'] = $photo->store('students', 'public');
        } else {
            unset($studentData['photo']);
        }
        unset($studentData['old_photo']);

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

        if ($is_updated) {
            return true;
        }
        return false;
    }

    public static function saveStructure($data, $studentId, $save_id)
    {
        $data['student_id'] = $save_id;
        $is_saved = StudentAcademicStructure::updateOrCreate(['id' => $data['id']], $data);
        AuditTableEntryEvent::dispatch('student_academic_structures', $is_saved, $data['id'] ? 'edit' : 'create');

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

            $is_saved = StudentGuardian::updateOrCreate(['id' => $guardian['id']], $guardian);
            AuditTableEntryEvent::dispatch('student_guardians', $is_saved, $guardian['id'] ? 'edit' : 'create');
        }

        return true;
    }

    public static function saveDocument($documents, $studentId, $saved_id)
    {
        foreach ($documents as $document) {

            $file = $document['file_path'] ?? null;
            $oldFile = $document['old_file'] ?? null;

            unset($document['preview'], $document['old_file']);

            /*
            |--------------------------------------------------------------------------
            | CREATE DOCUMENT
            |--------------------------------------------------------------------------
            */

            if (empty($document['id'])) {

                if ($file) {

                    $document['created_by'] = Auth::id();
                    $document['student_id'] = $saved_id;
                    $document['file_path'] = $file->store('documents', 'public');

                    $model = StudentDocument::create($document);

                    AuditTableEntryEvent::dispatch('student_documents', $model, 'create');
                }

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE DOCUMENT
            |--------------------------------------------------------------------------
            */

            $model = StudentDocument::find($document['id']);

            if (!$model) {
                continue;
            }

            /*
            | Only update document_type
            */

            if ($oldFile) {

                $model->update([
                    'document_type' => $document['document_type'],
                    'updated_by' => Auth::id(),
                ]);

                AuditTableEntryEvent::dispatch('student_documents', $model, 'update');

                continue;
            }

            /*
            | Update with new file
            */

            if ($file) {

                $model->update([
                    'document_type' => $document['document_type'],
                    'file_path' => $file->store('documents', 'public'),
                    'student_id' => $saved_id,
                    'updated_by' => Auth::id(),
                ]);

                AuditTableEntryEvent::dispatch('student_documents', $model, 'update');

                continue;
            }

            /*
            | Remove file
            */

            $model->update([
                'document_type' => $document['document_type'],
                'file_path' => null,
                'updated_by' => Auth::id(),
            ]);

            AuditTableEntryEvent::dispatch('student_documents', $model, 'update');
        }

        return true;
    }
}
