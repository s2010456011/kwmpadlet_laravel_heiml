<?php

namespace Database\Seeders;

namespace Database\Seeders;
use App\Models\Padlet;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'Beobachter'],
            ['name' => 'Bearbeiter'],
            ['name' => 'Admin']
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
