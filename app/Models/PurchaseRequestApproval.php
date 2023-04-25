<?php

namespace App\Models;

use App\Traits\Filter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestApproval extends Model
{
    use Filterable, HasFactory;

    protected $fillable = [
        'purchase_request_id',
        'order',
        'role_id',
        'approve_user_id',
        'approved_at'
    ];

    /***********************************************
     *  1. Relation
    ***********************************************/

    /***********************************************
     *  2. Getter & Setter
    ***********************************************/

    /***********************************************
     *  3. Scope
    ***********************************************/

    /***********************************************
     *  4. Function
    ***********************************************/
}
