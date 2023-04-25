<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CurrentPassword implements Rule
{

    public $type;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($type = '')
    {
        $this->type = $type;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = ($this->type)? Auth::guard($this->type)->User() : Auth::user();
        
        return Hash::check($value, $user->password);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Password lama tidak cocok';
    }
}
