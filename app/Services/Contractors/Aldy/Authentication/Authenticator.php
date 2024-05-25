<?php

namespace App\Services\Contractors\Aldy\Authentication;

use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Contractors\Base\Models\Authentication;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Authenticator extends AbstractAuthenticator
{
    private string $baseUrl;

    private string $username;

    private string $password;

    private int $storeId;

    public function __construct(int $storeId)
    {
         $this->baseUrl = env('ALDY_BASE_URL');

        $this->storeId = $storeId;
    }

    public static function adminToken(): string
    {
        return (new static(0))->getAdminToken();
    }

    public function getAdminToken(): string
    {
        return Cache::remember(
            'aldy:admin:token',
            now()->addMinutes(110),
            fn() => $this->callForAdmin()
        );
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
            fn() => $this->refresh()
        );
    }

    private function AccessLifetime(): Carbon
    {
        return now()->addMinutes(110);
    }

    private function RefreshLifetime(): Carbon
    {
        return now()->addDays(29);
    }

    private function cacheAccessKey(): string
    {
        return 'aldy:token:access:store:' . $this->storeId;
    }

    protected function refresh(): string
    {
        $refreshToken = Cache::get($this->cacheRefreshKey());

        if (empty($refreshToken)) {
            return $this->login();
        }

        if ($this->verify($refreshToken)) {
            return $this->getAccessByRefresh($refreshToken);
        }

        return $this->login();
    }

    private function cacheRefreshKey(): string
    {
        return 'aldy:token:refresh:store:' . $this->storeId;
    }

    protected function verify(string $token): bool
    {
        $response = Http::post(
            $this->baseUrl . 'verify',
            ['token' => $token]
        );

        return $response->successful();
    }

    private function getAccessByRefresh(string $refreshToken): string
    {
        $response = Http::post($this->baseUrl . 'refresh/', ['refresh' => $refreshToken]);

        if ($response->failed()) {
            throw new \Exception('Refreshing token failed. response is : ', $response->json());
        }

        Cache::put(
            $this->cacheAccessKey(),
            $accessToken = $response->json()['access'],
            $this->AccessLifetime()
        );

        return $accessToken;
    }

    protected function login(): string
    {
        $this->setUsernamePassword();

        $response = Http::post(
            $this->baseUrl . 'login/',
            [
                'username' => $this->username,
                'password' => $this->password,
            ]
        );

        if ($response->failed()) {
            throw new \Exception('Generating token from scratch for aldy failed. response is : ' . $response->status());
        }

        $tokens = $response->json();

        Cache::put(
            $this->cacheRefreshKey(),
            $tokens['refresh'],
            $this->RefreshLifetime()
        );

        Cache::put(
            $this->cacheAccessKey(),
            $tokens['access'],
            $this->AccessLifetime()
        );

        return $tokens['access'];
    }

    private function setUsernamePassword(): void
    {
        $authCredentials = Authentication::query()
            ->where('channel_id', ChannelsEnum::Aldy->value)
            ->where('store_id', $this->storeId)
            ->select(['username', 'password'])
            ->firstOr(fn () => throw new ModelNotFoundException('Could not find the Aldy integration credentials'));

        $this->username = $authCredentials->username;

        $this->password = $authCredentials->password;
    }

    private function callForAdmin(): string
    {
        $response = Http::post(
            $this->baseUrl . 'login/',
            [
                'username' => env('ALDY_ADMIN_USER'),
                'password' => env('ALDY_ADMIN_PASSWORD'),
            ]
        );

        if ($response->failed()) {
            throw new \Exception('Generating token from scratch for aldy failed. response is : ' . $response->json());
        }

        $tokens = $response->json();

        return $tokens['access'];
    }
}
