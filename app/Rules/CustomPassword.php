<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CustomPassword implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute minimum 8 characters at least 1 Uppercase Alpha, 1 Lowercase Alpha and 1 Number.';
    }
}
