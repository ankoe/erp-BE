<?php

namespace App\Http\Validations;

use Illuminate\Validation\Rule;

class ConfigApprovalValidation
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
    public static function store()
    {
        return [
            'role_id' => ['required', 'integer', 'exists:App\Models\Role,id'],
            'group' => ['required', 'string', Rule::in(RoleGroup::getValues())],
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

    /**
     * @return array
     */
    public static function sort()
    {
        return [
            'approvals' => ['required', 'array'],
            'approvals.*.role_id' => ['required', 'integer', 'distinct', 'exists:App\Models\Role,id'],
            'approvals.*.order' => ['required', 'integer', 'distinct'],
        ];
    }
}
