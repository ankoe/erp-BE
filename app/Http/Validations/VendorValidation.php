<?php

namespace App\Http\Validations;

use Illuminate\Validation\Rule;

class VendorValidation
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
            'material_categories' => ['required', 'array'],
            'material_categories.*' => ['required', 'integer', 'distinct', 'exists:App\Models\MaterialCategory,id'],
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email:rfc,dns', 'max:50'],
            'email_ccs' => ['required', 'array'],
            'email_ccs.*' => ['required', 'string', 'max:30', 'distinct'],
            'mobiles' => ['required', 'array'],
            'mobiles.*' => ['required', 'string', 'max:15', 'distinct'],
        ];
    }

    /**
     * @return array
     */
    public static function update()
    {
        return [
            'material_categories' => ['required', 'array'],
            'material_categories.*' => ['required', 'integer', 'distinct', 'exists:App\Models\MaterialCategory,id'],
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email:rfc,dns', 'max:50'],
            'email_ccs' => ['required', 'array'],
            'email_ccs.*' => ['required', 'string', 'max:30', 'distinct'],
            'mobiles' => ['required', 'array'],
            'mobiles.*' => ['required', 'string', 'max:15', 'distinct'],
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
