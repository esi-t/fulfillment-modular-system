<?php

namespace App\Services\Contractors\FoodSnap\Authentication;

use App\Services\Contractors\FoodSnap\Models\BasicInformation;
use App\Services\Contractors\FoodSnap\Models\Mapper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Authenticator extends AbstractAuthenticator
{
    private int $expiresIn = 5400;

    public function __construct(int $storeId)
    {
         $this->tokenUrl = env('FOOD_SNAP_TOKEN_URL');

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
        return 'food-snap:token:access:store:' . $this->storeId;
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
            Log::error('Generating token from scratch for foodsnap failed. response is : ' . $response->json());

            throw new \Exception('Generating token from scratch for foodsnap failed. response is : ' . $response->json());
        }

        $data = $response->json();

        $this->expiresIn = $data['expires_in'];

        return $data['access_token'];
    }

    private function getAuthenticationFields(): array
    {
        $authenticationFields = BasicInformation::query()
            ->selectRaw(
                'Client_ID as client_id,
                 Client_secret as client_secret,
                  UserName as username,
                   Password as password'
            )
            ->where('RetaileStoreID', Mapper::getRetailBySap($this->storeId))
            ->firstOr(fn () => throw new \Exception('Could not find credentials for food-snap token'))
            ->toArray();

        return array_merge($authenticationFields, [
            'scope' => 'automation',
            'grant_type' => 'password'
        ]);
    }

    public function unsetToken(): void
    {
        Cache::forget($this->cacheAccessKey());
    }

    public static function forgetToken(int $storeId): void
    {
        (new static($storeId))->unsetToken();
    }
}
