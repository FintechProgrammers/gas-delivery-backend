<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddBankAccountRequest extends FormRequest
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
            'account_number' => ['required', 'digits:10'],
            'bank_code' => ['required', 'numeric', 'exists:banks,bank_code'],
            'account_name' => ['required'],
            'bank_name' => ['required'],
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors(),
        ], \Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
