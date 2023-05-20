<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestApprovalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_request_id',
        'user_id',
        'role_id',
        'approved_at',
        'approve_status',
        'remarks'
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
