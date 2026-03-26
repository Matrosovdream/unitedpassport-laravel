<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\AbstractRepo;

class UserRepo extends AbstractRepo
{
    protected $withRelations = ['role'];

    public function __construct()
    {
        $this->model = new User();
    }

    public function getByEmail($email)
    {
        $item = $this->model
            ->where('email', $email)
            ->with($this->withRelations)
            ->first();

        return $this->mapItem($item);
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        return [
            'id' => $item->id,
            'name' => $item->name,
            'email' => $item->email,
            'user_login' => $item->user_login,
            'display_name' => $item->display_name,
            'user_status' => $item->user_status,
            'role_id' => $item->role_id,
            'role' => $item->relationLoaded('role') && $item->role ? [
                'id' => $item->role->id,
                'name' => $item->role->name,
                'slug' => $item->role->slug,
            ] : null,
            'created_at' => $item->created_at,
            'Model' => $item,
        ];
    }
}
