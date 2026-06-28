<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * @var list<array{name: string, email: string, role: string, phone: string|null}>
     */
    private const USERS = [
        [
            'name' => 'ShopEase Admin',
            'email' => 'admin@shopease.test',
            'role' => 'admin',
            'phone' => '01700000001',
        ],
        [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'customer',
            'phone' => '01700000002',
        ],
        [
            'name' => 'Rina A.',
            'email' => 'rina@example.com',
            'role' => 'customer',
            'phone' => '01700000003',
        ],
        [
            'name' => 'Karim H.',
            'email' => 'karim@example.com',
            'role' => 'customer',
            'phone' => '01700000004',
        ],
        [
            'name' => 'Sadia R.',
            'email' => 'sadia@example.com',
            'role' => 'customer',
            'phone' => '01700000005',
        ],
    ];

    /**
     * Seed demo admin and customer accounts.
     */
    public function run(): void
    {
        foreach (self::USERS as $user) {
            User::query()->updateOrCreate(
                ['email' => $user['email']],
                [
                    ...$user,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ],
            );
        }
    }
}
