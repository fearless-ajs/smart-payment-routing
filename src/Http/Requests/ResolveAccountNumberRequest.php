<?php

namespace Fearless\SmartPaymentRouting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResolveAccountNumberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'account_number'    =>'required|numeric|digits_between:1,10',
            'bank_code'         =>'required|numeric|digits_between:1,10',
        ];
    }
}
