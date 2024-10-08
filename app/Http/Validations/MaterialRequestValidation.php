<?php

namespace App\Http\Validations;

use Illuminate\Validation\Rule;

class MaterialRequestValidation
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
            'material_category_id' => ['nullable', 'integer', 'exists:App\Models\MaterialCategory,id'],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'unit_id' => ['required', 'integer', 'exists:App\Models\Unit,id'],
            'price' => ['nullable', 'integer', 'digits_between:0,99999999'],
            'stock' => ['required'],
            'attachment' => ['required', 'file', 'mimetypes:image/jpeg,image/png,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword,application/pdf,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel']
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
