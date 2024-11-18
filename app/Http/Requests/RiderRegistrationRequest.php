<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Password;

class RiderRegistrationRequest extends FormRequest
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
            'first_name'    => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone_number'  => ['required', 'numeric', 'digits_between:10,15', 'unique:users,phone_number'],
            'token'         => ['required', 'digits:4'], // Assuming a 4-digit token is required
            'password'      => [
                'required',
                'string',
                Password::min(8)->mixedCase()->numbers()->symbols(),
                'confirmed', // Ensures password confirmation matches
            ],
            'vehicle_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // Optional image with constraints
            'vehicle_colour' => ['nullable', 'string', 'max:100'], // Optional field with a max length
            'vehicle_number' => ['nullable', 'string', 'max:50'], // Optional field with max length
            'address'       => ['nuulable', 'string', 'max:255'], // Required address with max length
            'driver_license' => ['nullable', 'image', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a string.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.mixed_case' => 'Password must contain both uppercase and lowercase letters.',
            'password.numbers' => 'Password must contain at least one number.',
            'password.symbols' => 'Password must contain at least one special character.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
