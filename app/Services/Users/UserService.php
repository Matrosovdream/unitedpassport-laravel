<?php

namespace App\Services\Users;

use App\Repositories\User\UserRepo;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected UserRepo $userRepo,
    ) {}

    public function getAll(array $filter = [], int $perPage = 20, array $sorting = [])
    {
        return $this->userRepo->getAll($filter, $perPage, $sorting);
    }

    public function getById(int $id)
    {
        return $this->userRepo->getByID($id);
    }

    public function addUser(array $data): array
    {
        $data['password'] = Hash::make($data['password']);

        return $this->userRepo->create($data);
    }

    public function deleteUser(int $id): bool
    {
        $user = $this->userRepo->getByID($id);

        if (!$user) {
            return false;
        }

        if ($user['id'] === 1) {
            return false;
        }

        return $this->userRepo->delete($id);
    }

    public function updateUser(int $id, array $data): ?array
    {
        unset($data['email']);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $this->userRepo->update($id, $data);
    }
}
