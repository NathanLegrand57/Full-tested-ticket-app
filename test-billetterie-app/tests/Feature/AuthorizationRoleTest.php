<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthorizationRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_role_assignment_grants_expected_abilities(): void
    {
        $user = User::factory()->create();

        Permission::findOrCreate('show-create', 'web');
        Permission::findOrCreate('show-edit', 'web');
        Permission::findOrCreate('show-delete', 'web');
        Permission::findOrCreate('admin-stats', 'web');

        $adminRole = Role::findOrCreate('admin', 'web');
        $adminRole->givePermissionTo([
            'show-create',
            'show-edit',
            'show-delete',
            'admin-stats',
        ]);

        $user->assignRole('admin');

        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($user->can('show-create'));
        $this->assertTrue($user->can('show-edit'));
        $this->assertTrue($user->can('show-delete'));
        $this->assertTrue($user->can('admin-stats'));
    }
}
