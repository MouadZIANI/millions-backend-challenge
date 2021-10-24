<?php

namespace App\Repositories\Users;

use App\Models\User;
use Illuminate\Support\Collection;

class EloquentUserRepository implements UserRepository
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function findByUuid(string $uuid): ?User
    {
        return $this->user->newQuery()->firstWhere('uuid', $uuid);
    }

    public function save(array $fields): ?User
    {
        return $this->user->newQuery()->create($fields);
    }

    public function findAllExcept(User $user): ?Collection
    {
        return $this->user->newQuery()
            ->where('uuid', '!=', $user->uuid)
            ->get();
    }
}
