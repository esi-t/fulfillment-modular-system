<?php

namespace App\Services\Authentication\Repositories;

use App\Services\Panel\Models\User;

interface AuthenticationServiceInterface
{
    public function login(?User $user, array $data);

    public function adminLogin(?User $user, array $data);
}
