<?php

namespace App\Http\Validations;

use App\Rules\CurrentPassword;
use App\Rules\CustomPassword;


class ProfileValidation
{   

    /**
     * @return array
     */
    public static function update()
    {
        return [
            'name' => ['required', 'max:40'],
            'mobile' => ['nullable', 'digits_between:11,13'],
            'image_profile' => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg']
        ];
    }


    /**
     * @return array
     */
    public static function passwordUpdate()
    {
        return [
            'password' => ['required', 'confirmed', 'different:password_current', new CustomPassword],
            'password_current' => ['required', new CurrentPassword],
        ];
    }


    /**
     * @return array
     */
    public static function imageUpdate()
    {
        return [
            'image_profile' => ['required', 'file', 'image', 'mimes:jpeg,png,jpg']
        ];
    }
    
}
