<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin account
        User::firstOrCreate(
            ['email' => 'admin@supportops.com'],
            [
                'name'       => 'System Administrator',
                'password'   => Hash::make('Admin@1234'),
                'role'       => 'admin',
                'department' => 'IT Operations',
                'is_active'  => true,
            ]
        );

        // Support personnel
        $personnel = [
            ['name' => 'Alice Mensah',     'email' => 'alice@supportops.com',   'dept' => 'Server Operations'],
            ['name' => 'Bob Asante',       'email' => 'bob@supportops.com',     'dept' => 'Database Team'],
            ['name' => 'Carol Owusu',      'email' => 'carol@supportops.com',   'dept' => 'Network Monitoring'],
            ['name' => 'David Amoah',      'email' => 'david@supportops.com',   'dept' => 'API Services'],
            ['name' => 'Eva Boateng',      'email' => 'eva@supportops.com',     'dept' => 'SMS Gateway'],
            ['name' => 'Frank Acheampong', 'email' => 'frank@supportops.com',   'dept' => 'Security Operations'],
        ];

        foreach ($personnel as $person) {
            User::firstOrCreate(
                ['email' => $person['email']],
                [
                    'name'       => $person['name'],
                    'password'   => Hash::make('Support@1234'),
                    'role'       => 'support',
                    'department' => $person['dept'],
                    'is_active'  => true,
                ]
            );
        }

        $this->command->info('✅ Users seeded: 1 admin + 6 support personnel');
        $this->command->info('   Admin: admin@supportops.com / Admin@1234');
        $this->command->info('   Staff: alice@supportops.com / Support@1234');
    }
}
