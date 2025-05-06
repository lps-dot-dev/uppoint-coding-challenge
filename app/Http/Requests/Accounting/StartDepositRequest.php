<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StartDepositRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @todo This is whether we would determine if the user can use the balance source given */
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => ['required', 'decimal:2'],
            'source' => ['required']
        ];
    }
}
