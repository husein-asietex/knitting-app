<?php

namespace Database\Seeders;

use App\Models\Machines;
use App\Models\MachineOperators;
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
            ['name' => 'Section A', 'description' => 'Description Section A'],
            ['name' => 'Section B', 'description' => 'Description Section B'],
            ['name' => 'Section C', 'description' => 'Description Section C'],
        ];

        foreach ($sectionsData as $data) {
            Sections::firstOrCreate(['name' => $data['name']], $data);
        }

        $rolesData = [
            ['name' => 'Superadmin'],
            ['name' => 'Admin'],
            ['name' => 'User'],
        ];

        foreach ($rolesData as $data) {
            Roles::firstOrCreate(['name' => $data['name']], $data);
        }

        $role1 = Roles::find(1);

        $teams = [
            ['name' => 'Team A'],
            ['name' => 'Team B'],
            ['name' => 'Team C'],
            ['name' => 'Team D'],
            ['name' => 'Team E'],
        ];

        foreach ($teams as $data) {
            Teams::firstOrCreate(['name' => $data['name']], $data);
        }
        
        $teams1 = Teams::find(1);

        $shiftsData = [
            ['name' => 'Shift Pagi',  'start_at' => '06:00', 'finished_at' => '14:00'],
            ['name' => 'Shift Siang', 'start_at' => '14:00', 'finished_at' => '22:00'],
            ['name' => 'Shift Malam', 'start_at' => '22:00', 'finished_at' => '06:00'],
        ];

        foreach ($shiftsData as $data) {
            Shifts::firstOrCreate(['name' => $data['name']], $data);
        }

        $shift1 = Shifts::find(1);

        User::create([
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

        MachineOperators::factory(25)->create();

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