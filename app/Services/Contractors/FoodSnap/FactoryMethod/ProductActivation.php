<?php

namespace App\Services\Contractors\FoodSnap\FactoryMethod;

use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Contractors\Base\FactoryMethod\ProductActivationInterface;
use App\Services\Contractors\FoodSnap\Authentication\Authenticator;
use App\Services\Contractors\FoodSnap\Models\BasicInformation;
use App\Services\Contractors\FoodSnap\Models\Mapper;
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
        $this->url = env("PRODUCT_ACTIVATION_URL");

        $this->user = auth()->user();
    }

    public function setToken(): static
    {
        $this->token = Authenticator::token($this->user->store_id);

        return $this;
    }

    public function activate(array $products): bool
    {
        $products = array_map(function ($product) {
           $product['disable'] = $product['active'];
           unset($product['active']);

           return $product;
        }, $products);

        $this->setToken();

        $client = new Client();

        $clientId = BasicInformation::query()
            ->where('RetaileStoreID', Mapper::getRetailBySap($this->user->store_id))
            ->firstOr(fn () => throw new \Exception('Could not find client id for snap food activation'))->Client_ID;

        $body = [
            'products' => $products,
            'vendorCode' => $clientId,
        ];

        try {
            $response = $client->put($this->url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Accept' => 'application/json',
                ],
                'json' => $body,
            ]);

            $this->logAcceptSuccess($response, $body);

            return true;
        } catch (GuzzleException $e) {
            $this->logAcceptError($e, $body);

            return false;
        }
    }

    protected function logAcceptSuccess(ResponseInterface $response, array $body): void
    {
         // log
    }

    protected function logAcceptError(\Exception $exception, array $body): void
    {
        // log
    }

    public function __toString(): string
    {
        return 'اسنپ فود';
    }
}
