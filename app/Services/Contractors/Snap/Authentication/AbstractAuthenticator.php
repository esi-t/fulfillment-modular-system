<?php

namespace App\Services\Contractors\Snap\Authentication;

abstract class AbstractAuthenticator
{
    abstract  public function getToken(): string;

    abstract  protected function login(): string;
}
