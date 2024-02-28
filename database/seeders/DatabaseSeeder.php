<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enum\Rules;
use App\Models\Group;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::table('users')->insert([
            ['name' => 'SysAdmin', 'role' => Rules::ADMIN->value, 'email' => 'admin@mail.com'
                , 'password' => Hash::make('GCXx9B62BjJM29:'),
            ],
        ]);
        DB::table('groups')->insert(['name' => 'Public', 'isPublic' => 1,
            'owner_id' => 1, 'created_at' => now(), 'updated_at' => now()]);
    }
}
