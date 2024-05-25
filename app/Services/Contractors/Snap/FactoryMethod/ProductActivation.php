<?php

namespace App\Services\Contractors\Snap\FactoryMethod;

use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Contractors\Base\FactoryMethod\ProductActivationInterface;
use App\Services\Contractors\Snap\Authentication\Authenticator;
use App\Services\Contractors\Snap\Models\BasicInformation;
use App\Services\Contractors\Snap\Models\Mapper;
use App\Services\Order\Models\OrderLog;
use App\Services\Panel\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class ProductActivation implements ProductActivationInterface
{
    protected string $token;

    protected readonly string $url;

    protected User $user;

    public function __construct()
    {
        $this->url = env('SNAP_API_URL');

        $this->user = auth()->user();
    }

    public function setToken(): static
    {
        $this->token = Authenticator::token($this->user->store_id);

        return $this;
    }

    public function activate(array $products): bool
    {
        // activate
    }

    public function __toString(): string
    {
        return 'اسنپ';
    }
}
