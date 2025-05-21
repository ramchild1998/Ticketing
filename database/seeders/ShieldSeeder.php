<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"SUPERADMIN","guard_name":"web","permissions":["view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_office","view_any_office","create_office","update_office","view_user","view_any_user","create_user","update_user","view_vendor","view_any_vendor","create_vendor","update_vendor","restore_office","restore_any_office","replicate_office","reorder_office","restore_user","restore_any_user","replicate_user","reorder_user","restore_vendor","restore_any_vendor","replicate_vendor","reorder_vendor","page_MyProfilePage","delete_office","delete_any_office","force_delete_office","force_delete_any_office","delete_user","delete_any_user","force_delete_user","force_delete_any_user","delete_vendor","delete_any_vendor","force_delete_vendor","force_delete_any_vendor"]},{"name":"OPERATOR","guard_name":"web","permissions":[]},{"name":"ADMIN","guard_name":"web","permissions":["view_office","view_any_office","create_office","update_office","view_user","view_any_user","create_user","update_user","view_vendor","view_any_vendor","create_vendor","update_vendor"]},{"name":"super_admin","guard_name":"web","permissions":["view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_office","view_any_office","create_office","update_office","view_user","view_any_user","create_user","update_user","view_vendor","view_any_vendor","create_vendor","update_vendor","restore_office","restore_any_office","replicate_office","reorder_office","restore_user","restore_any_user","replicate_user","reorder_user","restore_vendor","restore_any_vendor","replicate_vendor","reorder_vendor","page_MyProfilePage","delete_office","delete_any_office","force_delete_office","force_delete_any_office","delete_user","delete_any_user","force_delete_user","force_delete_any_user","delete_vendor","delete_any_vendor","force_delete_vendor","force_delete_any_vendor"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
