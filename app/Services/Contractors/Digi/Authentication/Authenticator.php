<?php

namespace App\Services\Contractors\Digi\Authentication;

class Authenticator extends AbstractAuthenticator
{
    public static function token(): string
    {
        return (new static())->getToken();
    }

    public function getToken(): string
    {
        return env('DIGI_TOKEN');
    }
}
