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

        User::factory()->hasAdmin()->create([
            'loginId' => 'admin',
            'role' => Role::Admin
        ]);

        $user_factory = User::factory();

        for ($i = 0; $i < 100; $i++) { 
            if (2 % random_int(1, 3)) {
                $user_factory->hasEmployee()->create(['role' => Role::Employee]);
            } else {
                $user_factory->hasCustomer()->create(['role' => Role::Customer]);
            }
        }

        User::find(5)->delete();
        User::find(17)->delete();
    }
}
