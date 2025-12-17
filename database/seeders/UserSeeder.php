<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin principal
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@haskellcosmeticos.com.br',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Gerente
        User::create([
            'name' => 'Gerente Sistema',
            'email' => 'gerente@haskellcosmeticos.com.br',
            'password' => Hash::make('gerente123'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Suporte
        User::create([
            'name' => 'Suporte Técnico',
            'email' => 'suporte@haskellcosmeticos.com.br',
            'password' => Hash::make('suporte123'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);
    }
}
