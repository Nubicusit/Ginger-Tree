<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class HrUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'hr@steelsheetmart.com',
            ],
            [
                'name' => 'HR Administrator',
                'password' => Hash::make('hr@123'),
                'status' => 1,
                'role' => 'hr',
            ]
        );
    }
}
