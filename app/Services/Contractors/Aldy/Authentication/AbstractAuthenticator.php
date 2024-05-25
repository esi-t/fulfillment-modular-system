<?php

namespace App\Services\Contractors\Aldy\Authentication;

abstract class AbstractAuthenticator
{
    abstract  public function getToken(): string;

    abstract  protected function refresh(): string;

    abstract  protected function verify(string $token): bool;

    abstract  protected function login(): string;
}
