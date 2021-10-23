<?php

namespace App\Repositories\Users;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepository
{
    public function findByUuid(string $uuid): ?User;

    public function save(array $fields): ?User;

    public function findAllExcept(User $user): ?Collection;
}
