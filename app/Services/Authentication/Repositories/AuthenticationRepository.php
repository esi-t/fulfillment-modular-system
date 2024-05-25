<?php

namespace App\Services\Authentication\Repositories;

use App\Services\Panel\Models\User;
use App\Services\Panel\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class AuthenticationRepository implements AuthenticationServiceInterface
{
    public function login(?User $user, array $data)
    {
        //
    }

    public function adminLogin(?User $user, array $data)
    {
        //
    }
}
