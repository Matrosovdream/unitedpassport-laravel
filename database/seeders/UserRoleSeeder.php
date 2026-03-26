<?php

namespace Database\Seeders;

use App\Models\UserRole;
use App\Models\UserRoleRight;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    private const RIGHTS = [
        'user_add', 'user_edit', 'user_delete',
        'order_add', 'order_edit', 'order_delete',
        'page_add', 'page_edit', 'page_delete',
        'form_add', 'form_edit', 'form_delete',
        'payment_add', 'payment_edit', 'payment_delete',
        'shipment_add', 'shipment_edit', 'shipment_delete',
        'setting_edit',
        'migration_run',
    ];

    private const ADMIN_RIGHTS = self::RIGHTS;

    private const MANAGER_RIGHTS = [
        'order_add', 'order_edit', 'order_delete',
        'page_add', 'page_edit', 'page_delete',
        'form_add', 'form_edit', 'form_delete',
        'payment_add', 'payment_edit',
        'shipment_add', 'shipment_edit',
    ];

    private const EDITOR_RIGHTS = [
        'page_add', 'page_edit',
        'form_add', 'form_edit',
    ];

    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Full access. Not editable.',
                'is_editable' => false,
                'rights' => self::ADMIN_RIGHTS,
            ],
            [
                'name' => 'Admin 2',
                'slug' => 'admin2',
                'description' => 'Manager-level admin with order, page, form, payment and shipment access.',
                'rights' => self::MANAGER_RIGHTS,
            ],
            [
                'name' => 'Admin 3',
                'slug' => 'admin3',
                'description' => 'Editor-level admin with page and form access.',
                'rights' => self::EDITOR_RIGHTS,
            ],
            [
                'name' => 'Subscriber',
                'slug' => 'subscriber',
                'description' => 'No rights by default.',
                'rights' => [],
            ],
        ];

        foreach ($roles as $roleData) {
            $rights = $roleData['rights'];
            unset($roleData['rights']);

            $role = UserRole::updateOrCreate(
                ['slug' => $roleData['slug']],
                $roleData,
            );

            $role->rights()->delete();

            foreach ($rights as $code) {
                $role->rights()->create(['role_code' => $code]);
            }
        }
    }
}
