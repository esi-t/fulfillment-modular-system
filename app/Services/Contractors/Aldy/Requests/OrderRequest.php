<?php

namespace App\Services\Contractors\Aldy\Requests;

use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Contractors\Base\Requests\HasValidationError;
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

            $result = Order::query()
                ->where('code', $this->code)
                ->where('channel_id', ChannelsEnum::Aldy->value)
                ->exists();

            if ($result){
                $validator->errors()->add('order', 'Order has already exists');
            }
        });
    }
}
