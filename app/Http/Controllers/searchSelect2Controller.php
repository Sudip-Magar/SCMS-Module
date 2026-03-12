<?php

namespace App\Http\Controllers;

use App\Models\AcademicSetup\AcademicFaculty;
use App\Models\AcademicSetup\AcademicLevel;
use App\Models\AcademicSetup\AcademicProgram;
use App\Models\AcademicSetup\AcademicRoom;
use App\Models\AcademicSetup\AcademicSection;
use App\Models\AcademicSetup\AcademicStructure;
use App\Models\AcademicSetup\AcademicYear;
use App\Models\Address\District;
use App\Models\Address\Province;
use Illuminate\Http\Request;

class searchSelect2Controller extends Controller
{
    public function __invoke(Request $request)
    {
        $results = $this->{$this->redirectModules($request->get('module'))}($request);
        return ['results' => $results];
    }

    public function redirectModules($modules)
    {
        return match ($modules) {
            'get_academic_year' => 'getAcademicYear',
            'get_academic_program' => 'getAcademicProgram',
            'get_academic_faculty' => 'getAcademicFaculty',
            'get_academic_level' => 'getAcademicLevel',
            'get_academic_room' => 'getAcademicRoom',
            'get_academic_section' => 'getAcademicSection',
            'get_academic_structure' => 'getAcademicStructure',
            'get_province' => 'getProvince',
            'get_district' => 'getDistrict',
        };
    }

    public function getAcademicYear(Request $request)
    {
        return AcademicYear::query()
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('term', '') . '%');
            })
            ->active()
            ->limit(10)
            ->get(['id', 'name as text']);
    }

    public function getAcademicProgram(Request $request)
    {
        return AcademicProgram::query()
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('term', '') . '%');
            })
            ->active()
            ->limit(10)
            ->get(['id', 'name as text']);
    }

    public function getAcademicFaculty(Request $request)
    {
        return AcademicFaculty::query()
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('term', '') . '%');
            })
            ->active()
            ->limit(10)
            ->get(['id', 'name as text']);
    }

    public function getAcademicLevel(Request $request)
    {
        return AcademicLevel::query()
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('term', '') . '%');
            })
            ->active()
            ->limit(10)
            ->get(['id', 'name as text']);
    }

    public function getAcademicRoom(Request $request)
    {
        return AcademicRoom::query()
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('term', '') . '%');
            })
            ->active()
            ->limit(10)
            ->get(['id', 'name as text']);
    }

    public function getAcademicSection(Request $request)
    {
        return AcademicSection::query()
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('term', '') . '%');
            })
            ->active()
            ->limit(10)
            ->get(['id', 'name as text']);
    }

    public function getAcademicStructure(Request $request)
    {
        return AcademicStructure::query()
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('term', '') . '%');
            })
            ->active()
            ->limit(10)
            ->get(['id', 'name as text']);
    }
    public function getProvince(Request $request)
    {
        return Province::query()
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('term', '') . '%');
            })
            ->limit(10)
            ->get(['id', 'name as text']);
    }

    public function getDistrict(Request $request)
{
    return District::query()
        ->when($request->filled('selected_id'), function ($query) use ($request) {
            $query->where('province_id', $request->input('selected_id'));
        })
        ->when($request->filled('term'), function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->input('term') . '%');
        })
//        ->limit(10)
        ->get(['id', 'name as text']);
}
}
