<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Validation\Validator;

class BusinessRegistration extends FormRequest
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
            'email' => ['nullable', 'email'],
            'phone_number' => ['required', 'string'],
            'business_name' => ['required', 'string'],
            'photo' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'longitude' => ['nullable', 'numeric'], // Validate as a numeric value
            'latitude' => ['nullable', 'numeric'], // Validate as a numeric value
            'business_type' => ['required', 'in:retail,gas-station'],
            'dpr_number' => ['nullable', 'string', 'required_if:business_type,gas-station'],
        ];
    }

    /**
     * Custom validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.email' => 'The email must be a valid email address.',
            'phone_number.required' => 'The phone number is required.',
            'business_name.required' => 'The business name is required.',
            'business_name.string' => 'The business name must be a string.',
            'photo.string' => 'The photo must be a valid string.',
            'address.string' => 'The address must be a valid string.',
            'longitude.numeric' => 'The longitude must be a valid number.',
            'latitude.numeric' => 'The latitude must be a valid number.',
            'business_type.required' => 'The business type is required.',
            'business_type.in' => 'The business type must be either "retail" or "gas-station".',
            'dpr_number.required_if' => 'The DPR number is required for gas stations.',
            'dpr_number.string' => 'The DPR number must be a string.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
