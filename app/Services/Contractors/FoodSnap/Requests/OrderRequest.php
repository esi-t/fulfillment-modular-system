<?php

namespace App\Services\Contractors\FoodSnap\Requests;

use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Contractors\Base\Requests\HasValidationError;
use App\Services\Contractors\FoodSnap\Enums\StatusesEnum;
use App\Services\Order\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

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
                ->where('code', $this->code)
                ->where('channel_id', ChannelsEnum::FoodSnap->value)
                ->orderByDesc('created_at')
                ->get();

            if ($orders->isEmpty()) {
                return;
            }

            $lastOrder = $orders->first();

            if ($this->isAllowed($lastOrder)) {
                return;
            }

            $validator->errors()->add('order', 'Order has already exists');
        });
    }

    protected function isAllowed(Order $order): bool
    {
        return $this->isAmendment($order) || $this->isCancel();
    }

    protected function isAmendment(Order $order): bool
    {
        return $order->statusCode == StatusesEnum::NeedForCall->value &&
        ($this->statusCode == StatusesEnum::NewOrder->value || $this->statusCode == StatusesEnum::SentToStore->value);
    }

    protected function isCancel(): bool
    {
        return $this->statusCode == StatusesEnum::CanceledOrder->value;
    }
}
