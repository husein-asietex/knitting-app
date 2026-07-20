<?php

namespace Database\Seeders;

use App\Models\Machines;
use App\Models\User;
use App\Models\Teams;
use App\Models\Shifts;
use App\Models\Roles;
use App\Models\Sections;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $sectionsData = [
            ['id' => 1, 'name' => 'Section A', 'description' => 'Description Section A'],
            ['id' => 2, 'name' => 'Section B', 'description' => 'Description Section B'],
            ['id' => 3, 'name' => 'Section C', 'description' => 'Description Section C'],
        ];

        foreach ($sectionsData as $data) {
            Sections::updateOrCreate(['id' => $data['id']], $data);
        }

        $rolesData = [
            ['id' => 1, 'name' => 'Superadmin'],
            ['id' => 2, 'name' => 'Admin'],
            ['id' => 3, 'name' => 'User'],
        ];

        foreach ($rolesData as $data) {
            Roles::updateOrCreate(['id' => $data['id']], $data);
        }

        $role1 = Roles::find(1);

        $teams = [
            ['id' => 1, 'name' => 'Team A'],
            ['id' => 2, 'name' => 'Team B'],
            ['id' => 3, 'name' => 'Team C'],
            ['id' => 4, 'name' => 'Team D'],
            ['id' => 5, 'name' => 'Team E'],
        ];

        foreach ($teams as $data) {
            Teams::updateOrCreate(['id' => $data['id']], $data);
        }
        
        $teams1 = Teams::find(1);

        $shiftsData = [
            ['id' => 1, 'name' => 'Shift Pagi',  'start_at' => '06:00', 'finished_at' => '14:00'],
            ['id' => 2, 'name' => 'Shift Siang', 'start_at' => '14:00', 'finished_at' => '22:00'],
            ['id' => 3, 'name' => 'Shift Malam', 'start_at' => '22:00', 'finished_at' => '06:00'],
        ];

        foreach ($shiftsData as $data) {
            Shifts::updateOrCreate(['id' => $data['id']], $data);
        }

        $shift1 = Shifts::find(1);

        User::updateOrCreate([
            'name'     => 'Super Admin',
            'username' => 'superadmin',
            'position' => 'IT',
            'email'    => 'super@admin.com',
            'password' => 'password',
            'role_id'  => $role1->id,
            'team_id'  => $teams1->id,
            'shift_id' => $shift1->id,
        ]);

        User::factory(25)->create();

        $machinesData = [
            [
                'name' => 'CM-01',
                'type' => 'circular',
                'brand' => 'Fukuhara',
                'gauge' => 28,
                'feeder_count' => 90,
                'cylinder_dia' => 30.00,
                'section_id' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'CM-02',
                'type' => 'circular',
                'brand' => 'Terrot',
                'gauge' => 28,
                'feeder_count' => 90,
                'cylinder_dia' => 30.00,
                'section_id' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'CM-05',
                'type' => 'circular',
                'brand' => 'Fukuhara',
                'gauge' => 24,
                'feeder_count' => 88,
                'cylinder_dia' => 34.00,
                'section_id' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($machinesData as $data) {
            Machines::create($data);
        }
    }
}