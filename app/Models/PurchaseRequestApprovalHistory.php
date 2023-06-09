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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

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
