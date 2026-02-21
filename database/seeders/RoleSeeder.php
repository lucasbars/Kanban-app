<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Cria as roles
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);

        // Cria as permissions
        Permission::create(['name' => 'ver todos os boards']);
        Permission::create(['name' => 'gerenciar boards']);

        // Admin tem todas as permissions
        $admin->givePermissionTo(['ver todos os boards', 'gerenciar boards']);

        // User só gerencia os próprios
        $user->givePermissionTo('gerenciar boards');
    }
}
