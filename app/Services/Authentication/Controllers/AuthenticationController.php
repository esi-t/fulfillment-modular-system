<?php

namespace App\Services\Authentication\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Authentication\Repositories\AuthenticationServiceInterface;
use App\Services\Authentication\Requests\AuthenticationRequest;
use App\Services\Panel\Models\User;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    // TODO : Fix responses, make a method, then fix the statuses, for error use 400 or 500
    public function __construct(private readonly AuthenticationServiceInterface $authenticationService)
    {
    }

    public function login(AuthenticationRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::query()
            ->where('username', $validatedData['username'])
            ->first();

        return $this->authenticationService->login($user, $validatedData);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'logout successfully',
            'data' => [],
            'meta' => [],
        ]);
    }

    public function adminLogin(AuthenticationRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::query()
            ->where('username', $validatedData['username'])
            ->where('role', User::ADMIN)
            ->firstOrFail();

        return $this->authenticationService->adminLogin($user, $validatedData);
    }
}
