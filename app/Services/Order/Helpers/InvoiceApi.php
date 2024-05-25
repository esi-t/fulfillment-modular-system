<?php

namespace App\Services\Order\Helpers;

use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Contractors\Base\Models\InvoiceIntegrationLog;
use Illuminate\Support\Facades\Http;

class InvoiceApi
{
    public static function request(array $data)
    {
        return (new static())->makeRequest($data);
    }

    public function makeRequest(array $data)
    {
        $dataToSend = $this->prepareData($data);

        $response = Http::timeout(10)
            ->post(env('INVOICE_API_URL'), $dataToSend);

        if ($response->failed()) {
            InvoiceIntegrationLog::query()
                ->create($response->json() ?? []);
        }

        return $response;
    }

    private function prepareData(array $data): array
    {
        return ChannelsEnum::implementor($data['channel_id'])->prepareData($data);
    }
}
