<?php

namespace App\Services\Contractors\Digi\Requests;

use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Contractors\Base\Requests\HasValidationError;
use App\Services\Contractors\Digi\Enums\StatusesEnum;
use App\Services\Order\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class OrderRequest extends FormRequest
{
    use HasValidationError;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $orders = Order::query()
                ->where('channel_id', ChannelsEnum::Digi->value)
                ->where('code', $this->code)
                ->orderByDesc('created_at')
                ->get();

            if ($orders->isEmpty()) {
                return;
            }

            if ($this->isCancel()) {
                return;
            }

            if ($this->isAmendment($orders->first())) {
                return;
            }

            $validator->errors()->add('order', 'Order has already exists');
        });
    }

    protected function isCancel(): bool
    {
        return $this->status == StatusesEnum::Cancel->value;
    }

    protected function isAmendment(Order $order): bool
    {
        return $order->statusCode == StatusesEnum::Edit->value ||
            $order->statusCode == StatusesEnum::Cancel->value ||
            $order->statusCode == StatusesEnum::Reject->value;
    }
}
