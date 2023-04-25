<?php

namespace App\Http\Validations;

use App\Rules\CustomPassword;
use Illuminate\Validation\Rule;

class AuthValidation
{   
	
    /**
     * @return array
     */
    public static function login()
    {
        return [
            'email' => ['required', 'email:rfc,dns'],
            'password' => ['required'],
        ];
    }


    /**
     * @return array
     */
    public static function register()
    {
        return [
            'name' => ['required', 'max:40'],
            'email' => ['required', 'email:rfc,dns', 'unique:App\Models\User,email'],
            'mobile' => ['nullable', 'digits_between:11,13', 'unique:App\Models\User,mobile'],
            'password' => ['required', 'confirmed', new CustomPassword],
            'company' => ['required', 'max:100'],
        ];
    }


    /**
     * @return array
     */
    public static function activationResend()
    {
        return [
            'email' => [
                'required',
                'email:rfc,dns'
            ],
        ];
    }


    /**
     * @return array
     */
    public static function activationSubmit()
    {
        return [
            'token' => [
                'required',
            ],
        ];
    }


    /**
     * @return array
     */
    public static function passwordForgot()
    {
        return [
            'email' => ['required', 'email:rfc,dns'],
        ];
    }


    /**
     * @return array
     */
    public static function passwordReset()
    {
        return [
            'token' => ['required'],
            'password' => ['required', 'confirmed', new CustomPassword]
        ];
    }  
}
