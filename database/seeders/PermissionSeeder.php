<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionStructure = [
            'dashboard' => ['view'],
            'setup' => ['user', 'role', 'permission'],
            'academic_setup' => ['year', 'section', 'class', 'subject', 'faculty', 'level', 'room', 'structure', 'program'],
            'timetable_setup' => ['daily_schedule', 'timetable'],
            'student_setup' => ['student', 'admission_numbering'],
        ];

        $actions = ['list', 'create', 'edit', 'delete'];

        $data = [];

        foreach ($permissionStructure as $package => $subpackages) {
            foreach ($subpackages as $subpackage) {
                foreach ($actions as $action) {
                    $data[] = [
                        'name' => "{$package}-{$subpackage}-{$action}",
                        'package_name' => $package,
                        'sub_package_name' => $subpackage,
                        'guard_name' => 'web',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
        }

        DB::table('permissions')->insert($data);
        $this->command->info('Permissions seeded successfully!');
    }
}
