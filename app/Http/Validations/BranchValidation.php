<?php

namespace App\Http\Validations;

use Illuminate\Validation\Rule;

class BranchValidation
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
            'name' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:100'],
            'emails' => ['required', 'array'],
            'emails.*' => ['required', 'email:rfc,dns', 'max:30', 'distinct'],
            'mobiles' => ['required', 'array'],
            'mobiles.*' => ['required', 'string', 'max:15', 'distinct'],
            'postal_code' => ['required', 'string', 'max:10'],
            'city' => ['required', 'string', 'max:40'],
        ];
    }

    /**
     * @return array
     */
    public static function update()
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:100'],
            'emails' => ['required', 'array'],
            'emails.*' => ['required', 'email:rfc,dns', 'max:30', 'distinct'],
            'mobiles' => ['required', 'array'],
            'mobiles.*' => ['required', 'string', 'max:15', 'distinct'],
            'postal_code' => ['required', 'string', 'max:10'],
            'city' => ['required', 'string', 'max:40'],
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
