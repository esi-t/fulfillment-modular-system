<?php

namespace App\Services\Panel\Requests;

use App\Services\Panel\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => ['required', 'exists:users,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $this->id],
            'store_id' => ['required', 'integer'],
            'mobile' => ['required', 'numeric', 'unique:users,mobile,' . $this->id],
            'role' => ['required', Rule::in([User::STORE, User::ADMIN])],
            'status' => ['boolean'],
            'password' => ['required', 'string', 'max:255'],
        ];
    }
}
