<?php

namespace App\Repositories\Users;

use App\Models\User;

interface UserRepository
{
    public function findByUuid(string $uuid): ?User;

    public function save(array $fields): ?User;
}
