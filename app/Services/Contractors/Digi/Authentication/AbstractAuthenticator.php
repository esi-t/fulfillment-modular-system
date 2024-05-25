<?php

namespace App\Services\Contractors\Digi\Authentication;

abstract class AbstractAuthenticator
{
    abstract  public function getToken(): string;
}
