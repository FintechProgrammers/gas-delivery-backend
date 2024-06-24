<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrongPassword implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $hasUppercase = preg_match('/[A-Z]/', $value);
        $hasSpecialCharacter = preg_match('/[!@#$%^&*(),.?":{}|<>]/', $value);

        return $hasUppercase && $hasSpecialCharacter;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Password must contain at least one uppercase letter and one special character.';
    }
}
