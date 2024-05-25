<?php

namespace App\Services\Contractors\Snap\Authentication;

use App\Services\Contractors\Snap\Models\BasicInformation;
use App\Services\Contractors\Snap\Models\Mapper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Authenticator extends AbstractAuthenticator
{
    private int $expiresIn = 5400;

    public function __construct(int $storeId)
    {
         $this->tokenUrl = env('SNAP_TOKEN_URL');

        $this->storeId = $storeId;
    }

    public static function token(int $storeId): string
    {
        return (new static($storeId))->getToken();
    }


    public function getToken(): string
    {
        return Cache::remember(
            $this->cacheAccessKey(),
            $this->AccessLifetime(),
            fn() => $this->login()
        );
    }

    private function cacheAccessKey(): string
    {
        return 'snap:token:access:store:' . $this->storeId;
    }

    private function AccessLifetime(): int
    {
        return $this->expiresIn - 60;
    }

    protected function login(): string
    {
        $authenticationFields = $this->getAuthenticationFields();

        $response = Http::asForm()->post($this->tokenUrl, [
            'grant_type' => 'password',
            'scope' => 'automation',
            'username' => $authenticationFields['username'],
            'password' => $authenticationFields['password'],
            'client_id' => $authenticationFields['client_id'],
            'client_secret' => $authenticationFields['client_secret'],
        ]);

        if ($response->failed()) {
            Log::error('Generating token from scratch for snap failed. response is : ' . $response->json());

            throw new \Exception('Generating token from scratch for snap failed. response is : ' . $response->json());
        }

        $data = $response->json();

        $this->expiresIn = $data['expires_in'];

        return $data['access_token'];
    }

    private function getAuthenticationFields(): array
    {
        // return authentication
    }
}
