<?php

namespace App\Http\Validations;

use Illuminate\Validation\Rule;

class UserValidation
{

	/**
     * @return array
     */
    public static function index()
    {
        return [
            'per_page' => ['numeric', 'max:100'],
            'order_type' => [
                Rule::in(['asc', 'desc'])
            ],
            'order_by' => [
                Rule::in([
                ])
            ],
            // // 'title' => ['sometimes','required', 'numeric', 'digits:1'],
            // 'is_publish' => ['sometimes','required', 'boolean'],
            // 'show_title' => ['sometimes','required', 'boolean'],
            // 'show_summary' => ['sometimes','required', 'boolean'],
            // 'publish_start' => ['sometimes','required'], // need validate format too
            // 'publish_end' => ['sometimes','required'], // need validate format too
            // 'created_at' => ['sometimes','required'], // need validate format too
        ];
    }

    /**
     * @return array
     */
    public static function all()
    {
        return [
            'order_type' => [
                Rule::in(['asc', 'desc'])
            ],
            'order_by' => [
                Rule::in([
                ])
            ],
        ];
    }

    /**
     * @return array
     */
    public static function show()
    {
        return [
            // 'order_type' => [
            //     Rule::in(['asc', 'desc'])
            // ],
            // 'order_by' => [
            //     Rule::in([
            //     ])
            // ],
        ];
    }

    /**
     * @return array
     */
    public static function store()
    {
        return [
            'name' => ['required', 'max:40'],
            'email' => ['required', 'email:rfc,dns', 'unique:App\Models\User,email'],
            'mobile' => ['nullable', 'digits_between:11,13', 'unique:App\Models\User,mobile'],
            'role_id' => ['required', 'integer', 'exists:App\Models\Role,id'],
        ];
    }

    /**
     * @return array
     */
    public static function update($id)
    {
        return [
            'name' => ['required', 'max:40'],
            'email' => ['required', 'email:rfc,dns', Rule::unique('App\Models\User')->ignore($id)],
            'mobile' => ['nullable', 'digits_between:11,13', Rule::unique('App\Models\User')->ignore($id)],
            'is_active' => ['required', 'string', Rule::in(['true', 'false'])],
            'role_id' => ['required', 'integer', 'exists:App\Models\Role,id'],
        ];
    }

    /**
     * @return array
     */
    public static function destroy()
    {
        return [
            // 'order_type' => [
            //     Rule::in(['asc', 'desc'])
            // ],
            // 'order_by' => [
            //     Rule::in([
            //     ])
            // ],
        ];
    }
}
