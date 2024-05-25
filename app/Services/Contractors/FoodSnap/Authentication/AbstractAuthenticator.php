<?php

namespace App\Services\Contractors\FoodSnap\Authentication;

abstract class AbstractAuthenticator
{
    abstract  public function getToken(): string;

    abstract  protected function login(): string;
}
