<?php

namespace App\Services\Users;

use App\Repositories\User\UserRoleRepo;
use App\Repositories\User\UserRoleRightRepo;

class UserRoleService
{
    public function __construct(
        protected UserRoleRepo $roleRepo,
        protected UserRoleRightRepo $rightRepo,
    ) {}

    public function getAll(array $filter = [], int $perPage = 20, array $sorting = [])
    {
        return $this->roleRepo->getAll($filter, $perPage, $sorting);
    }

    public function getById(int $id)
    {
        return $this->roleRepo->getByID($id);
    }

    public function addRole(array $data, array $rights = []): array
    {
        $role = $this->roleRepo->create($data);

        $this->syncRights($role['id'], $rights);

        return $this->roleRepo->getByID($role['id']);
    }

    public function deleteRole(int $id): bool
    {
        $role = $this->roleRepo->getByID($id);

        if (!$role || !$role['Model']->is_editable) {
            return false;
        }

        return $this->roleRepo->delete($id);
    }

    public function updateRole(int $id, array $data, array $rights = []): ?array
    {
        $role = $this->roleRepo->getByID($id);

        if (!$role || !$role['Model']->is_editable) {
            return null;
        }

        $this->roleRepo->update($id, $data);
        $this->syncRights($id, $rights);

        return $this->roleRepo->getByID($id);
    }

    public function getAllRights(): array
    {
        return [
            'user_add', 'user_edit', 'user_delete',
            'order_add', 'order_edit', 'order_delete',
            'page_add', 'page_edit', 'page_delete',
            'form_add', 'form_edit', 'form_delete',
            'payment_add', 'payment_edit', 'payment_delete',
            'shipment_add', 'shipment_edit', 'shipment_delete',
            'setting_edit',
            'migration_run',
        ];
    }

    protected function syncRights(int $roleId, array $rights): void
    {
        $this->rightRepo->getModel()
            ->where('user_role_id', $roleId)
            ->delete();

        foreach ($rights as $code) {
            $this->rightRepo->create([
                'user_role_id' => $roleId,
                'role_code' => $code,
            ]);
        }
    }
}
