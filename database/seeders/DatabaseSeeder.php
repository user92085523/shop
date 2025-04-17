<?php

namespace Database\Seeders;

use App\Enums\Models\EmployeePosition\Name;
use App\Enums\Models\User\Role;
use App\Models\Employee;
use App\Models\EmployeePosition;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        EmployeePosition::factory(count(Name::cases()))->create();

        User::factory()->create([
            'loginId' => 'admin',
            'role' => Role::Admin
        ]);

        User::factory(20)->hasEmployee()->create(['role' => Role::Employee]);
    }
}
